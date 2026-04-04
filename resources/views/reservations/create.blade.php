@extends('layouts.public')

@section('title', 'Réserver | '.config('app.name', 'Restaurant'))

@push('styles')
    <style>
        .public-reservation-shell {
            display: grid;
            gap: 24px;
        }

        .public-reservation-hero,
        .public-reservation-section {
            padding: 30px;
        }

        .public-reservation-form {
            display: grid;
            gap: 18px;
        }

        .public-reservation-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 18px;
        }

        .public-reservation-field {
            display: grid;
            gap: 8px;
        }

        .public-reservation-field label {
            font-weight: 700;
            font-size: 0.92rem;
        }

        .public-reservation-field input,
        .public-reservation-field select,
        .public-reservation-field textarea {
            width: 100%;
            padding: 14px 16px;
            border-radius: 18px;
            border: 1px solid rgba(101, 83, 59, 0.16);
            background: rgba(255, 255, 255, 0.84);
            font: inherit;
            color: var(--public-text);
        }

        .public-reservation-field input:focus,
        .public-reservation-field select:focus,
        .public-reservation-field textarea:focus {
            outline: none;
            border-color: rgba(192, 99, 43, 0.42);
            box-shadow: 0 0 0 4px rgba(192, 99, 43, 0.1);
        }

        .public-reservation-error {
            color: #b42318;
            font-size: 0.9rem;
        }

        .public-reservation-menus {
            display: grid;
            gap: 12px;
        }

        .public-reservation-menu-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 14px;
        }

        .public-reservation-menu-option {
            position: relative;
        }

        .public-reservation-menu-option input {
            position: absolute;
            inset: 0;
            opacity: 0;
            pointer-events: none;
        }

        .public-reservation-menu-card {
            display: grid;
            gap: 8px;
            padding: 16px;
            border-radius: 20px;
            border: 1px solid rgba(101, 83, 59, 0.16);
            background: rgba(255, 255, 255, 0.82);
            transition: 0.2s ease;
            cursor: pointer;
        }

        .public-reservation-menu-option input:checked + .public-reservation-menu-card {
            border-color: rgba(192, 99, 43, 0.5);
            background: rgba(192, 99, 43, 0.08);
            box-shadow: 0 0 0 4px rgba(192, 99, 43, 0.08);
        }

        .public-reservation-menu-option input:disabled + .public-reservation-menu-card {
            opacity: 0.5;
            cursor: not-allowed;
        }

        .public-reservation-menu-top {
            display: flex;
            justify-content: space-between;
            gap: 12px;
            align-items: flex-start;
        }

        .public-reservation-menu-tag {
            display: inline-flex;
            padding: 6px 10px;
            border-radius: 999px;
            background: rgba(47, 107, 82, 0.1);
            color: var(--public-green);
            font-size: 0.78rem;
            font-weight: 700;
        }

        .public-reservation-menu-help {
            color: var(--public-muted);
            font-size: 0.92rem;
            line-height: 1.7;
        }

        @media (max-width: 960px) {
            .public-reservation-grid {
                grid-template-columns: 1fr;
            }

            .public-reservation-menu-grid {
                grid-template-columns: 1fr;
            }

            .public-reservation-hero,
            .public-reservation-section {
                padding: 24px;
            }
        }
    </style>
@endpush

