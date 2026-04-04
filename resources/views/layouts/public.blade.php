<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', config('app.name', 'Restaurant'))</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --public-bg: #f4ede2;
            --public-bg-soft: #fbf7f1;
            --public-surface: rgba(255, 250, 243, 0.84);
            --public-card: rgba(255, 250, 243, 0.92);
            --public-card-strong: #fffdf8;
            --public-text: #1f1f1a;
            --public-muted: #685f53;
            --public-line: rgba(101, 83, 59, 0.16);
            --public-accent: #c0632b;
            --public-accent-strong: #8f4318;
            --public-accent-soft: rgba(192, 99, 43, 0.1);
            --public-green: #2f6b52;
            --public-green-soft: rgba(47, 107, 82, 0.1);
            --public-shadow: 0 24px 60px rgba(78, 57, 34, 0.14);
            --public-shadow-soft: 0 18px 38px rgba(90, 65, 36, 0.08);
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: 'Manrope', sans-serif;
            color: var(--public-text);
            background:
                radial-gradient(circle at 8% 0%, rgba(255, 204, 128, 0.42), transparent 26%),
                radial-gradient(circle at 100% 5%, rgba(47, 107, 82, 0.2), transparent 24%),
                radial-gradient(circle at 50% 100%, rgba(143, 67, 24, 0.08), transparent 26%),
                linear-gradient(180deg, var(--public-bg-soft) 0%, var(--public-bg) 100%);
            min-height: 100vh;
            position: relative;
        }

        a {
            color: inherit;
            text-decoration: none;
        }

        button,
        input,
        select,
        textarea {
            font: inherit;
        }

        body::before,
        body::after {
            content: '';
            position: fixed;
            inset: auto;
            pointer-events: none;
            z-index: 0;
            border-radius: 999px;
            filter: blur(18px);
            opacity: 0.55;
        }

        body::before {
            width: 280px;
            height: 280px;
            top: 90px;
            left: -90px;
            background: radial-gradient(circle, rgba(255, 214, 153, 0.55) 0%, rgba(255, 214, 153, 0) 72%);
        }

        body::after {
            width: 340px;
            height: 340px;
            right: -120px;
            bottom: 60px;
            background: radial-gradient(circle, rgba(47, 107, 82, 0.18) 0%, rgba(47, 107, 82, 0) 72%);
        }

        .public-shell {
            width: min(1180px, calc(100% - 32px));
            margin: 0 auto;
            padding: 22px 0 40px;
            position: relative;
            z-index: 1;
        }

        .public-navbar {
            position: sticky;
            top: 16px;
            z-index: 30;
            display: grid;
            grid-template-columns: auto 1fr auto;
            align-items: center;
            gap: 20px;
            padding: 18px 20px;
            border: 1px solid var(--public-line);
            border-radius: 28px;
            background:
                linear-gradient(180deg, rgba(255, 255, 255, 0.72) 0%, rgba(255, 250, 243, 0.82) 100%);
            backdrop-filter: blur(20px);
            box-shadow: var(--public-shadow);
            overflow: hidden;
        }

        .public-navbar::before {
            content: '';
            position: absolute;
            inset: 0 0 auto;
            height: 1px;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.88), transparent);
            pointer-events: none;
        }

        .public-brand {
            display: flex;
            align-items: center;
            gap: 14px;
            min-width: 0;
        }

        .public-brand-mark {
            width: 52px;
            height: 52px;
            display: grid;
            place-items: center;
            border-radius: 18px;
            background: linear-gradient(135deg, #1b3b30 0%, #2f6b52 100%);
            color: #fff;
            font-size: 1.2rem;
            font-weight: 800;
            box-shadow: 0 14px 30px rgba(47, 107, 82, 0.24);
        }

        .public-brand-copy {
            min-width: 0;
        }

        .public-brand h1 {
            margin: 0;
            font-size: 1rem;
            font-weight: 800;
            letter-spacing: 0.05em;
            text-transform: uppercase;
        }

        .public-brand p {
            margin: 2px 0 0;
            color: var(--public-muted);
            font-size: 0.85rem;
        }

        .public-navbar-menu {
            display: flex;
            justify-content: center;
            min-width: 0;
        }

        .public-nav-links {
            display: flex;
            align-items: center;
            gap: 10px;
            flex-wrap: wrap;
            justify-content: center;
        }

        .public-nav-link {
            padding: 10px 16px;
            border-radius: 999px;
            font-weight: 600;
            color: var(--public-muted);
            transition: 0.2s ease;
            position: relative;
        }

        .public-nav-link:hover,
        .public-nav-link.active {
            background: #fff;
            color: var(--public-text);
            box-shadow: 0 10px 22px rgba(60, 45, 27, 0.08);
        }

        .public-nav-link.active::after {
            content: '';
            position: absolute;
            left: 14px;
            right: 14px;
            bottom: 6px;
            height: 2px;
            border-radius: 999px;
            background: linear-gradient(90deg, var(--public-accent) 0%, var(--public-green) 100%);
        }

        .public-nav-actions {
            display: flex;
            align-items: center;
            gap: 10px;
            flex-wrap: wrap;
            justify-content: flex-end;
        }

        .public-navbar-toggle {
            display: none;
            align-items: center;
            justify-content: center;
            width: 46px;
            height: 46px;
            border: 1px solid var(--public-line);
            border-radius: 16px;
            background: rgba(255, 255, 255, 0.68);
            color: var(--public-text);
            cursor: pointer;
            box-shadow: var(--public-shadow-soft);
        }

        .public-navbar-toggle svg {
            width: 20px;
            height: 20px;
        }

        .public-user-badge {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 8px 10px 8px 8px;
            border-radius: 999px;
            background: rgba(255, 255, 255, 0.72);
            border: 1px solid var(--public-line);
            box-shadow: var(--public-shadow-soft);
        }

        .public-user-mark {
            width: 34px;
            height: 34px;
            display: grid;
            place-items: center;
            border-radius: 50%;
            background: linear-gradient(135deg, #1f2a2b 0%, #2f6b52 100%);
            color: #fff;
            font-size: 0.92rem;
            font-weight: 800;
            text-transform: uppercase;
        }

        .public-user-copy {
            display: grid;
            gap: 2px;
        }

        .public-user-copy strong {
            font-size: 0.92rem;
            line-height: 1.1;
        }

        .public-user-copy span {
            color: var(--public-muted);
            font-size: 0.76rem;
            line-height: 1.1;
        }

        .public-button,
        .public-button-secondary {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 999px;
            padding: 11px 18px;
            font-weight: 700;
            transition: 0.2s ease;
            border: 1px solid transparent;
            cursor: pointer;
            font-family: inherit;
        }

        .public-button {
            background: linear-gradient(135deg, var(--public-accent) 0%, #d8843b 100%);
            color: #fff;
            box-shadow: 0 16px 28px rgba(192, 99, 43, 0.25);
        }

        .public-button:hover {
            background: linear-gradient(135deg, var(--public-accent-strong) 0%, var(--public-accent) 100%);
        }

        .public-button-secondary {
            background: rgba(255, 255, 255, 0.65);
            border-color: var(--public-line);
            color: var(--public-text);
        }

        .public-button-secondary:hover {
            background: #fff;
        }

        .public-content {
            padding-top: 30px;
        }

        .public-panel {
            border: 1px solid var(--public-line);
            border-radius: 32px;
            background: var(--public-surface);
            box-shadow: var(--public-shadow);
            overflow: hidden;
            position: relative;
        }

        .public-panel::after {
            content: '';
            position: absolute;
            inset: auto -10% -40% auto;
            width: 180px;
            height: 180px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(192, 99, 43, 0.12) 0%, rgba(192, 99, 43, 0) 72%);
            pointer-events: none;
        }

        .public-grid {
            display: grid;
            gap: 22px;
        }

        .public-card {
            border: 1px solid var(--public-line);
            border-radius: 24px;
            background: var(--public-card);
            padding: 22px;
            box-shadow: var(--public-shadow-soft);
        }

        .public-kicker {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 8px 12px;
            border-radius: 999px;
            background: var(--public-green-soft);
            color: var(--public-green);
            font-weight: 700;
            font-size: 0.85rem;
        }

        .public-muted {
            color: var(--public-muted);
        }

        .public-section-stack {
            display: grid;
            gap: 24px;
        }

        .public-page-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 18px;
            flex-wrap: wrap;
            margin-bottom: 18px;
        }

        .public-eyebrow {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 7px 12px;
            border: 1px solid var(--public-line);
            border-radius: 999px;
            background: rgba(255,255,255,0.62);
            color: var(--public-muted);
            font-size: 0.82rem;
            font-weight: 700;
        }

        .public-footer {
            display: grid;
            grid-template-columns: 1.2fr 0.8fr;
            gap: 24px;
            margin-top: 30px;
            padding: 24px 26px;
            border: 1px solid var(--public-line);
            border-radius: 28px;
            background: rgba(255, 250, 243, 0.72);
            box-shadow: var(--public-shadow-soft);
            backdrop-filter: blur(16px);
        }

        .public-footer-copy {
            display: grid;
            gap: 10px;
        }

        .public-footer-copy h2 {
            margin: 0;
            font-size: 1.1rem;
        }

        .public-footer-copy p {
            margin: 0;
            color: var(--public-muted);
            line-height: 1.7;
            max-width: 56ch;
        }

        .public-footer-links {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            justify-content: flex-end;
            align-content: flex-start;
        }

        .public-footer-links a {
            padding: 10px 14px;
            border-radius: 999px;
            background: rgba(255,255,255,0.7);
            border: 1px solid var(--public-line);
            color: var(--public-muted);
            font-weight: 700;
        }

        .public-footer-links a:hover {
            background: #fff;
            color: var(--public-text);
        }

        @media (max-width: 960px) {
            .public-navbar {
                position: static;
                grid-template-columns: 1fr auto;
                align-items: center;
            }

            .public-navbar-menu,
            .public-nav-actions {
                grid-column: 1 / -1;
                display: none;
                justify-content: flex-start;
            }

            .public-navbar[data-open='true'] .public-navbar-menu,
            .public-navbar[data-open='true'] .public-nav-actions {
                display: flex;
            }

            .public-navbar[data-open='true'] .public-navbar-menu {
                margin-top: 4px;
            }

            .public-nav-links,
            .public-nav-actions {
                justify-content: flex-start;
                width: 100%;
            }

            .public-nav-links {
                flex-direction: column;
                align-items: stretch;
            }

            .public-nav-link {
                width: 100%;
            }

            .public-nav-actions {
                flex-direction: column;
                align-items: stretch;
            }

            .public-navbar-toggle {
                display: inline-flex;
            }

            .public-footer {
                grid-template-columns: 1fr;
                padding: 22px;
            }

            .public-footer-links {
                justify-content: flex-start;
            }
        }

        @media (max-width: 640px) {
            .public-shell {
                width: min(100% - 20px, 1180px);
                padding-top: 10px;
            }

            .public-navbar,
            .public-card,
            .public-panel,
            .public-footer {
                border-radius: 22px;
            }

            .public-panel,
            .public-card {
                padding-left: 18px;
                padding-right: 18px;
            }
        }
    </style>
    @stack('styles')
</head>
<body>
    <div class="public-shell">
        <nav class="public-navbar" id="publicNavbar" data-open="false">
            <div class="public-brand">
                <a href="{{ url('/') }}" class="public-brand-mark">R</a>
                <div class="public-brand-copy">
                    <h1>Restaurant</h1>
                    <p>Expérience client et réservation</p>
                </div>
            </div>

            <button type="button" class="public-navbar-toggle" id="publicNavbarToggle" aria-expanded="false" aria-controls="publicNavbarMenu" aria-label="Ouvrir la navigation">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
                    <path d="M4 7h16"></path>
                    <path d="M4 12h16"></path>
                    <path d="M4 17h16"></path>
                </svg>
            </button>

            <div class="public-navbar-menu" id="publicNavbarMenu">
                <div class="public-nav-links">
                    <a href="{{ route('home') }}" class="public-nav-link {{ request()->routeIs('home') ? 'active' : '' }}">Accueil</a>
                    <a href="{{ route('menu.index') }}" class="public-nav-link {{ request()->routeIs('menu.index') ? 'active' : '' }}">Menu</a>
                    @auth
                        <a href="{{ route('dashboard') }}" class="public-nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">Dashboard</a>
                        <a href="{{ route('orders.index') }}" class="public-nav-link {{ request()->routeIs('orders.*') ? 'active' : '' }}">Commandes</a>
                        <a href="{{ route('reservations.index') }}" class="public-nav-link {{ request()->routeIs('reservations.*') ? 'active' : '' }}">Reservations</a>
                        <a href="{{ route('profile.edit') }}" class="public-nav-link {{ request()->routeIs('profile.*') ? 'active' : '' }}">Profil</a>
                        @if(auth()->user()->isAdmin())
                            <a href="{{ route('admin.dashboard') }}" class="public-nav-link {{ request()->routeIs('admin.*') ? 'active' : '' }}">Admin</a>
                        @endif
                    @endauth
                </div>
            </div>

            <div class="public-nav-actions">
                @auth
                    <span class="public-user-badge">
                        <span class="public-user-mark">{{ \Illuminate\Support\Str::substr(auth()->user()->name, 0, 1) }}</span>
                        <span class="public-user-copy">
                            <strong>{{ auth()->user()->name }}</strong>
                            <span>{{ auth()->user()->isAdmin() ? 'Administrateur' : 'Client' }}</span>
                        </span>
                    </span>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="public-button-secondary">Déconnexion</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="public-button-secondary">Connexion</a>
                    @if(Route::has('register'))
                        <a href="{{ route('register') }}" class="public-button">Créer un compte</a>
                    @endif
                @endauth
            </div>
        </nav>

        <main class="public-content">
            @yield('content')
        </main>

        <footer class="public-footer">
            <div class="public-footer-copy">
                <span class="public-eyebrow">Espace public</span>
                <h2>Une interface client plus claire, plus stable et mieux reliée aux modules du restaurant.</h2>
                <p>
                    Le layout public regroupe désormais la navigation, les accès rapides et un habillage cohérent pour les vues menu, commandes, réservations et profil.
                </p>
            </div>

            <div class="public-footer-links">
                <a href="{{ route('home') }}">Accueil</a>
                <a href="{{ route('menu.index') }}">Carte</a>
                @auth
                    <a href="{{ route('orders.index') }}">Mes commandes</a>
                    <a href="{{ route('reservations.index') }}">Mes reservations</a>
                    <a href="{{ route('profile.edit') }}">Mon profil</a>
                @else
                    <a href="{{ route('login') }}">Connexion</a>
                @endauth
            </div>
        </footer>
    </div>

    <script>
        const publicNavbar = document.getElementById('publicNavbar');
        const publicNavbarToggle = document.getElementById('publicNavbarToggle');

        if (publicNavbar && publicNavbarToggle) {
            publicNavbarToggle.addEventListener('click', () => {
                const isOpen = publicNavbar.dataset.open === 'true';
                publicNavbar.dataset.open = isOpen ? 'false' : 'true';
                publicNavbarToggle.setAttribute('aria-expanded', isOpen ? 'false' : 'true');
            });
        }
    </script>

    @stack('scripts')
</body>
</html>