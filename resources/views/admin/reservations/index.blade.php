@extends('layouts.admin')

@section('title', 'Gestion des reservations')
@section('admin_title', 'Gestion des reservations clients')
@section('admin_subtitle', 'Valide, consulte et suis les reservations passées depuis l espace public.')

@section('content')
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="d-flex justify-content-between align-items-center gap-3 flex-wrap mb-4">
        <form method="GET" action="{{ route('admin.reservations.index') }}" class="d-flex gap-2 flex-wrap">
            <input type="search" name="search" value="{{ $search }}" class="form-control" placeholder="Reservation, client, email, table, plat" style="min-width: 320px;">
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
                        <th>Table</th>
                        <th>Date</th>
                        <th>Plats</th>
                        <th>Personnes</th>
                        <th>Statut</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($reservations as $reservation)
                        <tr>
                            <td class="fw-semibold">{{ $reservation->id }}</td>
                            <td>
                                <div>{{ $reservation->user->name }}</div>
                                <div class="text-muted small">{{ $reservation->user->email }}</div>
                            </td>
                            <td>Table {{ $reservation->table->numero }}</td>
                            <td>{{ $reservation->date_reservation->format('d/m/Y H:i') }}</td>
                            <td>{{ $reservation->menusSummary() }}</td>
                            <td>{{ $reservation->nombre_personnes }}</td>
                            <td><span class="badge text-bg-dark">{{ $reservation->statusLabel() }}</span></td>
                            <td class="text-end"><a href="{{ route('admin.reservations.show', $reservation) }}" class="btn btn-outline-primary btn-sm">Ouvrir</a></td>
                        </tr>
                    @empty
                        <tr><td colspan="8" class="text-center py-4 text-muted">Aucune reservation trouvée.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if($reservations->hasPages())
        <div class="mt-4">{{ $reservations->links() }}</div>
    @endif
@endsection