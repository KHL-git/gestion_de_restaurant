@extends('layouts.admin')

@section('title', 'Gestion du menu')
@section('admin_title', 'Gestion du menu')
@section('admin_subtitle', 'Ajoute, modifie, supprime et recherche les plats visibles dans ton restaurant.')

@section('content')
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="d-flex justify-content-between align-items-center gap-3 flex-wrap mb-4">
        <form method="GET" action="{{ route('admin.menus.index') }}" class="d-flex gap-2 flex-wrap">
            <input
                type="search"
                name="search"
                value="{{ $search }}"
                class="form-control"
                placeholder="Rechercher un plat, une catégorie, une description"
                style="min-width: 320px;"
            >
            <button type="submit" class="btn btn-primary">Rechercher</button>
            @if($search)
                <a href="{{ route('admin.menus.index') }}" class="btn btn-outline-secondary">Réinitialiser</a>
            @endif
        </form>

        <a href="{{ route('admin.menus.create') }}" class="btn btn-success">Ajouter un plat</a>
    </div>

    <div class="card shadow-sm border-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Image</th>
                        <th>Plat</th>
                        <th>Catégorie</th>
                        <th>Prix</th>
                        <th>Statut</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($menus as $menu)
                        <tr>
                            <td>
                                @if($menu->imageUrl())
                                    <img src="{{ $menu->imageUrl() }}" alt="{{ $menu->nom }}" style="width: 64px; height: 64px; object-fit: cover; border-radius: 14px;">
                                @else
                                    <div class="d-grid place-items-center text-muted border rounded-4" style="width: 64px; height: 64px;">-</div>
                                @endif
                            </td>
                            <td>
                                <div class="fw-semibold">{{ $menu->nom }}</div>
                                <div class="text-muted small">{{ \Illuminate\Support\Str::limit($menu->description, 90) ?: 'Aucune description' }}</div>
                            </td>
                            <td>{{ $menu->categorie ?: 'Non classé' }}</td>
                            <td>{{ $menu->formattedPrice() }}</td>
                            <td>
                                @if($menu->disponible)
                                    <span class="badge text-bg-success">Disponible</span>
                                @else
                                    <span class="badge text-bg-secondary">Indisponible</span>
                                @endif
                            </td>
                            <td>
                                <div class="d-flex justify-content-end gap-2 flex-wrap">
                                    <a href="{{ route('admin.menus.show', $menu) }}" class="btn btn-outline-dark btn-sm">Afficher</a>
                                    <a href="{{ route('admin.menus.edit', $menu) }}" class="btn btn-outline-primary btn-sm">Modifier</a>
                                    <form method="POST" action="{{ route('admin.menus.destroy', $menu) }}" onsubmit="return confirm('Supprimer ce plat ?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger btn-sm">Supprimer</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-4 text-muted">Aucun plat trouvé.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if($menus->hasPages())
        <div class="mt-4">
            {{ $menus->links() }}
        </div>
    @endif
@endsection