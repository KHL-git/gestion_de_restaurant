@extends('layouts.admin')

@section('title', 'Fiche commande')
@section('admin_title', 'Fiche commande client')
@section('admin_subtitle', 'Consulte le detail d une commande et mets a jour son statut.')

@section('content')
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="d-flex justify-content-between align-items-center gap-3 flex-wrap mb-4">
        <div>
            <h2 class="h3 mb-1">Commande #{{ $order->id }}</h2>
            <p class="text-muted mb-0">{{ $order->user->name }} - {{ $order->user->email }}</p>
        </div>
        <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-secondary">Retour</a>
    </div>

    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body">
            <form method="POST" action="{{ route('admin.orders.update-status', $order) }}" class="d-flex gap-2 flex-wrap align-items-end">
                @csrf
                @method('PATCH')
                <div>
                    <label class="form-label">Statut</label>
                    <select name="statut" class="form-select">
                        @foreach($statuses as $value => $label)
                            <option value="{{ $value }}" @selected($order->statut === $value)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">Mettre a jour</button>
            </form>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h3 class="h5 mb-3">Lignes de commande</h3>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr><th>Plat</th><th>Quantité</th><th>Prix</th><th>Total</th></tr>
                            </thead>
                            <tbody>
                                @foreach($order->items as $item)
                                    <tr>
                                        <td>{{ $item->menu?->nom ?? 'Plat supprimé' }}</td>
                                        <td>{{ $item->quantity }}</td>
                                        <td>{{ $item->formattedUnitPrice() }}</td>
                                        <td>{{ $item->formattedTotal() }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h3 class="h5 mb-3">Résumé</h3>
                    <p class="mb-2"><strong>Table:</strong> {{ $order->table ? 'Table '.$order->table->numero : 'Non précisée' }}</p>
                    <p class="mb-2"><strong>Total:</strong> {{ $order->formattedTotal() }}</p>
                    @if($order->notes)
                        <p class="mb-0"><strong>Note:</strong> {{ $order->notes }}</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection