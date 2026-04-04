@extends('layouts.public')

@section('title', 'Mes réservations | '.config('app.name', 'Restaurant'))

@section('content')
    <section class="public-panel" style="padding: 30px; margin-bottom: 24px;">
        <div style="display:flex; justify-content:space-between; gap:16px; align-items:flex-start; flex-wrap:wrap;">
            <div>
                <span class="public-kicker">Réservations</span>
                <h2 style="font-size: clamp(2rem, 3vw, 3.2rem); margin: 16px 0 10px;">Mes réservations</h2>
                <p class="public-muted" style="margin:0; max-width:62ch; line-height:1.75;">Retrouve les réservations envoyées depuis ton espace public.</p>
            </div>
            <a href="{{ route('reservations.create') }}" class="public-button">Nouvelle réservation</a>
        </div>
    </section>

    @if(session('success'))
        <div class="public-card" style="margin-bottom:24px; color: var(--public-green); font-weight:700;">{{ session('success') }}</div>
    @endif

    @if($errors->get('reservation'))
        <div class="public-card" style="margin-bottom:24px; color:#b42318; font-weight:700;">{{ $errors->first('reservation') }}</div>
    @endif

    <section class="public-grid">
        @forelse($reservations as $reservation)
            <article class="public-card">
                <div style="display:flex; justify-content:space-between; gap:16px; align-items:flex-start; flex-wrap:wrap; margin-bottom:14px;">
                    <div>
                        <h3 style="margin:0 0 6px; font-size:1.2rem;">Réservation #{{ $reservation->id }}</h3>
                        <div class="public-muted">{{ $reservation->date_reservation->format('d/m/Y H:i') }}</div>
                    </div>
                    <span class="public-kicker">{{ $reservation->statusLabel() }}</span>
                </div>

                <div style="display:flex; justify-content:space-between; gap:16px; flex-wrap:wrap; margin-bottom:12px;">
                    <div>
                        <div class="public-muted">Table</div>
                        <strong>Table {{ $reservation->table->numero }}</strong>
                    </div>
                    <div>
                        <div class="public-muted">Capacité</div>
                        <strong>{{ $reservation->table->places }} places</strong>
                    </div>
                    <div>
                        <div class="public-muted">Personnes</div>
                        <strong>{{ $reservation->nombre_personnes }}</strong>
                    </div>
                    <div>
                        <div class="public-muted">Plats</div>
                        <strong>{{ $reservation->menusSummary() }}</strong>
                    </div>
                </div>

                @if($reservation->notes)
                    <p class="public-muted" style="margin:0; line-height:1.7;">{{ $reservation->notes }}</p>
                @endif

                <div style="display:flex; gap:12px; flex-wrap:wrap; margin-top:16px;">
                    <a href="{{ route('reservations.show', $reservation) }}" class="public-button-secondary">Voir le detail</a>
                    @if($reservation->statut === 'en attente')
                        <form method="POST" action="{{ route('reservations.cancel', $reservation) }}">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="public-button-secondary" style="border-color:#b42318; color:#b42318;">Annuler</button>
                        </form>
                    @endif
                </div>
            </article>
        @empty
            <div class="public-card">
                <h3 style="margin-top:0;">Aucune réservation pour le moment</h3>
                <p class="public-muted" style="margin-bottom:0;">Réserve une table depuis l'espace public pour lancer le module.</p>
            </div>
        @endforelse
    </section>

    @if($reservations->hasPages())
        <div style="margin-top:24px;">{{ $reservations->links() }}</div>
    @endif
@endsection