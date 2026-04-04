<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Models\Sale;
use App\Models\SaleLine;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class SaleController extends Controller
{
    public function index(Request $request): View
    {
        $search = trim((string) $request->query('search', ''));
        $status = (string) $request->query('status', '');
        $paymentMethod = (string) $request->query('payment_method', '');
        $client = trim((string) $request->query('client', ''));
        $dateFrom = (string) $request->query('date_from', '');
        $dateTo = (string) $request->query('date_to', '');

        $sales = Sale::query()
            ->with(['lines.menu', 'user'])
            ->search($search)
            ->when($status !== '', fn ($query) => $query->where('status', $status))
            ->when($paymentMethod !== '', fn ($query) => $query->where('payment_method', $paymentMethod))
            ->when($client !== '', function ($query) use ($client) {
                $query->where(function ($builder) use ($client) {
                    $builder
                        ->where('client_name', 'like', "%{$client}%")
                        ->orWhereHas('user', function ($userQuery) use ($client) {
                            $userQuery
                                ->where('name', 'like', "%{$client}%")
                                ->orWhere('email', 'like', "%{$client}%");
                        });
                });
            })
            ->when($dateFrom !== '', fn ($query) => $query->whereDate('sold_at', '>=', $dateFrom))
            ->when($dateTo !== '', fn ($query) => $query->whereDate('sold_at', '<=', $dateTo))
            ->latest('sold_at')
            ->latest('id')
            ->paginate(10)
            ->withQueryString();

        return view('admin.sales.index', [
            'sales' => $sales,
            'search' => $search,
            'status' => $status,
            'paymentMethod' => $paymentMethod,
            'client' => $client,
            'dateFrom' => $dateFrom,
            'dateTo' => $dateTo,
            'statuses' => Sale::statuses(),
            'paymentMethods' => Sale::paymentMethods(),
        ]);
    }

    public function create(): View
    {
        return view('admin.sales.create', $this->formData(new Sale([
            'status' => Sale::STATUS_PAID,
            'payment_method' => Sale::PAYMENT_METHOD_CASH,
            'sold_at' => now(),
        ])));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validatedData($request);

        DB::transaction(function () use ($validated) {
            $sale = Sale::create($this->salePayload($validated));
            $this->syncLines($sale, $validated['items']);
        });

        return redirect()
            ->route('admin.sales.index')
            ->with('success', 'La vente a ete enregistree avec succes.');
    }

    public function show(Sale $sale): View
    {
        $sale->load(['lines.menu', 'user']);

        return view('admin.sales.show', compact('sale'));
    }

    public function edit(Sale $sale): View
    {
        $sale->load(['lines.menu', 'user']);

        return view('admin.sales.edit', $this->formData($sale));
    }

    public function update(Request $request, Sale $sale): RedirectResponse
    {
        $validated = $this->validatedData($request);

        DB::transaction(function () use ($sale, $validated) {
            $sale->update($this->salePayload($validated, $sale));
            $this->syncLines($sale, $validated['items']);
        });

        return redirect()
            ->route('admin.sales.show', $sale)
            ->with('success', 'La vente a ete mise a jour avec succes.');
    }

    public function destroy(Sale $sale): RedirectResponse
    {
        $sale->delete();

        return redirect()
            ->route('admin.sales.index')
            ->with('success', 'La vente a ete supprimee avec succes.');
    }

    private function validatedData(Request $request): array
    {
        return $request->validate([
            'user_id' => ['nullable', 'exists:users,id'],
            'client_name' => ['nullable', 'string', 'max:255'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.menu_id' => ['required', 'exists:menus,id'],
            'items.*.quantity' => ['required', 'integer', 'min:1'],
            'payment_method' => ['required', Rule::in(array_keys(Sale::paymentMethods()))],
            'status' => ['required', Rule::in(array_keys(Sale::statuses()))],
            'sold_at' => ['required', 'date'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ]);
    }

    private function salePayload(array $validated, ?Sale $sale = null): array
    {
        $user = isset($validated['user_id']) ? User::query()->find($validated['user_id']) : null;
        $lineData = $this->linePayloads($validated['items']);
        $firstLine = $lineData[0];
        $totalQuantity = array_sum(array_column($lineData, 'quantity'));
        $total = array_sum(array_column($lineData, 'total'));

        return [
            'reference' => $sale?->reference ?? $this->generateReference(),
            'menu_id' => $firstLine['menu_id'],
            'user_id' => $user?->id,
            'client_name' => $validated['client_name'] ?: $user?->name,
            'quantity' => $totalQuantity,
            'unit_price' => $firstLine['unit_price'],
            'total' => $total,
            'payment_method' => $validated['payment_method'],
            'status' => $validated['status'],
            'sold_at' => Carbon::parse($validated['sold_at']),
            'notes' => $validated['notes'] ?? null,
        ];
    }

    private function formData(Sale $sale): array
    {
        return [
            'sale' => $sale,
            'menus' => Menu::query()->orderBy('nom')->get(),
            'users' => User::query()->orderBy('name')->get(),
            'statuses' => Sale::statuses(),
            'paymentMethods' => Sale::paymentMethods(),
        ];
    }

    private function syncLines(Sale $sale, array $items): void
    {
        $sale->lines()->delete();

        $lines = array_map(
            fn (array $line) => array_merge($line, [
                'sale_id' => $sale->id,
                'created_at' => now(),
                'updated_at' => now(),
            ]),
            $this->linePayloads($items)
        );

        SaleLine::query()->insert($lines);
    }

    private function linePayloads(array $items): array
    {
        $menus = Menu::query()
            ->whereIn('id', collect($items)->pluck('menu_id')->all())
            ->get()
            ->keyBy('id');

        return collect($items)
            ->map(function (array $item) use ($menus) {
                $menu = $menus->get((int) $item['menu_id']);

                abort_if(! $menu, 422, 'Le plat selectionne est introuvable.');

                $quantity = (int) $item['quantity'];
                $unitPrice = (float) $menu->prix;

                return [
                    'menu_id' => $menu->id,
                    'quantity' => $quantity,
                    'unit_price' => $unitPrice,
                    'total' => $unitPrice * $quantity,
                ];
            })
            ->values()
            ->all();
    }

    private function generateReference(): string
    {
        do {
            $reference = 'VTE-'.now()->format('Ymd').'-'.str_pad((string) random_int(1, 9999), 4, '0', STR_PAD_LEFT);
        } while (Sale::query()->where('reference', $reference)->exists());

        return $reference;
    }
}