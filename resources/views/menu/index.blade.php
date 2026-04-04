@extends('layouts.public')

@section('title', 'Notre menu | '.config('app.name', 'Restaurant'))

@push('styles')
    <style>
        .menu-hero {
            padding: 34px;
            margin-bottom: 24px;
        }

        .menu-search-form {
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
            align-items: center;
        }

        .menu-search-input {
            min-width: 280px;
            border-radius: 999px;
            padding: 13px 18px;
            border: 1px solid rgba(101, 83, 59, 0.16);
            background: rgba(255, 255, 255, 0.88);
            color: var(--public-text);
            font: inherit;
        }

        .menu-search-input:focus {
            outline: none;
            border-color: rgba(192, 99, 43, 0.42);
            box-shadow: 0 0 0 4px rgba(192, 99, 43, 0.1);
        }

        .menu-section {
            margin-bottom: 24px;
        }

        .menu-filter-row {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            margin-top: 18px;
        }

        .menu-filter-chip {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 14px;
            border-radius: 999px;
            border: 1px solid rgba(101, 83, 59, 0.16);
            background: rgba(255, 255, 255, 0.78);
            color: var(--public-text);
            font-weight: 700;
            font-size: 0.9rem;
            transition: 0.2s ease;
        }

        .menu-filter-chip:hover,
        .menu-filter-chip.active {
            background: #1f2a2b;
            color: #fff;
            border-color: #1f2a2b;
        }

        .menu-section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 12px;
            margin-bottom: 18px;
        }

        .menu-section-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 18px;
        }

        .menu-item-card {
            display: grid;
            grid-template-columns: 200px minmax(0, 1fr);
            gap: 18px;
            padding: 18px;
            background: rgba(255, 255, 255, 0.8);
        }

        .menu-item-media {
            position: relative;
            overflow: hidden;
            border-radius: 20px;
            background: linear-gradient(145deg, #efe2cf 0%, #f8f2e9 100%);
            min-height: 180px;
        }

        .menu-item-media img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }

        .menu-item-placeholder {
            width: 100%;
            height: 100%;
            min-height: 180px;
            display: grid;
            place-items: center;
            color: var(--public-muted);
            font-weight: 700;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            background:
                radial-gradient(circle at top left, rgba(192, 99, 43, 0.14), transparent 35%),
                linear-gradient(160deg, #fcf7ef 0%, #eedeca 100%);
        }

        .menu-item-body {
            display: flex;
            flex-direction: column;
            gap: 14px;
            min-width: 0;
        }

        .menu-item-top {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 16px;
        }

        .menu-item-title {
            margin: 0;
            font-size: 1.2rem;
            line-height: 1.25;
        }

        .menu-item-price {
            white-space: nowrap;
            padding: 10px 14px;
            border-radius: 999px;
            background: #1f2a2b;
            color: #fff;
            font-size: 0.96rem;
            font-weight: 800;
        }

        .menu-item-description {
            margin: 0;
            color: var(--public-muted);
            line-height: 1.7;
        }

        .menu-item-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 12px;
            margin-top: auto;
            flex-wrap: wrap;
        }

        .menu-item-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 9px 12px;
            border-radius: 999px;
            background: rgba(47, 107, 82, 0.1);
            color: var(--public-green);
            font-size: 0.85rem;
            font-weight: 700;
        }

        .menu-item-note {
            color: var(--public-muted);
            font-size: 0.9rem;
        }

        .menu-item-actions {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }

        .menu-item-action-primary,
        .menu-item-action-secondary {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 11px 16px;
            border-radius: 999px;
            font-weight: 800;
            font-size: 0.92rem;
            transition: 0.2s ease;
            border: 1px solid transparent;
        }

        .menu-item-action-primary {
            background: linear-gradient(135deg, var(--public-accent) 0%, #d8843b 100%);
            color: #fff;
            box-shadow: 0 16px 28px rgba(192, 99, 43, 0.18);
        }

        .menu-item-action-primary:hover {
            background: linear-gradient(135deg, var(--public-accent-strong) 0%, var(--public-accent) 100%);
        }

        .menu-item-action-secondary {
            background: transparent;
            border-color: rgba(101, 83, 59, 0.16);
            color: var(--public-text);
        }

        .menu-item-action-secondary:hover {
            background: rgba(255, 255, 255, 0.82);
        }

        @media (max-width: 1100px) {
            .menu-section-grid {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 720px) {
            .menu-hero {
                padding: 24px;
            }

            .menu-search-form {
                width: 100%;
            }

            .menu-search-input {
                min-width: 0;
                width: 100%;
            }

            .menu-item-card {
                grid-template-columns: 1fr;
            }

            .menu-item-media,
            .menu-item-placeholder {
                min-height: 220px;
            }

            .menu-item-top {
                flex-direction: column;
                align-items: flex-start;
            }
        }
    </style>
@endpush

@section('content')
    <section class="public-panel menu-hero">
        <div style="display:flex; justify-content:space-between; gap: 20px; align-items:flex-start; flex-wrap:wrap;">
            <div>
                <span class="public-kicker">Carte du restaurant</span>
                <h2 style="font-size: clamp(2rem, 3vw, 3.6rem); margin: 16px 0 10px;">Découvre nos plats.</h2>
                <p class="public-muted" style="margin: 0; max-width: 64ch; line-height: 1.75;">
                    Explore les plats actuellement disponibles, classés par catégorie, avec une recherche simple par nom, type ou description.
                </p>
            </div>

            <form method="GET" action="{{ route('menu.index') }}" class="menu-search-form">
                <input
                    type="search"
                    name="search"
                    value="{{ $search }}"
                    class="menu-search-input"
                    placeholder="Rechercher un plat"
                >
                @if($category)
                    <input type="hidden" name="category" value="{{ $category }}">
                @endif
                <button type="submit" class="public-button">Chercher</button>
            </form>
        </div>

        <div class="menu-filter-row">
            <a href="{{ route('menu.index', array_filter(['search' => $search])) }}" class="menu-filter-chip {{ $category === '' ? 'active' : '' }}">Toutes les catégories</a>
            @foreach($categories as $categoryItem)
                <a href="{{ route('menu.index', array_filter(['search' => $search, 'category' => $categoryItem->categorie])) }}" class="menu-filter-chip {{ $category === $categoryItem->categorie ? 'active' : '' }}">
                    {{ $categoryItem->categorie }}
                    <span>{{ $categoryItem->total }}</span>
                </a>
            @endforeach
        </div>
    </section>

    <section>
        @forelse($menus as $categorie => $plats)
            <div class="public-card menu-section">
                <div class="menu-section-header">
                    <h3 style="margin: 0; font-size: 1.35rem;">{{ $categorie }}</h3>
                    <span class="public-kicker">{{ $plats->count() }} plat{{ $plats->count() > 1 ? 's' : '' }}</span>
                </div>

                <div class="menu-section-grid">
                    @foreach($plats as $plat)
                        <article class="public-card menu-item-card">
                            <div class="menu-item-media">
                                @if($plat->imageUrl())
                                    <img src="{{ $plat->imageUrl() }}" alt="{{ $plat->nom }}">
                                @else
                                    <div class="menu-item-placeholder">Plat maison</div>
                                @endif
                            </div>

                            <div class="menu-item-body">
                                <div class="menu-item-top">
                                    <div style="min-width: 0;">
                                        <h4 class="menu-item-title">{{ $plat->nom }}</h4>
                                        <p class="menu-item-description">{{ $plat->description ?: 'Le chef propose une assiette simple et soignée, servie selon la disponibilité du jour.' }}</p>
                                    </div>
                                    <span class="menu-item-price">{{ $plat->formattedPrice() }}</span>
                                </div>

                                <div class="menu-item-footer">
                                    <span class="menu-item-badge">Disponible maintenant</span>
                                    <div class="menu-item-actions">
                                        @auth
                                            <a href="{{ route('orders.create', ['menu' => $plat->id]) }}" class="menu-item-action-primary">Commander</a>
                                            <a href="{{ route('reservations.create', ['menu_id' => $plat->id]) }}" class="menu-item-action-secondary">Réserver</a>
                                        @else
                                            <a href="{{ route('login') }}" class="menu-item-action-primary">Commander</a>
                                            <a href="{{ route('login') }}" class="menu-item-action-secondary">Réserver</a>
                                        @endauth
                                        <a href="{{ route('menu.index', ['category' => $plat->categorie]) }}" class="menu-item-action-secondary">Voir la catégorie</a>
                                    </div>
                                </div>
                            </div>
                        </article>
                    @endforeach
                </div>
            </div>
        @empty
            <div class="public-card">
                <h3 style="margin-top: 0;">Aucun plat disponible</h3>
                <p class="public-muted" style="margin-bottom: 0;">Aucun résultat ne correspond à ta recherche pour le moment.</p>
            </div>
        @endforelse
    </section>
@endsection