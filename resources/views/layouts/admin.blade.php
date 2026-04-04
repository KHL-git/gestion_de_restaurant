<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Administration').' | '.config('app.name', 'Restaurant')</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        :root {
            --admin-bg: #f3efe6;
            --admin-surface: #fffdf8;
            --admin-sidebar: #1d2a3a;
            --admin-sidebar-muted: #9eb0c5;
            --admin-border: #dfd4c2;
            --admin-text: #1f2933;
            --admin-accent: #b7791f;
            --admin-accent-strong: #8c5a14;
            --admin-shadow: 0 20px 45px rgba(30, 42, 58, 0.12);
        }

        body {
            margin: 0;
            font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
            background: radial-gradient(circle at top left, #fbf6ea 0%, var(--admin-bg) 45%, #e9dfd0 100%);
            color: var(--admin-text);
        }

        .admin-shell {
            min-height: 100vh;
            display: flex;
        }

        .admin-sidebar {
            width: 280px;
            background: linear-gradient(180deg, #213246 0%, var(--admin-sidebar) 100%);
            color: #fff;
            padding: 2rem 1.25rem;
            display: flex;
            flex-direction: column;
            gap: 2rem;
            box-shadow: var(--admin-shadow);
            position: sticky;
            top: 0;
            height: 100vh;
        }

        .admin-brand {
            display: flex;
            align-items: center;
            gap: 0.9rem;
        }

        .admin-brand-badge {
            width: 48px;
            height: 48px;
            border-radius: 14px;
            display: grid;
            place-items: center;
            font-size: 1.4rem;
            background: linear-gradient(135deg, #f6ad55 0%, #dd6b20 100%);
            box-shadow: 0 10px 20px rgba(221, 107, 32, 0.25);
        }

        .admin-brand-title {
            margin: 0;
            font-size: 1.05rem;
            font-weight: 700;
            letter-spacing: 0.04em;
            text-transform: uppercase;
        }

        .admin-brand-subtitle {
            margin: 0.15rem 0 0;
            color: var(--admin-sidebar-muted);
            font-size: 0.88rem;
        }

        .admin-nav-label {
            color: var(--admin-sidebar-muted);
            font-size: 0.78rem;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            margin-bottom: 0.75rem;
        }

        .admin-nav {
            display: grid;
            gap: 0.5rem;
        }

        .admin-nav-link {
            color: #e8edf3;
            text-decoration: none;
            border: 1px solid transparent;
            border-radius: 16px;
            padding: 0.95rem 1rem;
            display: flex;
            align-items: center;
            gap: 0.85rem;
            transition: 0.2s ease;
        }

        .admin-nav-link:hover,
        .admin-nav-link.active {
            background: rgba(255, 255, 255, 0.08);
            border-color: rgba(255, 255, 255, 0.08);
            color: #fff;
            transform: translateX(2px);
        }

        .admin-nav-icon {
            width: 38px;
            height: 38px;
            border-radius: 12px;
            display: grid;
            place-items: center;
            background: rgba(255, 255, 255, 0.08);
            font-size: 1rem;
        }

        .admin-nav-text strong {
            display: block;
            font-size: 0.96rem;
            font-weight: 600;
        }

        .admin-nav-text span {
            display: block;
            color: var(--admin-sidebar-muted);
            font-size: 0.8rem;
        }

        .admin-sidebar-footer {
            margin-top: auto;
            background: rgba(255, 255, 255, 0.06);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 18px;
            padding: 1rem;
        }

        .admin-sidebar-footer p {
            margin: 0;
        }

        .admin-sidebar-footer .name {
            font-weight: 600;
        }

        .admin-sidebar-footer .role {
            color: var(--admin-sidebar-muted);
            font-size: 0.86rem;
        }

        .admin-logout {
            margin-top: 0.9rem;
            width: 100%;
        }

        .admin-content {
            flex: 1;
            padding: 2rem;
        }

        .admin-topbar {
            background: rgba(255, 253, 248, 0.85);
            backdrop-filter: blur(14px);
            border: 1px solid rgba(223, 212, 194, 0.8);
            border-radius: 24px;
            padding: 1.5rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            box-shadow: var(--admin-shadow);
            margin-bottom: 1.75rem;
        }

        .admin-topbar h1 {
            margin: 0;
            font-size: 1.75rem;
            font-weight: 700;
        }

        .admin-topbar p {
            margin: 0.35rem 0 0;
            color: #5b6672;
        }

        .admin-chip {
            background: #fff6e8;
            border: 1px solid #f1d7ae;
            color: var(--admin-accent-strong);
            padding: 0.55rem 0.85rem;
            border-radius: 999px;
            font-size: 0.9rem;
            font-weight: 600;
            white-space: nowrap;
        }

        .admin-panel {
            background: rgba(255, 253, 248, 0.94);
            border: 1px solid rgba(223, 212, 194, 0.9);
            border-radius: 26px;
            padding: 1.5rem;
            box-shadow: var(--admin-shadow);
        }

        .table,
        .card,
        .alert {
            border-radius: 18px;
        }

        .card,
        .table,
        .form-control,
        .form-select {
            border-color: var(--admin-border);
        }

        .btn-primary {
            background-color: var(--admin-accent);
            border-color: var(--admin-accent);
        }

        .btn-primary:hover,
        .btn-primary:focus {
            background-color: var(--admin-accent-strong);
            border-color: var(--admin-accent-strong);
        }

        @media (max-width: 991.98px) {
            .admin-shell {
                flex-direction: column;
            }

            .admin-sidebar {
                width: 100%;
                height: auto;
                position: static;
            }

            .admin-content {
                padding: 1rem;
            }

            .admin-topbar {
                flex-direction: column;
                align-items: flex-start;
            }
        }
    </style>
    @stack('styles')
</head>
<body>
    <div class="admin-shell">
        <aside class="admin-sidebar">
            <div class="admin-brand">
                <div class="admin-brand-badge">R</div>
                <div>
                    <p class="admin-brand-title">Restaurant</p>
                    <p class="admin-brand-subtitle">Panneau d'administration</p>
                </div>
            </div>

            <div>
                <div class="admin-nav-label">Navigation</div>
                <nav class="admin-nav">
                    <a href="{{ url('/admin') }}" class="admin-nav-link {{ request()->is('admin') ? 'active' : '' }}">
                        <span class="admin-nav-icon">🏠</span>
                        <span class="admin-nav-text">
                            <strong>Dashboard</strong>
                            <span>Vue d'ensemble</span>
                        </span>
                    </a>

                    <a href="{{ route('admin.users.index') }}" class="admin-nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                        <span class="admin-nav-icon">👥</span>
                        <span class="admin-nav-text">
                            <strong>Utilisateurs</strong>
                            <span>Comptes, rôles et accès</span>
                        </span>
                    </a>

                    <a href="{{ route('admin.menus.index') }}" class="admin-nav-link {{ request()->routeIs('admin.menus.*') ? 'active' : '' }}">
                        <span class="admin-nav-icon">🍽️</span>
                        <span class="admin-nav-text">
                            <strong>Menu</strong>
                            <span>Plats, prix et disponibilité</span>
                        </span>
                    </a>

                    <a href="{{ route('admin.tables.index') }}" class="admin-nav-link {{ request()->routeIs('admin.tables.*') ? 'active' : '' }}">
                        <span class="admin-nav-icon">🪑</span>
                        <span class="admin-nav-text">
                            <strong>Tables</strong>
                            <span>Capacité et disponibilité</span>
                        </span>
                    </a>

                    <a href="{{ route('admin.sales.index') }}" class="admin-nav-link {{ request()->routeIs('admin.sales.*') ? 'active' : '' }}">
                        <span class="admin-nav-icon">💰</span>
                        <span class="admin-nav-text">
                            <strong>Ventes</strong>
                            <span>Historique et enregistrement</span>
                        </span>
                    </a>

                    <a href="{{ route('admin.orders.index') }}" class="admin-nav-link {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}">
                        <span class="admin-nav-icon">🧾</span>
                        <span class="admin-nav-text">
                            <strong>Commandes</strong>
                            <span>Suivi et validation client</span>
                        </span>
                    </a>

                    <a href="{{ route('admin.reservations.index') }}" class="admin-nav-link {{ request()->routeIs('admin.reservations.*') ? 'active' : '' }}">
                        <span class="admin-nav-icon">📅</span>
                        <span class="admin-nav-text">
                            <strong>Reservations</strong>
                            <span>Tables et demandes client</span>
                        </span>
                    </a>

                    <a href="{{ route('profile.edit') }}" class="admin-nav-link {{ request()->routeIs('profile.*') ? 'active' : '' }}">
                        <span class="admin-nav-icon">🙍</span>
                        <span class="admin-nav-text">
                            <strong>Mon profil</strong>
                            <span>Informations personnelles</span>
                        </span>
                    </a>
                </nav>
            </div>

            <div class="admin-sidebar-footer">
                <p class="name">{{ auth()->user()->name }}</p>
                <p class="role">Administrateur</p>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="btn btn-outline-light admin-logout">Se déconnecter</button>
                </form>
            </div>
        </aside>

        <div class="admin-content">
            <header class="admin-topbar">
                <div>
                    <h1>@yield('admin_title', 'Administration')</h1>
                    <p>@yield('admin_subtitle', 'Gérez les accès et les opérations du restaurant depuis cet espace dédié.')</p>
                </div>
                <div class="admin-chip">{{ now()->translatedFormat('d F Y') }}</div>
            </header>

            <section class="admin-panel">
                @yield('content')
            </section>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>