@section('content')
    <div class="public-reservation-shell">
        <section class="public-panel public-reservation-hero">
            <div style="display:flex; justify-content:space-between; gap:20px; align-items:flex-start; flex-wrap:wrap;">
                <div>
                    <span class="public-kicker">Réservation publique</span>
                    <h2 style="font-size: clamp(2rem, 3vw, 3.4rem); margin: 16px 0 10px;">Réserve une table.</h2>
                    <p class="public-muted" style="margin: 0; max-width: 62ch; line-height: 1.75;">Choisis une table disponible, indique le créneau souhaité et précise si tu souhaites un plat particulier.</p>
                </div>
                <a href="{{ route('reservations.index') }}" class="public-button-secondary">Voir mes réservations</a>
            </div>
        </section>

        @if($errors->any())
            <div class="public-card">
                <div class="public-reservation-error">{{ $errors->first() }}</div>
            </div>
        @endif

        <section class="public-card public-reservation-section">
            @if($tables->isEmpty())
                <div class="public-muted" style="margin-bottom:18px; line-height:1.7;">
                    Aucune table n'est disponible pour le moment. Ajoute ou active des tables dans l'administration pour ouvrir les réservations publiques.
                </div>
                @if(auth()->user()->isAdmin())
                    <div style="display:flex; gap:12px; flex-wrap:wrap; margin-bottom:18px;">
                        <a href="{{ route('admin.tables.create') }}" class="public-button">Ajouter une table</a>
                        <a href="{{ route('admin.tables.index') }}" class="public-button-secondary">Gérer les tables</a>
                    </div>
                @endif
            @endif

            <form method="POST" action="{{ route('reservations.store') }}" class="public-reservation-form">
                @csrf

                <div class="public-reservation-menus">
                    <div>
                        <label style="font-weight:700; font-size:0.92rem; display:block; margin-bottom:8px;">Plats souhaités</label>
                        <p class="public-reservation-menu-help" style="margin:0;">Sélectionne un ou deux plats maximum pour préciser ce que tu souhaites consommer pendant la réservation.</p>
                    </div>

                    <div class="public-reservation-menu-grid" id="reservationMenuGrid">
                        @foreach($menus as $menu)
                            <label class="public-reservation-menu-option">
                                <input
                                    type="checkbox"
                                    name="menu_ids[]"
                                    value="{{ $menu->id }}"
                                    @checked(in_array($menu->id, old('menu_ids', $selectedMenuIds ?? [])))
                                    @disabled($tables->isEmpty())
                                >
                                <span class="public-reservation-menu-card">
                                    <span class="public-reservation-menu-top">
                                        <strong>{{ $menu->nom }}</strong>
                                        <span class="public-reservation-menu-tag">{{ $menu->formattedPrice() }}</span>
                                    </span>
                                    <span class="public-muted">{{ $menu->categorie }}</span>
                                    <span class="public-reservation-menu-help">{{ \Illuminate\Support\Str::limit($menu->description ?: 'Plat disponible pour accompagner la réservation.', 100) }}</span>
                                </span>
                            </label>
                        @endforeach
                    </div>
                </div>

                <div class="public-reservation-grid">
                    <div class="public-reservation-field">
                        <label for="table_id">Table</label>
                        <select id="table_id" name="table_id" required @disabled($tables->isEmpty())>
                            <option value="">Sélectionner une table</option>
                            @foreach($tables as $table)
                                <option value="{{ $table->id }}" @selected(old('table_id') == $table->id)>Table {{ $table->numero }} - {{ $table->places }} places</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="public-reservation-field">
                        <label for="date_reservation">Date et heure</label>
                        <input id="date_reservation" name="date_reservation" type="datetime-local" value="{{ old('date_reservation') }}" required @disabled($tables->isEmpty())>
                    </div>

                    <div class="public-reservation-field">
                        <label for="nombre_personnes">Nombre de personnes</label>
                        <input id="nombre_personnes" name="nombre_personnes" type="number" min="1" value="{{ old('nombre_personnes', 2) }}" required @disabled($tables->isEmpty())>
                    </div>

                    <div class="public-reservation-field">
                        <label for="notes">Notes</label>
                        <textarea id="notes" name="notes" rows="4" placeholder="Précisions pour la réservation, préférence de table, allergie, demande particulière..." @disabled($tables->isEmpty())>{{ old('notes') }}</textarea>
                    </div>
                </div>

                <div style="display:flex; gap:12px; flex-wrap:wrap;">
                    <button type="submit" class="public-button" @disabled($tables->isEmpty())>Confirmer la réservation</button>
                    <a href="{{ route('menu.index') }}" class="public-button-secondary">Retour au menu</a>
                </div>
            </form>
        </section>
    </div>
@endsection

@push('scripts')
    <script>
        const reservationMenuGrid = document.getElementById('reservationMenuGrid');

        if (reservationMenuGrid) {
            const checkboxes = Array.from(reservationMenuGrid.querySelectorAll('input[type="checkbox"]'));

            const syncReservationMenuState = () => {
                const checkedCount = checkboxes.filter((checkbox) => checkbox.checked).length;

                checkboxes.forEach((checkbox) => {
                    checkbox.disabled = checkbox.closest('.public-reservation-menu-option').querySelector('.public-reservation-menu-card') === null
                        ? checkbox.disabled
                        : (!checkbox.checked && checkedCount >= 2) || checkbox.hasAttribute('data-force-disabled');
                });
            };

            checkboxes.forEach((checkbox) => {
                if (checkbox.disabled) {
                    checkbox.setAttribute('data-force-disabled', 'true');
                }

                checkbox.addEventListener('change', syncReservationMenuState);
            });

            syncReservationMenuState();
        }
    </script>
@endpush