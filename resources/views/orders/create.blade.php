@extends('layouts.public')

@section('title', 'Commander | '.config('app.name', 'Restaurant'))

@push('styles')
    <style>
        .public-order-shell {
            display: grid;
            gap: 24px;
        }

        .public-order-hero,
        .public-order-section {
            padding: 30px;
        }

        .public-order-form {
            display: grid;
            gap: 18px;
        }

        .public-order-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 18px;
        }

        .public-order-field {
            display: grid;
            gap: 8px;
        }

        .public-order-field label {
            font-weight: 700;
            font-size: 0.92rem;
        }

        .public-order-field input,
        .public-order-field select,
        .public-order-field textarea {
            width: 100%;
            padding: 14px 16px;
            border-radius: 18px;
            border: 1px solid rgba(101, 83, 59, 0.16);
            background: rgba(255, 255, 255, 0.84);
            font: inherit;
            color: var(--public-text);
        }

        .public-order-field input:focus,
        .public-order-field select:focus,
        .public-order-field textarea:focus {
            outline: none;
            border-color: rgba(192, 99, 43, 0.42);
            box-shadow: 0 0 0 4px rgba(192, 99, 43, 0.1);
        }

        .public-order-items {
            display: grid;
            gap: 14px;
        }

        .public-order-item {
            display: grid;
            grid-template-columns: minmax(0, 1fr) 120px 160px 58px;
            gap: 12px;
            align-items: end;
            padding: 16px;
            border: 1px solid var(--public-line);
            border-radius: 22px;
            background: rgba(255, 255, 255, 0.76);
        }

        .public-order-total {
            padding: 18px 20px;
            border-radius: 22px;
            background: #1f2a2b;
            color: #fff;
            font-size: 1.1rem;
            font-weight: 800;
        }

        .public-order-error {
            color: #b42318;
            font-size: 0.9rem;
        }

        @media (max-width: 960px) {
            .public-order-grid,
            .public-order-item {
                grid-template-columns: 1fr;
            }

            .public-order-hero,
            .public-order-section {
                padding: 24px;
            }
        }
    </style>
@endpush

