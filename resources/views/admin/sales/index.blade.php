@extends('layouts.admin')

@section('title', 'Gestion des ventes')
@section('admin_title', 'Gestion des ventes')
@section('admin_subtitle', 'Enregistre une vente, consulte l historique et retrouve rapidement une transaction.')

@section('content')
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center gap-3 flex-wrap mb-3">
                <div>
                    <h2 class="h5 mb-1">Filtres avances</h2>
                    <p class="text-muted mb-0">Affiche les ventes par client, periode, statut et mode de paiement.</p>
                </div>
                <a href="{{ route('admin.sales.create') }}" class="btn btn-success">Enregistrer une vente</a>
            </div>

            <form method="GET" action="{{ route('admin.sales.index') }}" class="row g-3 align-items-end">
                <div class="col-xl-3 col-lg-6">
                    <label for="sales-search" class="form-label">Recherche globale</label>
                    <input
                        id="sales-search"
                        type="search"
                        name="search"
                        value="{{ $search }}"
                        class="form-control"
                        placeholder="Reference, plat, client, paiement"
                    >
                </div>

                <div class="col-xl-2 col-lg-6">
                    <label for="sales-client" class="form-label">Client</label>
                    <input
                        id="sales-client"
                        type="search"
                        name="client"
                        value="{{ $client }}"
                        class="form-control"
                        placeholder="Nom ou email"
                    >
                </div>

                <div class="col-xl-2 col-lg-6">
                    <label for="sales-status" class="form-label">Statut</label>
                    <select id="sales-status" name="status" class="form-select">
                        <option value="">Tous les statuts</option>
                        @foreach($statuses as $value => $label)
                            <option value="{{ $value }}" @selected($status === $value)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-xl-2 col-lg-6">
                    <label for="sales-payment-method" class="form-label">Paiement</label>
                    <select id="sales-payment-method" name="payment_method" class="form-select">
                        <option value="">Tous les modes</option>
                        @foreach($paymentMethods as $value => $label)
                            <option value="{{ $value }}" @selected($paymentMethod === $value)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-xl-1 col-lg-6">
                    <label for="sales-date-from" class="form-label">Du</label>
                    <input id="sales-date-from" type="date" name="date_from" value="{{ $dateFrom }}" class="form-control">
                </div>

                <div class="col-xl-1 col-lg-6">
                    <label for="sales-date-to" class="form-label">Au</label>
                    <input id="sales-date-to" type="date" name="date_to" value="{{ $dateTo }}" class="form-control">
                </div>

                <div class="col-12 d-flex gap-2 flex-wrap">
                    <button type="submit" class="btn btn-primary">Filtrer</button>
                    @if($search || $status || $paymentMethod || $client || $dateFrom || $dateTo)
                        <a href="{{ route('admin.sales.index') }}" class="btn btn-outline-secondary">Reinitialiser</a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    <div class="d-flex gap-2 flex-wrap mb-4">
        <a href="{{ route('admin.sales.index', array_filter(['search' => $search, 'client' => $client, 'payment_method' => $paymentMethod, 'date_from' => $dateFrom, 'date_to' => $dateTo])) }}" class="btn {{ $status === '' ? 'btn-dark' : 'btn-outline-dark' }} btn-sm">Toutes</a>
        @foreach($statuses as $value => $label)
            <a href="{{ route('admin.sales.index', array_filter(['search' => $search, 'status' => $value, 'client' => $client, 'payment_method' => $paymentMethod, 'date_from' => $dateFrom, 'date_to' => $dateTo])) }}" class="btn {{ $status === $value ? 'btn-primary' : 'btn-outline-primary' }} btn-sm">{{ $label }}</a>
        @endforeach
    </div>

    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body d-flex justify-content-between align-items-center gap-3 flex-wrap">
            <div>
                <h2 class="h5 mb-1">Historique des ventes</h2>
                <p class="text-muted mb-0">{{ $sales->total() }} vente{{ $sales->total() > 1 ? 's' : '' }} enregistree{{ $sales->total() > 1 ? 's' : '' }}</p>
            </div>
            <div class="text-end">
                <div class="small text-muted">Total visible</div>
                <div class="fs-5 fw-semibold">{{ number_format((float) $sales->getCollection()->sum('total'), 0, ',', ' ') }} FCFA</div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm border-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Reference</th>
                        <th>Client</th>
                        <th>Panier</th>
                        <th>Statut</th>
                        <th>Paiement</th>
                        <th>Date</th>
                        <th>Total</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($sales as $sale)
                        <tr>
                            <td class="fw-semibold">{{ $sale->reference }}</td>
                            <td>
                                <div>{{ $sale->customerLabel() }}</div>
                                @if($sale->user)
                                    <div class="text-muted small">{{ $sale->user->email }}</div>
                                @endif
                            </td>
                            <td>
                                <div class="fw-semibold">{{ $sale->itemsSummary(2) }}</div>
                                <div class="text-muted small">{{ $sale->itemsCount() }} article{{ $sale->itemsCount() > 1 ? 's' : '' }}</div>
                            </td>
                            <td>
                                <span class="badge {{ $sale->status === 'payee' ? 'text-bg-success' : ($sale->status === 'annulee' ? 'text-bg-danger' : 'text-bg-warning') }}">
                                    {{ $sale->statusLabel() }}
                                </span>
                            </td>
                            <td>{{ $sale->paymentMethodLabel() }}</td>
                            <td>{{ $sale->sold_at->format('d/m/Y H:i') }}</td>
                            <td class="fw-semibold">{{ $sale->formattedTotal() }}</td>
                            <td>
                                <div class="d-flex justify-content-end gap-2 flex-wrap">
                                    <a href="{{ route('admin.sales.show', $sale) }}" class="btn btn-outline-dark btn-sm">Afficher</a>
                                    <a href="{{ route('admin.sales.edit', $sale) }}" class="btn btn-outline-primary btn-sm">Modifier</a>
                                    <form method="POST" action="{{ route('admin.sales.destroy', $sale) }}" onsubmit="return confirm('Supprimer cette vente ?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger btn-sm">Supprimer</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-4 text-muted">Aucune vente trouvee.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if($sales->hasPages())
        <div class="mt-4">{{ $sales->links() }}</div>
    @endif
@endsection