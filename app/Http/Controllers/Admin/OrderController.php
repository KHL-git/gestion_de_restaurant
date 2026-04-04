<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class OrderController extends Controller
{
    public function index(Request $request): View
    {
        $status = (string) $request->query('status', '');
        $search = trim((string) $request->query('search', ''));

        $orders = Order::query()
            ->with(['user', 'table', 'items.menu'])
            ->when($status !== '', fn ($query) => $query->where('statut', $status))
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($builder) use ($search) {
                    $builder
                        ->where('id', $search)
                        ->orWhere('notes', 'like', "%{$search}%")
                        ->orWhereHas('user', fn ($userQuery) => $userQuery->where('name', 'like', "%{$search}%")->orWhere('email', 'like', "%{$search}%"))
                        ->orWhereHas('items.menu', fn ($menuQuery) => $menuQuery->where('nom', 'like', "%{$search}%"));
                });
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('admin.orders.index', [
            'orders' => $orders,
            'status' => $status,
            'search' => $search,
            'statuses' => Order::statuses(),
        ]);
    }

    public function show(Order $order): View
    {
        $order->load(['user', 'table', 'items.menu']);

        return view('admin.orders.show', [
            'order' => $order,
            'statuses' => Order::statuses(),
        ]);
    }

    public function updateStatus(Request $request, Order $order): RedirectResponse
    {
        $validated = $request->validate([
            'statut' => ['required', Rule::in(array_keys(Order::statuses()))],
        ]);

        $order->update(['statut' => $validated['statut']]);

        return back()->with('success', 'Le statut de la commande a ete mis a jour.');
    }
}