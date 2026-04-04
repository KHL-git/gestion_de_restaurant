@extends('layouts.public')

@section('title', 'Accueil | '.config('app.name', 'Restaurant'))

@section('content')
    <section class="public-panel" style="padding: 36px; margin-bottom: 24px;">
        <div style="display:grid; grid-template-columns: 1.15fr 0.85fr; gap: 28px; align-items:center;">
            <div>
                <span class="public-kicker">Cuisine vivante • carte connectée</span>
                <h2 style="font-size: clamp(2.4rem, 4vw, 4.8rem); line-height: 1.02; margin: 18px 0 14px; max-width: 11ch;">Une page publique reliée à vos contrôleurs.</h2>
                <p class="public-muted" style="font-size: 1.05rem; line-height: 1.8; max-width: 58ch; margin: 0 0 28px;">
                    La partie publique affiche maintenant les vraies données disponibles dans l'application: le menu public, le profil utilisateur et l'accès au dashboard connecté.
                </p>
                <div style="display:flex; gap: 14px; flex-wrap: wrap;">
                    <a href="{{ route('menu.index') }}" class="public-button">Voir le menu</a>
                    @auth
                        <a href="{{ route('dashboard') }}" class="public-button-secondary">Accéder à mon dashboard</a>
                        <a href="{{ route('reservations.create') }}" class="public-button-secondary">Réserver une table</a>
                    @else
                        <a href="{{ route('login') }}" class="public-button-secondary">Se connecter</a>
                    @endauth
                </div>
            </div>

            <div class="public-card" style="background: linear-gradient(160deg, #fffdf8 0%, #efe1ca 100%); min-height: 360px; position: relative; overflow: hidden;">
                <div style="position:absolute; inset:auto -40px -80px auto; width: 220px; height: 220px; border-radius:50%; background: rgba(192, 99, 43, 0.12);"></div>
                <div style="position:relative; z-index:1; display:grid; gap: 16px;">
                    <div style="display:flex; justify-content:space-between; align-items:center; gap: 12px;">
                        <strong>Vue d'ensemble</strong>
                        <span class="public-kicker">En ligne</span>
                    </div>

                    <div class="public-card" style="padding: 18px; background: rgba(255,255,255,0.82);">
                        <div class="public-muted" style="font-size: 0.85rem; margin-bottom: 8px;">Plats disponibles</div>
                        <div style="font-size: 2rem; font-weight: 800;">{{ $availableMenuCount }}</div>
                    </div>

                    <div class="public-card" style="padding: 18px; background: #1f2a2b; color: #fff;">
                        <div style="font-size: 0.85rem; opacity: 0.75;">Catégories visibles</div>
                        <div style="font-size: 1.5rem; font-weight: 700; margin-top: 6px;">{{ $availableCategoryCount }}</div>
                    </div>

                    <div style="display:grid; grid-template-columns: repeat(2, 1fr); gap: 12px;">
                        <div class="public-card" style="padding: 16px;">
                            <div class="public-muted" style="font-size: 0.82rem;">Navigation</div>
                            <strong>Accueil, menu, dashboard</strong>
                        </div>
                        <div class="public-card" style="padding: 16px;">
                            <div class="public-muted" style="font-size: 0.82rem;">Compte</div>
                            <strong>Profil réellement branché</strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="public-grid" style="grid-template-columns: 1fr 1fr; margin-bottom: 24px;">
        <article class="public-card">
            <h3 style="margin: 0 0 14px; font-size: 1.2rem;">Modules publics actuellement actifs</h3>
            <div class="public-grid">
                <div class="public-card" style="padding: 16px; background: rgba(255,255,255,0.76);">
                    <div class="public-muted" style="font-size: 0.82rem;">Menu public</div>
                    <strong>Consultation des plats disponibles</strong>
                </div>
                <div class="public-card" style="padding: 16px; background: rgba(255,255,255,0.76);">
                    <div class="public-muted" style="font-size: 0.82rem;">Espace utilisateur</div>
                    <strong>Connexion, dashboard et profil</strong>
                </div>
            </div>
        </article>

        <article class="public-card">
            <h3 style="margin: 0 0 14px; font-size: 1.2rem;">Aperçu du menu</h3>
            <div class="public-grid">
                @forelse($featuredMenus as $menu)
                    <div class="public-card" style="padding: 16px; background: rgba(255,255,255,0.76); display:flex; justify-content:space-between; gap:14px; align-items:flex-start;">
                        <div>
                            <div class="public-muted" style="font-size: 0.82rem;">{{ $menu->categorie }}</div>
                            <strong>{{ $menu->nom }}</strong>
                        </div>
                        <strong style="white-space: nowrap;">{{ $menu->formattedPrice() }}</strong>
                    </div>
                @empty
                    <div class="public-card" style="padding: 16px; background: rgba(255,255,255,0.76);">
                        <strong>Aucun plat disponible pour le moment.</strong>
                    </div>
                @endforelse
            </div>
        </article>
    </section>

    <style>
        @media (max-width: 960px) {
            .public-panel > div,
            .public-grid {
                grid-template-columns: 1fr !important;
            }
        }
    </style>
@endsection
