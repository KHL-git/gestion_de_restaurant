@extends('layouts.admin')

@section('title', 'Fiche vente')
@section('admin_title', 'Fiche vente')
@section('admin_subtitle', 'Consulte toutes les informations utiles d une transaction en un seul endroit.')

@section('content')
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="d-flex justify-content-between align-items-center gap-3 flex-wrap mb-4">
        <div>
            <h2 class="h3 mb-1">{{ $sale->reference }}</h2>
            <p class="text-muted mb-0">Vente enregistree le {{ $sale->sold_at->format('d/m/Y a H:i') }}</p>
        </div>
        <div class="d-flex gap-2 flex-wrap">
            <a href="{{ route('admin.sales.edit', $sale) }}" class="btn btn-primary">Modifier</a>
            <a href="{{ route('admin.sales.index') }}" class="btn btn-outline-secondary">Retour</a>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body">
                    <h3 class="h5 mb-3">Details de la vente</h3>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="text-muted small">Client</div>
                            <div class="fw-semibold">{{ $sale->customerLabel() }}</div>
                            @if($sale->user)
                                <div class="text-muted small">{{ $sale->user->email }}</div>
                            @endif
                        </div>
                        <div class="col-md-6">
                            <div class="text-muted small">Panier</div>
                            <div class="fw-semibold">{{ $sale->itemsSummary(3) }}</div>
                        </div>
                        <div class="col-md-4">
                            <div class="text-muted small">Articles</div>
                            <div class="fw-semibold">{{ $sale->itemsCount() }}</div>
                        </div>
                        <div class="col-md-4">
                            <div class="text-muted small">Total</div>
                            <div class="fw-semibold">{{ $sale->formattedTotal() }}</div>
                        </div>
                        <div class="col-md-6">
                            <div class="text-muted small">Mode de paiement</div>
                            <div class="fw-semibold">{{ $sale->paymentMethodLabel() }}</div>
                        </div>
                        <div class="col-md-6">
                            <div class="text-muted small">Statut</div>
                            <div>
                                <span class="badge {{ $sale->status === 'payee' ? 'text-bg-success' : ($sale->status === 'annulee' ? 'text-bg-danger' : 'text-bg-warning') }}">
                                    {{ $sale->statusLabel() }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <hr class="my-4">

                    <h3 class="h5 mb-3">Lignes de vente</h3>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Plat</th>
                                    <th>Quantite</th>
                                    <th>Prix unitaire</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($sale->lines as $line)
                                    <tr>
                                        <td>{{ $line->menu?->nom ?? 'Plat supprime' }}</td>
                                        <td>{{ $line->quantity }}</td>
                                        <td>{{ $line->formattedUnitPrice() }}</td>
                                        <td class="fw-semibold">{{ $line->formattedTotal() }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center py-3 text-muted">Aucune ligne enregistree pour cette vente.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body">
                    <h3 class="h5 mb-3">Notes</h3>
                    <p class="mb-0 text-muted">{{ $sale->notes ?: 'Aucune note pour cette vente.' }}</p>
                </div>
            </div>

            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h3 class="h5 mb-3">Actions</h3>
                    <div class="d-grid gap-2">
                        <a href="{{ route('admin.sales.edit', $sale) }}" class="btn btn-outline-primary">Modifier la vente</a>
                        <form method="POST" action="{{ route('admin.sales.destroy', $sale) }}" onsubmit="return confirm('Supprimer cette vente ?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger w-100">Supprimer la vente</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection