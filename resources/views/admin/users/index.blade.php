@extends('layouts.admin')

@section('title', 'Gestion des utilisateurs')
@section('admin_title', 'Gestion des utilisateurs')
@section('admin_subtitle', 'Ajoute, recherche et administre les comptes clients et administrateurs.')

@section('content')
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger">{{ $errors->first() }}</div>
    @endif

    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body">
            <h2 class="h4 mb-3">Créer un compte</h2>
            <form method="POST" action="{{ route('admin.users.store') }}">
                @csrf
                @include('admin.users._form', ['submitLabel' => 'Créer le compte'])
            </form>
        </div>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center gap-3 flex-wrap mb-4">
                <form method="GET" action="{{ route('admin.users.index') }}" class="d-flex gap-2 flex-wrap">
                    <input type="search" name="search" value="{{ $search }}" class="form-control" placeholder="Rechercher un nom, un email ou un rôle" style="min-width: 320px;">
                    <select name="role" class="form-select" style="min-width: 220px;">
                        <option value="">Tous les rôles</option>
                        @foreach($roles as $value => $label)
                            <option value="{{ $value }}" @selected($role === $value)>{{ $label }}</option>
                        @endforeach
                    </select>
                    <button type="submit" class="btn btn-primary">Rechercher</button>
                    @if($search || $role)
                        <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">Réinitialiser</a>
                    @endif
                </form>
                <span class="text-muted small">{{ $users->total() }} utilisateur{{ $users->total() > 1 ? 's' : '' }}</span>
            </div>

            <div class="d-flex gap-2 flex-wrap mb-4">
                <a href="{{ route('admin.users.index', array_filter(['search' => $search])) }}" class="btn {{ $role === '' ? 'btn-dark' : 'btn-outline-dark' }} btn-sm">Tous</a>
                @foreach($roles as $value => $label)
                    <a href="{{ route('admin.users.index', array_filter(['search' => $search, 'role' => $value])) }}" class="btn {{ $role === $value ? 'btn-primary' : 'btn-outline-primary' }} btn-sm">{{ $label }}</a>
                @endforeach
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 bg-white">
                    <thead class="table-light">
                        <tr>
                            <th>Nom</th>
                            <th>Email</th>
                            <th>Rôle</th>
                            <th>Créé le</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $user)
                            <tr>
                                <td>
                                    <div class="fw-semibold">{{ $user->name }}</div>
                                    @if(auth()->id() === $user->id)
                                        <span class="badge bg-secondary mt-1">Compte connecté</span>
                                    @endif
                                </td>
                                <td>{{ $user->email }}</td>
                                <td>
                                    <span class="badge {{ $user->isAdmin() ? 'text-bg-dark' : 'text-bg-light' }}">{{ $user->roleLabel() }}</span>
                                </td>
                                <td>{{ $user->created_at->format('d/m/Y H:i') }}</td>
                                <td>
                                    <div class="d-flex justify-content-end gap-2 flex-wrap">
                                        <a href="{{ route('admin.users.show', $user) }}" class="btn btn-outline-dark btn-sm">Afficher</a>
                                        <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-outline-primary btn-sm">Modifier</a>
                                        <form method="POST" action="{{ route('admin.users.destroy', $user) }}" onsubmit="return confirm('Supprimer cet utilisateur ?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger btn-sm" @disabled(auth()->id() === $user->id)>Supprimer</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-4 text-muted">Aucun utilisateur trouvé.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($users->hasPages())
                <div class="mt-4">{{ $users->links() }}</div>
            @endif
        </div>
    </div>
@endsection