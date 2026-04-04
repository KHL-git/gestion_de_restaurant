@extends('layouts.public')

@section('title', 'Mes commandes | '.config('app.name', 'Restaurant'))

@section('content')
    <section class="public-panel" style="padding: 30px; margin-bottom: 24px;">
        <div style="display:flex; justify-content:space-between; gap:16px; align-items:flex-start; flex-wrap:wrap;">
            <div>
                <span class="public-kicker">Tunnel de commande</span>
                <h2 style="font-size: clamp(2rem, 3vw, 3.2rem); margin: 16px 0 10px;">Mes commandes</h2>
                <p class="public-muted" style="margin:0; max-width:62ch; line-height:1.75;">Retrouve les commandes envoyées depuis l'espace client.</p>
            </div>
            <a href="{{ route('orders.create') }}" class="public-button">Nouvelle commande</a>
        </div>
    </section>

    @if(session('success'))
        <div class="public-card" style="margin-bottom:24px; color: var(--public-green); font-weight:700;">{{ session('success') }}</div>
    @endif

    @if($errors->get('order'))
        <div class="public-card" style="margin-bottom:24px; color:#b42318; font-weight:700;">{{ $errors->first('order') }}</div>
    @endif

    <section class="public-grid">
        @forelse($orders as $order)
            <article class="public-card">
                <div style="display:flex; justify-content:space-between; gap:16px; align-items:flex-start; flex-wrap:wrap; margin-bottom:14px;">
                    <div>
                        <h3 style="margin:0 0 6px; font-size:1.2rem;">Commande #{{ $order->id }}</h3>
                        <div class="public-muted">{{ $order->created_at->format('d/m/Y H:i') }}</div>
                    </div>
                    <span class="public-kicker">{{ $order->statusLabel() }}</span>
                </div>

                <div style="display:flex; justify-content:space-between; gap:16px; flex-wrap:wrap; margin-bottom:12px;">
                    <div>
                        <div class="public-muted">Panier</div>
                        <strong>{{ $order->itemsSummary(3) }}</strong>
                    </div>
                    <div>
                        <div class="public-muted">Articles</div>
                        <strong>{{ $order->itemsCount() }}</strong>
                    </div>
                    <div>
                        <div class="public-muted">Table</div>
                        <strong>{{ $order->table ? 'Table '.$order->table->numero : 'Non précisée' }}</strong>
                    </div>
                    <div>
                        <div class="public-muted">Total</div>
                        <strong>{{ $order->formattedTotal() }}</strong>
                    </div>
                </div>

                @if($order->notes)
                    <p class="public-muted" style="margin:0; line-height:1.7;">{{ $order->notes }}</p>
                @endif

                <div style="display:flex; gap:12px; flex-wrap:wrap; margin-top:16px;">
                    <a href="{{ route('orders.show', $order) }}" class="public-button-secondary">Voir le detail</a>
                    @if($order->statut === 'en attente')
                        <form method="POST" action="{{ route('orders.cancel', $order) }}">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="public-button-secondary" style="border-color:#b42318; color:#b42318;">Annuler</button>
                        </form>
                    @endif
                </div>
            </article>
        @empty
            <div class="public-card">
                <h3 style="margin-top:0;">Aucune commande pour le moment</h3>
                <p class="public-muted" style="margin-bottom:0;">Commence par sélectionner un plat depuis le menu public.</p>
            </div>
        @endforelse
    </section>

    @if($orders->hasPages())
        <div style="margin-top:24px;">{{ $orders->links() }}</div>
    @endif
@endsection