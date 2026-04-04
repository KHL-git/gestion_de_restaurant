@extends('layouts.public')

@section('title', 'Dashboard | '.config('app.name', 'Restaurant'))

@section('content')
    <section class="public-panel" style="padding: 34px; margin-bottom: 24px;">
        <div style="display:flex; justify-content:space-between; gap: 20px; align-items:flex-start; flex-wrap:wrap;">
            <div>
                <span class="public-kicker">Espace client</span>
                <h2 style="font-size: clamp(2rem, 3vw, 3.4rem); margin: 16px 0 10px;">Bonjour {{ auth()->user()->name }}, voici ton dashboard.</h2>
                <p class="public-muted" style="margin: 0; max-width: 62ch; line-height: 1.75;">
                    Retrouve ici les accès réellement branchés dans la partie publique: ton profil, le menu public et les informations liées à ton compte.
                </p>
            </div>
            <div class="public-card" style="min-width: 240px; padding: 18px 20px;">
                <div class="public-muted" style="font-size: 0.82rem;">Compte connecté</div>
                <div style="font-size: 1.25rem; font-weight: 800; margin-top: 6px;">{{ auth()->user()->email }}</div>
                <div style="margin-top: 12px;">
                    <span class="public-kicker">{{ auth()->user()->isAdmin() ? 'Administrateur' : 'Client' }}</span>
                </div>
            </div>
        </div>
    </section>

    <section class="public-grid" style="grid-template-columns: 1.2fr 0.8fr; margin-bottom: 24px;">
        <article class="public-card">
            <h3 style="margin: 0 0 18px; font-size: 1.3rem;">Accès rapides</h3>
            <div class="public-grid" style="grid-template-columns: repeat(2, minmax(0, 1fr));">
                <a href="{{ route('profile.edit') }}" class="public-card" style="padding: 18px; background: #fff;">
                    <div class="public-muted" style="font-size: 0.82rem;">Mon compte</div>
                    <strong style="display:block; margin-top: 8px; font-size: 1.1rem;">Modifier mon profil</strong>
                </a>

                <a href="{{ route('menu.index') }}" class="public-card" style="padding: 18px; background: #fff;">
                    <div class="public-muted" style="font-size: 0.82rem;">Menu public</div>
                    <strong style="display:block; margin-top: 8px; font-size: 1.1rem;">Consulter les plats disponibles</strong>
                </a>

                <a href="{{ route('orders.index') }}" class="public-card" style="padding: 18px; background: #fff;">
                    <div class="public-muted" style="font-size: 0.82rem;">Mes commandes</div>
                    <strong style="display:block; margin-top: 8px; font-size: 1.1rem;">{{ $userOrdersCount }} commande{{ $userOrdersCount > 1 ? 's' : '' }}</strong>
                </a>

                <a href="{{ route('reservations.index') }}" class="public-card" style="padding: 18px; background: #fff;">
                    <div class="public-muted" style="font-size: 0.82rem;">Mes réservations</div>
                    <strong style="display:block; margin-top: 8px; font-size: 1.1rem;">{{ $userReservationsCount }} réservation{{ $userReservationsCount > 1 ? 's' : '' }}</strong>
                </a>

                @if(auth()->user()->isAdmin())
                    <a href="{{ route('admin.dashboard') }}" class="public-card" style="padding: 18px; background: linear-gradient(145deg, #22342f 0%, #2f6b52 100%); color: #fff;">
                        <div style="font-size: 0.82rem; opacity: 0.82;">Administration</div>
                        <strong style="display:block; margin-top: 8px; font-size: 1.1rem;">Ouvrir le back-office</strong>
                    </a>
                @else
                    <div class="public-card" style="padding: 18px; background: #fff;">
                        <div class="public-muted" style="font-size: 0.82rem;">Rôle</div>
                        <strong style="display:block; margin-top: 8px; font-size: 1.1rem;">Espace client actif</strong>
                    </div>
                @endif
            </div>
        </article>

        <aside class="public-card" style="background: linear-gradient(180deg, #fffaf3 0%, #f0e4d1 100%);">
            <h3 style="margin: 0 0 18px; font-size: 1.3rem;">Vue rapide</h3>
            <div class="public-grid">
                <div class="public-card" style="padding: 16px; background: rgba(255,255,255,0.82);">
                    <div class="public-muted" style="font-size: 0.82rem;">Plats disponibles</div>
                    <strong style="display:block; margin-top: 6px;">{{ $availableMenuCount }}</strong>
                </div>
                <div class="public-card" style="padding: 16px; background: rgba(255,255,255,0.82);">
                    <div class="public-muted" style="font-size: 0.82rem;">Catégories visibles</div>
                    <strong style="display:block; margin-top: 6px;">{{ $availableCategoryCount }}</strong>
                </div>
                <div class="public-card" style="padding: 16px; background: #1f2a2b; color: #fff;">
                    <div style="font-size: 0.82rem; opacity: 0.76;">Tables disponibles</div>
                    <strong style="display:block; margin-top: 6px;">{{ $availableTablesCount }}</strong>
                </div>
            </div>
        </aside>
    </section>

    @if($selectedMenu)
        <section class="public-card" style="margin-bottom: 24px; background: linear-gradient(145deg, #fffaf2 0%, #f2e3cf 100%);">
            <div style="display:flex; justify-content:space-between; gap:16px; align-items:flex-start; flex-wrap:wrap;">
                <div>
                    <span class="public-kicker" style="background: rgba(192, 99, 43, 0.12); color: var(--public-accent-strong);">Plat sélectionné</span>
                    <h3 style="margin: 14px 0 8px; font-size: 1.5rem;">{{ $selectedMenu->nom }}</h3>
                    <p class="public-muted" style="margin:0; max-width: 64ch; line-height:1.7;">
                        {{ $selectedMenu->description ?: 'Ce plat a été sélectionné depuis la carte publique.' }}
                    </p>
                </div>
                <div style="display:grid; gap:10px; justify-items:end;">
                    <strong style="font-size: 1.2rem;">{{ $selectedMenu->formattedPrice() }}</strong>
                    <div style="display:flex; gap:10px; flex-wrap:wrap; justify-content:flex-end;">
                        <a href="{{ route('orders.create', ['menu' => $selectedMenu->id]) }}" class="public-button">Commander ce plat</a>
                        <a href="{{ route('reservations.create', ['menu_id' => $selectedMenu->id]) }}" class="public-button-secondary">Réserver une table</a>
                    </div>
                </div>
            </div>
        </section>
    @endif

    <section class="public-card">
        <div style="display:flex; justify-content:space-between; gap:16px; align-items:center; flex-wrap:wrap; margin-bottom:18px;">
            <div>
                <h3 style="margin: 0 0 6px; font-size: 1.3rem;">Derniers plats disponibles</h3>
                <p class="public-muted" style="margin:0;">Ce bloc vient directement des plats disponibles du menu public.</p>
            </div>
            <a href="{{ route('menu.index') }}" class="public-button-secondary">Voir toute la carte</a>
        </div>

        <div class="public-grid" style="grid-template-columns: repeat(3, minmax(0, 1fr));">
            @forelse($recentMenus as $menu)
                <article class="public-card" style="padding: 18px; background: rgba(255,255,255,0.82);">
                    <div class="public-muted" style="font-size: 0.82rem; margin-bottom: 8px;">{{ $menu->categorie }}</div>
                    <strong style="display:block; font-size: 1.08rem; margin-bottom: 8px;">{{ $menu->nom }}</strong>
                    <p class="public-muted" style="margin:0 0 12px; line-height:1.6;">{{ \Illuminate\Support\Str::limit($menu->description ?: 'Description à venir.', 90) }}</p>
                    <strong>{{ $menu->formattedPrice() }}</strong>
                </article>
            @empty
                <div class="public-card" style="padding: 18px; background: rgba(255,255,255,0.82); grid-column: 1 / -1;">
                    <strong>Aucun plat disponible pour le moment.</strong>
                </div>
            @endforelse
        </div>
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
