@extends('layouts.admin')

@section('title', 'Afficher un plat')
@section('admin_title', $menu->nom)
@section('admin_subtitle', 'Consulte les détails du plat avant modification ou suppression.')

@section('content')
    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body p-4">
                    @if($menu->imageUrl())
                        <div class="mb-4">
                            <img src="{{ $menu->imageUrl() }}" alt="{{ $menu->nom }}" class="img-fluid rounded-4" style="max-height: 320px; width: 100%; object-fit: cover;">
                        </div>
                    @endif

                    <div class="d-flex justify-content-between align-items-start gap-3 flex-wrap mb-3">
                        <div>
                            <h2 class="h3 mb-1">{{ $menu->nom }}</h2>
                            <div class="text-muted">{{ $menu->categorie ?: 'Non classé' }}</div>
                        </div>
                        @if($menu->disponible)
                            <span class="badge text-bg-success">Disponible</span>
                        @else
                            <span class="badge text-bg-secondary">Indisponible</span>
                        @endif
                    </div>

                    <div class="mb-4">
                        <div class="text-muted small mb-2">Description</div>
                        <p class="mb-0">{{ $menu->description ?: 'Aucune description renseignée.' }}</p>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="border rounded-4 p-3 bg-light-subtle h-100">
                                <div class="text-muted small">Prix</div>
                                <div class="fs-4 fw-bold">{{ $menu->formattedPrice() }}</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="border rounded-4 p-3 bg-light-subtle h-100">
                                <div class="text-muted small">Créé le</div>
                                <div class="fw-semibold">{{ $menu->created_at->format('d/m/Y H:i') }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body p-4 d-grid gap-2 align-content-start">
                    <a href="{{ route('admin.menus.edit', $menu) }}" class="btn btn-primary">Modifier le plat</a>
                    <a href="{{ route('admin.menus.index') }}" class="btn btn-outline-secondary">Retour à la liste</a>

                    <form method="POST" action="{{ route('admin.menus.destroy', $menu) }}" onsubmit="return confirm('Supprimer définitivement ce plat ?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-outline-danger w-100">Supprimer</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection