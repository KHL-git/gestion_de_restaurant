@extends('layouts.admin')

@section('title', 'Fiche reservation')
@section('admin_title', 'Fiche reservation client')
@section('admin_subtitle', 'Consulte le detail d une reservation et mets a jour son statut.')

@section('content')
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="d-flex justify-content-between align-items-center gap-3 flex-wrap mb-4">
        <div>
            <h2 class="h3 mb-1">Reservation #{{ $reservation->id }}</h2>
            <p class="text-muted mb-0">{{ $reservation->user->name }} - {{ $reservation->user->email }}</p>
        </div>
        <a href="{{ route('admin.reservations.index') }}" class="btn btn-outline-secondary">Retour</a>
    </div>

    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body">
            <form method="POST" action="{{ route('admin.reservations.update-status', $reservation) }}" class="d-flex gap-2 flex-wrap align-items-end">
                @csrf
                @method('PATCH')
                <div>
                    <label class="form-label">Statut</label>
                    <select name="statut" class="form-select">
                        @foreach($statuses as $value => $label)
                            <option value="{{ $value }}" @selected($reservation->statut === $value)>{{ $label }}</option>
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
                    <h3 class="h5 mb-3">Informations reservation</h3>
                    <p class="mb-2"><strong>Table:</strong> {{ $reservation->table->numero }}</p>
                    <p class="mb-2"><strong>Capacite:</strong> {{ $reservation->table->places }} places</p>
                    <p class="mb-2"><strong>Date:</strong> {{ $reservation->date_reservation->format('d/m/Y H:i') }}</p>
                    <p class="mb-2"><strong>Personnes:</strong> {{ $reservation->nombre_personnes }}</p>
                    <div class="mb-3">
                        <strong>Plats choisis:</strong>
                        <div class="mt-2 d-flex flex-wrap gap-2">
                            @foreach($reservation->menus as $menu)
                                <span class="badge text-bg-light border">{{ $menu->nom }} - {{ $menu->formattedPrice() }}</span>
                            @endforeach
                        </div>
                    </div>
                    @if($reservation->notes)
                        <p class="mb-0"><strong>Note:</strong> {{ $reservation->notes }}</p>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h3 class="h5 mb-3">Statut actuel</h3>
                    <span class="badge text-bg-dark">{{ $reservation->statusLabel() }}</span>
                </div>
            </div>
        </div>
    </div>
@endsection