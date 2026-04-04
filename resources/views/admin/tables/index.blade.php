@extends('layouts.admin')

@section('title', 'Gestion des tables')
@section('admin_title', 'Gestion des tables')
@section('admin_subtitle', 'Ajoute, active et organise les tables utilisées pour les réservations publiques.')

@section('content')
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if($errors->get('table'))
        <div class="alert alert-danger">{{ $errors->first('table') }}</div>
    @endif

    <div class="d-flex justify-content-between align-items-center gap-3 flex-wrap mb-4">
        <form method="GET" action="{{ route('admin.tables.index') }}" class="d-flex gap-2 flex-wrap">
            <input type="search" name="search" value="{{ $search }}" class="form-control" placeholder="Numero ou capacite" style="min-width: 280px;">
            <button type="submit" class="btn btn-outline-secondary">Rechercher</button>
        </form>

        <a href="{{ route('admin.tables.create') }}" class="btn btn-primary">Nouvelle table</a>
    </div>

    <div class="card shadow-sm border-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Numero</th>
                        <th>Capacite</th>
                        <th>Disponibilite</th>
                        <th>Reservations</th>
                        <th>Commandes</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($tables as $table)
                        <tr>
                            <td class="fw-semibold">{{ $table->numero }}</td>
                            <td>{{ $table->places }} places</td>
                            <td>
                                <span class="badge {{ $table->disponible ? 'text-bg-success' : 'text-bg-secondary' }}">
                                    {{ $table->disponible ? 'Disponible' : 'Inactive' }}
                                </span>
                            </td>
                            <td>{{ $table->reservations_count }}</td>
                            <td>{{ $table->orders_count }}</td>
                            <td class="text-end">
                                <div class="d-inline-flex gap-2">
                                    <a href="{{ route('admin.tables.edit', $table) }}" class="btn btn-outline-primary btn-sm">Modifier</a>
                                    <form method="POST" action="{{ route('admin.tables.destroy', $table) }}" onsubmit="return confirm('Supprimer cette table ?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger btn-sm">Supprimer</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-4 text-muted">Aucune table configurée pour le moment.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if($tables->hasPages())
        <div class="mt-4">{{ $tables->links() }}</div>
    @endif
@endsection