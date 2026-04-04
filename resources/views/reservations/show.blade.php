@extends('layouts.public')

@section('title', 'Reservation #'.$reservation->id.' | '.config('app.name', 'Restaurant'))

@section('content')
    <section class="public-panel" style="padding: 30px; margin-bottom: 24px;">
        <div style="display:flex; justify-content:space-between; gap:16px; align-items:flex-start; flex-wrap:wrap;">
            <div>
                <span class="public-kicker">Detail reservation</span>
                <h2 style="font-size: clamp(2rem, 3vw, 3.2rem); margin: 16px 0 10px;">Reservation #{{ $reservation->id }}</h2>
                <p class="public-muted" style="margin:0; line-height:1.75;">Suivi détaillé de votre réservation client.</p>
            </div>
            <div style="display:flex; gap:12px; flex-wrap:wrap;">
                <a href="{{ route('reservations.index') }}" class="public-button-secondary">Retour</a>
                @if($reservation->statut === 'en attente')
                    <form method="POST" action="{{ route('reservations.cancel', $reservation) }}">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="public-button-secondary" style="border-color:#b42318; color:#b42318;">Annuler</button>
                    </form>
                @endif
            </div>
        </div>
    </section>

    @if(session('success'))
        <div class="public-card" style="margin-bottom:24px; color: var(--public-green); font-weight:700;">{{ session('success') }}</div>
    @endif

    <section class="public-card">
        <div style="display:flex; justify-content:space-between; gap:16px; flex-wrap:wrap; margin-bottom:16px;">
            <div><div class="public-muted">Statut</div><strong>{{ $reservation->statusLabel() }}</strong></div>
            <div><div class="public-muted">Table</div><strong>Table {{ $reservation->table->numero }}</strong></div>
            <div><div class="public-muted">Date</div><strong>{{ $reservation->date_reservation->format('d/m/Y H:i') }}</strong></div>
            <div><div class="public-muted">Personnes</div><strong>{{ $reservation->nombre_personnes }}</strong></div>
        </div>

        <div class="public-grid" style="grid-template-columns: repeat(2, minmax(0, 1fr)); margin-bottom:16px;">
            @foreach($reservation->menus as $menu)
                <div class="public-card" style="padding:16px; background: rgba(255,255,255,0.78);">
                    <div class="public-muted" style="font-size:0.82rem;">{{ $menu->categorie }}</div>
                    <strong>{{ $menu->nom }}</strong>
                    <div style="margin-top:8px; font-weight:800;">{{ $menu->formattedPrice() }}</div>
                </div>
            @endforeach
        </div>

        @if($reservation->notes)
            <p class="public-muted" style="margin:0; line-height:1.7;">{{ $reservation->notes }}</p>
        @endif
    </section>
@endsection