@section('content')
    @php
        $initialItems = old('items', $selectedMenu ? [['menu_id' => $selectedMenu->id, 'quantity' => 1]] : [['menu_id' => '', 'quantity' => 1]]);
        $menuOptions = $menus->map(fn ($menu) => [
            'id' => $menu->id,
            'name' => $menu->nom,
            'price' => (float) $menu->prix,
            'formatted_price' => $menu->formattedPrice(),
        ])->values()->all();
    @endphp

    <div class="public-order-shell">
        <section class="public-panel public-order-hero">
            <div style="display:flex; justify-content:space-between; gap:20px; align-items:flex-start; flex-wrap:wrap;">
                <div>
                    <span class="public-kicker">Commande client</span>
                    <h2 style="font-size: clamp(2rem, 3vw, 3.4rem); margin: 16px 0 10px;">Compose ta commande.</h2>
                    <p class="public-muted" style="margin: 0; max-width: 62ch; line-height: 1.75;">Ajoute un ou plusieurs plats, choisis une table si nécessaire et envoie ta commande au restaurant.</p>
                </div>
                <a href="{{ route('orders.index') }}" class="public-button-secondary">Voir mes commandes</a>
            </div>
        </section>

        @if($errors->any())
            <div class="public-card">
                <div class="public-order-error">{{ $errors->first() }}</div>
            </div>
        @endif

        <section class="public-card public-order-section">
            <form method="POST" action="{{ route('orders.store') }}" class="public-order-form">
                @csrf

                <div class="public-order-grid">
                    <div class="public-order-field">
                        <label for="table_id">Table (optionnel)</label>
                        <select id="table_id" name="table_id">
                            <option value="">Aucune table</option>
                            @foreach($tables as $table)
                                <option value="{{ $table->id }}" @selected(old('table_id') == $table->id)>Table {{ $table->numero }} - {{ $table->places }} places</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="public-order-field">
                        <label for="notes">Note de commande</label>
                        <textarea id="notes" name="notes" rows="3" placeholder="Précisions utiles pour la préparation ou le service">{{ old('notes') }}</textarea>
                    </div>
                </div>

                <div style="display:flex; justify-content:space-between; gap:12px; align-items:center; flex-wrap:wrap;">
                    <div>
                        <h3 style="margin:0 0 6px; font-size:1.2rem;">Panier</h3>
                        <p class="public-muted" style="margin:0;">Sélectionne les plats à commander.</p>
                    </div>
                    <button type="button" class="public-button-secondary" id="add-order-item">Ajouter un plat</button>
                </div>

                <div id="order-items" class="public-order-items">
                    @foreach($initialItems as $index => $item)
                        <div class="public-order-item">
                            <div class="public-order-field">
                                <label>Plat</label>
                                <select name="items[{{ $index }}][menu_id]" class="order-item-menu" required>
                                    <option value="">Sélectionner un plat</option>
                                    @foreach($menus as $menu)
                                        <option value="{{ $menu->id }}" data-price="{{ $menu->prix }}" @selected((string) ($item['menu_id'] ?? '') === (string) $menu->id)>
                                            {{ $menu->nom }} - {{ $menu->formattedPrice() }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="public-order-field">
                                <label>Quantité</label>
                                <input type="number" name="items[{{ $index }}][quantity]" class="order-item-quantity" min="1" value="{{ $item['quantity'] ?? 1 }}" required>
                            </div>
                            <div class="public-order-field">
                                <label>Sous-total</label>
                                <div class="public-button-secondary order-item-total" style="justify-content:flex-start;">0 FCFA</div>
                            </div>
                            <button type="button" class="public-button-secondary remove-order-item">X</button>
                        </div>
                    @endforeach
                </div>

                <div class="public-order-total" id="order-total">Total: 0 FCFA</div>

                <div style="display:flex; gap:12px; flex-wrap:wrap;">
                    <button type="submit" class="public-button">Envoyer la commande</button>
                    <a href="{{ route('menu.index') }}" class="public-button-secondary">Retour au menu</a>
                </div>
            </form>
        </section>
    </div>
@endsection

@push('scripts')
    <script>
        (() => {
            const itemsContainer = document.getElementById('order-items');
            const addButton = document.getElementById('add-order-item');
            const totalLabel = document.getElementById('order-total');
            const menus = @json($menuOptions);

            if (!itemsContainer || !addButton || !totalLabel) {
                return;
            }

            let itemIndex = itemsContainer.querySelectorAll('.public-order-item').length;
            const moneyFormatter = new Intl.NumberFormat('fr-FR');

            const renderOptions = (selectedValue = '') => '<option value="">Sélectionner un plat</option>' + menus.map((menu) => {
                const selected = String(selectedValue) === String(menu.id) ? 'selected' : '';
                return `<option value="${menu.id}" data-price="${menu.price}" ${selected}>${menu.name} - ${menu.formatted_price}</option>`;
            }).join('');

            const createItem = (selectedValue = '', quantity = 1) => {
                const wrapper = document.createElement('div');
                wrapper.className = 'public-order-item';
                wrapper.innerHTML = `
                    <div class="public-order-field">
                        <label>Plat</label>
                        <select name="items[${itemIndex}][menu_id]" class="order-item-menu" required>
                            ${renderOptions(selectedValue)}
                        </select>
                    </div>
                    <div class="public-order-field">
                        <label>Quantité</label>
                        <input type="number" name="items[${itemIndex}][quantity]" class="order-item-quantity" min="1" value="${quantity}" required>
                    </div>
                    <div class="public-order-field">
                        <label>Sous-total</label>
                        <div class="public-button-secondary order-item-total" style="justify-content:flex-start;">0 FCFA</div>
                    </div>
                    <button type="button" class="public-button-secondary remove-order-item">X</button>`;
                itemIndex += 1;
                return wrapper;
            };

            const updateTotals = () => {
                let total = 0;
                itemsContainer.querySelectorAll('.public-order-item').forEach((item) => {
                    const menuField = item.querySelector('.order-item-menu');
                    const quantityField = item.querySelector('.order-item-quantity');
                    const totalField = item.querySelector('.order-item-total');
                    const selectedOption = menuField.options[menuField.selectedIndex];
                    const unitPrice = Number(selectedOption?.dataset.price || 0);
                    const quantity = Number(quantityField.value || 0);
                    const lineTotal = unitPrice * quantity;
                    total += lineTotal;
                    totalField.textContent = moneyFormatter.format(lineTotal) + ' FCFA';
                });

                totalLabel.textContent = 'Total: ' + moneyFormatter.format(total) + ' FCFA';
            };

            addButton.addEventListener('click', () => {
                itemsContainer.appendChild(createItem());
                updateTotals();
            });

            itemsContainer.addEventListener('click', (event) => {
                if (!event.target.classList.contains('remove-order-item')) {
                    return;
                }

                event.target.closest('.public-order-item')?.remove();

                if (!itemsContainer.querySelector('.public-order-item')) {
                    itemsContainer.appendChild(createItem());
                }

                updateTotals();
            });

            itemsContainer.addEventListener('input', updateTotals);
            itemsContainer.addEventListener('change', updateTotals);
            updateTotals();
        })();
    </script>
@endpush