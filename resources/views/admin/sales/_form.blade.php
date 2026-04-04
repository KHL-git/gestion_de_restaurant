@php
    $lineItems = old('items', $sale->lines->isNotEmpty()
        ? $sale->lines->map(fn ($line) => ['menu_id' => $line->menu_id, 'quantity' => $line->quantity])->values()->all()
        : ($sale->menu_id ? [['menu_id' => $sale->menu_id, 'quantity' => $sale->quantity ?: 1]] : [['menu_id' => '', 'quantity' => 1]]));

    $menuOptions = $menus->map(fn ($menu) => [
        'id' => $menu->id,
        'name' => $menu->nom,
        'price' => (float) $menu->prix,
        'formatted_price' => $menu->formattedPrice(),
    ])->values()->all();
@endphp

<div class="row g-3">
    <div class="col-md-6">
        <label for="user_id" class="form-label">Client lie a un compte</label>
        <select id="user_id" name="user_id" class="form-select @error('user_id') is-invalid @enderror">
            <option value="">Client occasionnel</option>
            @foreach($users as $user)
                <option value="{{ $user->id }}" @selected(old('user_id', $sale->user_id) == $user->id)>
                    {{ $user->name }} - {{ $user->email }}
                </option>
            @endforeach
        </select>
        @error('user_id')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        <label for="client_name" class="form-label">Nom du client</label>
        <input type="text" id="client_name" name="client_name" class="form-control @error('client_name') is-invalid @enderror" value="{{ old('client_name', $sale->client_name) }}" placeholder="Ex: Client comptoir">
        <div class="form-text">Laisse vide si le client selectionne ci-dessus suffit.</div>
        @error('client_name')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center gap-3 flex-wrap mb-2">
            <div>
                <label class="form-label mb-0">Panier de vente</label>
                <div class="form-text">Ajoute un ou plusieurs plats a la meme vente.</div>
            </div>
            <button type="button" class="btn btn-outline-primary btn-sm" id="add-sale-line">Ajouter un plat</button>
        </div>

        @error('items')
            <div class="alert alert-danger py-2">{{ $message }}</div>
        @enderror

        <div id="sale-lines" class="d-grid gap-3">
            @foreach($lineItems as $index => $item)
                <div class="card border-0 shadow-sm sale-line" data-line-index="{{ $index }}">
                    <div class="card-body">
                        <div class="row g-3 align-items-end">
                            <div class="col-lg-7">
                                <label class="form-label">Plat</label>
                                <select name="items[{{ $index }}][menu_id]" class="form-select sale-line-menu @error("items.$index.menu_id") is-invalid @enderror" required>
                                    <option value="">Selectionner un plat</option>
                                    @foreach($menus as $menu)
                                        <option value="{{ $menu->id }}" data-price="{{ $menu->prix }}" @selected((string) ($item['menu_id'] ?? '') === (string) $menu->id)>
                                            {{ $menu->nom }} - {{ $menu->formattedPrice() }}
                                        </option>
                                    @endforeach
                                </select>
                                @error("items.$index.menu_id")
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-lg-2 col-md-4">
                                <label class="form-label">Quantite</label>
                                <input type="number" name="items[{{ $index }}][quantity]" min="1" value="{{ $item['quantity'] ?? 1 }}" class="form-control sale-line-quantity @error("items.$index.quantity") is-invalid @enderror" required>
                                @error("items.$index.quantity")
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-lg-2 col-md-4">
                                <label class="form-label">Sous-total</label>
                                <div class="form-control bg-light sale-line-total">0 FCFA</div>
                            </div>
                            <div class="col-lg-1 col-md-4 d-grid">
                                <button type="button" class="btn btn-outline-danger remove-sale-line">X</button>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <div class="col-md-6">
        <label class="form-label">Montant estime</label>
        <div id="sale-total-preview" class="form-control bg-light">{{ $sale->exists ? $sale->formattedTotal() : '0 FCFA' }}</div>
    </div>

    <div class="col-md-6">
        <label for="payment_method" class="form-label">Mode de paiement</label>
        <select id="payment_method" name="payment_method" class="form-select @error('payment_method') is-invalid @enderror" required>
            @foreach($paymentMethods as $value => $label)
                <option value="{{ $value }}" @selected(old('payment_method', $sale->payment_method) === $value)>{{ $label }}</option>
            @endforeach
        </select>
        @error('payment_method')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        <label for="status" class="form-label">Statut</label>
        <select id="status" name="status" class="form-select @error('status') is-invalid @enderror" required>
            @foreach($statuses as $value => $label)
                <option value="{{ $value }}" @selected(old('status', $sale->status) === $value)>{{ $label }}</option>
            @endforeach
        </select>
        @error('status')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        <label for="sold_at" class="form-label">Date de vente</label>
        <input type="datetime-local" id="sold_at" name="sold_at" class="form-control @error('sold_at') is-invalid @enderror" value="{{ old('sold_at', optional($sale->sold_at)->format('Y-m-d\TH:i') ?? now()->format('Y-m-d\TH:i')) }}" required>
        @error('sold_at')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-12">
        <label for="notes" class="form-label">Notes</label>
        <textarea id="notes" name="notes" rows="4" class="form-control @error('notes') is-invalid @enderror" placeholder="Precisions utiles sur la vente, le service ou le paiement">{{ old('notes', $sale->notes) }}</textarea>
        @error('notes')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>

