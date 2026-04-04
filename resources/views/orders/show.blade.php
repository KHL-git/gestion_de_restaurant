@extends('layouts.public')

@section('title', 'Commande #'.$order->id.' | '.config('app.name', 'Restaurant'))

@section('content')
    <section class="public-panel" style="padding: 30px; margin-bottom: 24px;">
        <div style="display:flex; justify-content:space-between; gap:16px; align-items:flex-start; flex-wrap:wrap;">
            <div>
                <span class="public-kicker">Detail commande</span>
                <h2 style="font-size: clamp(2rem, 3vw, 3.2rem); margin: 16px 0 10px;">Commande #{{ $order->id }}</h2>
                <p class="public-muted" style="margin:0; line-height:1.75;">Suivi détaillé de votre commande client.</p>
            </div>
            <div style="display:flex; gap:12px; flex-wrap:wrap;">
                <a href="{{ route('orders.index') }}" class="public-button-secondary">Retour</a>
                @if($order->statut === 'en attente')
                    <form method="POST" action="{{ route('orders.cancel', $order) }}">
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

    <section class="public-card" style="margin-bottom:24px;">
        <div style="display:flex; justify-content:space-between; gap:16px; flex-wrap:wrap; margin-bottom:16px;">
            <div><div class="public-muted">Statut</div><strong>{{ $order->statusLabel() }}</strong></div>
            <div><div class="public-muted">Table</div><strong>{{ $order->table ? 'Table '.$order->table->numero : 'Non précisée' }}</strong></div>
            <div><div class="public-muted">Total</div><strong>{{ $order->formattedTotal() }}</strong></div>
        </div>

        <div class="public-grid">
            @foreach($order->items as $item)
                <div class="public-card" style="padding:16px; background: rgba(255,255,255,0.78);">
                    <div class="public-muted" style="font-size:0.82rem;">{{ $item->menu?->categorie }}</div>
                    <strong>{{ $item->menu?->nom ?? 'Plat supprimé' }}</strong>
                    <div class="public-muted" style="margin-top:8px;">{{ $item->quantity }} x {{ $item->formattedUnitPrice() }}</div>
                    <div style="margin-top:8px; font-weight:800;">{{ $item->formattedTotal() }}</div>
                </div>
            @endforeach
        </div>

        @if($order->notes)
            <p class="public-muted" style="margin:16px 0 0; line-height:1.7;">{{ $order->notes }}</p>
        @endif
    </section>
@endsection