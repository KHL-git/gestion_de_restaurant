@extends('layouts.admin')

@section('title', 'Gestion des commandes')
@section('admin_title', 'Gestion des commandes clients')
@section('admin_subtitle', 'Valide, consulte et suis les commandes passées depuis l espace public.')

@section('content')
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="d-flex justify-content-between align-items-center gap-3 flex-wrap mb-4">
        <form method="GET" action="{{ route('admin.orders.index') }}" class="d-flex gap-2 flex-wrap">
            <input type="search" name="search" value="{{ $search }}" class="form-control" placeholder="Commande, client, email, plat" style="min-width: 320px;">
            <select name="status" class="form-select" style="min-width: 220px;">
                <option value="">Tous les statuts</option>
                @foreach($statuses as $value => $label)
                    <option value="{{ $value }}" @selected($status === $value)>{{ $label }}</option>
                @endforeach
            </select>
            <button type="submit" class="btn btn-primary">Filtrer</button>
        </form>
    </div>

    <div class="card shadow-sm border-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Client</th>
                        <th>Panier</th>
                        <th>Table</th>
                        <th>Statut</th>
                        <th>Total</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $order)
                        <tr>
                            <td class="fw-semibold">{{ $order->id }}</td>
                            <td>
                                <div>{{ $order->user->name }}</div>
                                <div class="text-muted small">{{ $order->user->email }}</div>
                            </td>
                            <td>{{ $order->itemsSummary(2) }}</td>
                            <td>{{ $order->table ? 'Table '.$order->table->numero : 'Non précisée' }}</td>
                            <td><span class="badge text-bg-dark">{{ $order->statusLabel() }}</span></td>
                            <td>{{ $order->formattedTotal() }}</td>
                            <td class="text-end"><a href="{{ route('admin.orders.show', $order) }}" class="btn btn-outline-primary btn-sm">Ouvrir</a></td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="text-center py-4 text-muted">Aucune commande trouvée.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if($orders->hasPages())
        <div class="mt-4">{{ $orders->links() }}</div>
    @endif
@endsection