<div class="d-flex justify-content-between align-items-center gap-3 flex-wrap mt-4">
    <a href="{{ route('admin.sales.index') }}" class="btn btn-outline-secondary">Retour a l'historique</a>
    <button type="submit" class="btn btn-primary">{{ $submitLabel }}</button>
</div>

@push('scripts')
    <script>
        (() => {
            const linesContainer = document.getElementById('sale-lines');
            const addLineButton = document.getElementById('add-sale-line');
            const totalPreview = document.getElementById('sale-total-preview');
            const menus = @json($menuOptions);

            if (!linesContainer || !addLineButton || !totalPreview) {
                return;
            }

            const formatPrice = (value) => new Intl.NumberFormat('fr-FR').format(value) + ' FCFA';

            let lineIndex = linesContainer.querySelectorAll('.sale-line').length;

            const renderOptions = (selectedValue = '') => {
                const defaultOption = '<option value="">Selectionner un plat</option>';

                return defaultOption + menus.map((menu) => {
                    const selected = String(selectedValue) === String(menu.id) ? 'selected' : '';
                    return `<option value="${menu.id}" data-price="${menu.price}" ${selected}>${menu.name} - ${menu.formatted_price}</option>`;
                }).join('');
            };

            const createLine = (selectedValue = '', quantity = 1) => {
                const wrapper = document.createElement('div');
                wrapper.className = 'card border-0 shadow-sm sale-line';
                wrapper.dataset.lineIndex = String(lineIndex);
                wrapper.innerHTML = `
                    <div class="card-body">
                        <div class="row g-3 align-items-end">
                            <div class="col-lg-7">
                                <label class="form-label">Plat</label>
                                <select name="items[${lineIndex}][menu_id]" class="form-select sale-line-menu" required>
                                    ${renderOptions(selectedValue)}
                                </select>
                            </div>
                            <div class="col-lg-2 col-md-4">
                                <label class="form-label">Quantite</label>
                                <input type="number" name="items[${lineIndex}][quantity]" min="1" value="${quantity}" class="form-control sale-line-quantity" required>
                            </div>
                            <div class="col-lg-2 col-md-4">
                                <label class="form-label">Sous-total</label>
                                <div class="form-control bg-light sale-line-total">0 FCFA</div>
                            </div>
                            <div class="col-lg-1 col-md-4 d-grid">
                                <button type="button" class="btn btn-outline-danger remove-sale-line">X</button>
                            </div>
                        </div>
                    </div>`;
                lineIndex += 1;
                return wrapper;
            };

            const updateLineTotal = (line) => {
                const menuField = line.querySelector('.sale-line-menu');
                const quantityField = line.querySelector('.sale-line-quantity');
                const lineTotal = line.querySelector('.sale-line-total');
                const selectedOption = menuField.options[menuField.selectedIndex];
                const unitPrice = Number(selectedOption?.dataset.price || 0);
                const quantity = Number(quantityField.value || 0);
                lineTotal.textContent = formatPrice(unitPrice * quantity);
                return unitPrice * quantity;
            };

            const updateTotal = () => {
                const total = Array.from(linesContainer.querySelectorAll('.sale-line')).reduce((sum, line) => sum + updateLineTotal(line), 0);
                totalPreview.textContent = formatPrice(total);
            };

            const ensureAtLeastOneLine = () => {
                if (linesContainer.querySelectorAll('.sale-line').length === 0) {
                    linesContainer.appendChild(createLine());
                }
            };

            addLineButton.addEventListener('click', () => {
                linesContainer.appendChild(createLine());
                updateTotal();
            });

            linesContainer.addEventListener('click', (event) => {
                const target = event.target;

                if (!target.classList.contains('remove-sale-line')) {
                    return;
                }

                target.closest('.sale-line')?.remove();
                ensureAtLeastOneLine();
                updateTotal();
            });

            linesContainer.addEventListener('change', updateTotal);
            linesContainer.addEventListener('input', updateTotal);
            updateTotal();
        })();
    </script>
@endpush