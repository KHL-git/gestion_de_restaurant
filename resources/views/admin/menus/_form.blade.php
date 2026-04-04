<div class="row g-3">
    <div class="col-md-6">
        <label for="nom" class="form-label">Nom du plat</label>
        <input
            id="nom"
            name="nom"
            type="text"
            value="{{ old('nom', $menu->nom ?? '') }}"
            class="form-control @error('nom') is-invalid @enderror"
            required
        >
        @error('nom')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-3">
        <label for="categorie" class="form-label">Catégorie</label>
        <select
            id="categorie"
            name="categorie"
            class="form-select @error('categorie') is-invalid @enderror"
            required
        >
            <option value="" disabled @selected(old('categorie', $menu->categorie ?? '') === '')>Choisir une catégorie</option>
            @foreach($categories as $value => $label)
                <option value="{{ $value }}" @selected(old('categorie', $menu->categorie ?? '') === $value)>{{ $label }}</option>
            @endforeach
        </select>
        @error('categorie')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-3">
        <label for="prix" class="form-label">Prix (FCFA)</label>
        <input
            id="prix"
            name="prix"
            type="number"
            step="1"
            min="0"
            value="{{ old('prix', isset($menu) ? number_format((float) $menu->prix, 0, '.', '') : '') }}"
            class="form-control @error('prix') is-invalid @enderror"
            required
        >
        <div class="form-text">Saisir le montant en francs CFA.</div>
        @error('prix')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-12">
        <label for="description" class="form-label">Description</label>
        <textarea
            id="description"
            name="description"
            rows="4"
            class="form-control @error('description') is-invalid @enderror"
            placeholder="Décris le plat, ses ingrédients ou sa préparation"
        >{{ old('description', $menu->description ?? '') }}</textarea>
        @error('description')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-4">
        <label for="disponible" class="form-label">Disponibilité</label>
        <select id="disponible" name="disponible" class="form-select @error('disponible') is-invalid @enderror">
            <option value="1" @selected((string) old('disponible', isset($menu) ? (int) $menu->disponible : 1) === '1')>Disponible</option>
            <option value="0" @selected((string) old('disponible', isset($menu) ? (int) $menu->disponible : 1) === '0')>Indisponible</option>
        </select>
        @error('disponible')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-8">
        <label for="image" class="form-label">Image du plat</label>
        <input
            id="image"
            name="image"
            type="file"
            accept="image/png,image/jpeg,image/webp"
            class="form-control @error('image') is-invalid @enderror"
        >
        <div class="form-text">Formats acceptés: JPG, PNG, WEBP. Taille max: 2 Mo.</div>
        @error('image')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    @if(isset($menu) && $menu->imageUrl())
        <div class="col-12">
            <div class="border rounded-4 p-3 bg-light-subtle d-inline-block">
                <div class="text-muted small mb-2">Image actuelle</div>
                <img src="{{ $menu->imageUrl() }}" alt="{{ $menu->nom }}" style="width: 180px; height: 180px; object-fit: cover; border-radius: 18px;">
            </div>
        </div>
    @endif
</div>

<div class="d-flex gap-2 mt-4">
    <button type="submit" class="btn btn-primary">{{ $submitLabel }}</button>
    <a href="{{ route('admin.menus.index') }}" class="btn btn-outline-secondary">Annuler</a>
</div>