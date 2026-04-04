<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Table;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class OrderController extends Controller
{
    public function index(Request $request): View
    {
        $orders = $request->user()
            ->orders()
            ->with(['items.menu', 'table'])
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('orders.index', [
            'orders' => $orders,
        ]);
    }

    public function create(Request $request): View
    {
        $selectedMenu = null;

        if ($request->filled('menu')) {
            $selectedMenu = Menu::query()
                ->where('disponible', true)
                ->find($request->integer('menu'));
        }

        return view('orders.create', [
            'menus' => Menu::query()->where('disponible', true)->orderBy('categorie')->orderBy('nom')->get(),
            'tables' => Table::query()->where('disponible', true)->orderBy('numero')->get(),
            'selectedMenu' => $selectedMenu,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'table_id' => ['nullable', 'exists:tables,id'],
            'notes' => ['nullable', 'string', 'max:1000'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.menu_id' => ['required', 'exists:menus,id'],
            'items.*.quantity' => ['required', 'integer', 'min:1'],
        ]);

        $order = DB::transaction(function () use ($request, $validated) {
            $lineData = $this->linePayloads($validated['items']);
            $total = array_sum(array_column($lineData, 'total'));

            $order = Order::create([
                'user_id' => $request->user()->id,
                'table_id' => $validated['table_id'] ?? null,
                'total' => $total,
                'statut' => Order::STATUS_PENDING,
                'notes' => $validated['notes'] ?? null,
            ]);

            OrderItem::query()->insert(array_map(
                fn (array $item) => array_merge($item, [
                    'order_id' => $order->id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]),
                $lineData
            ));

            return $order;
        });

        return redirect()
            ->route('orders.index')
            ->with('success', 'Votre commande a ete envoyee avec succes.');
    }

    public function show(Request $request, Order $order): View
    {
        $this->ensureOwnership($request, $order);
        $order->load(['items.menu', 'table']);

        return view('orders.show', [
            'order' => $order,
        ]);
    }

    public function cancel(Request $request, Order $order): RedirectResponse
    {
        $this->ensureOwnership($request, $order);

        if ($order->statut !== Order::STATUS_PENDING) {
            return back()->withErrors(['order' => 'Seules les commandes en attente peuvent etre annulees.']);
        }

        $order->update(['statut' => Order::STATUS_CANCELLED]);

        return back()->with('success', 'La commande a ete annulee.');
    }

    private function linePayloads(array $items): array
    {
        $menus = Menu::query()
            ->where('disponible', true)
            ->whereIn('id', collect($items)->pluck('menu_id')->all())
            ->get()
            ->keyBy('id');

        return collect($items)
            ->map(function (array $item) use ($menus) {
                $menu = $menus->get((int) $item['menu_id']);

                abort_if(! $menu, 422, 'Le plat selectionne est indisponible.');

                $quantity = (int) $item['quantity'];
                $unitPrice = (float) $menu->prix;

                return [
                    'menu_id' => $menu->id,
                    'quantity' => $quantity,
                    'unit_price' => $unitPrice,
                    'total' => $quantity * $unitPrice,
                ];
            })
            ->values()
            ->all();
    }

    private function ensureOwnership(Request $request, Order $order): void
    {
        throw_if($order->user_id !== $request->user()->id, AuthorizationException::class);
    }
}
