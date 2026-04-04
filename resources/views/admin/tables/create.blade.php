@extends('layouts.admin')

@section('title', 'Nouvelle table')
@section('admin_title', 'Ajouter une table')
@section('admin_subtitle', 'Crée une table réservable depuis l espace public.')

@section('content')
    <div class="card shadow-sm border-0">
        <div class="card-body p-4">
            <form method="POST" action="{{ route('admin.tables.store') }}" class="row g-4">
                @csrf

                <div class="col-md-4">
                    <label for="numero" class="form-label">Numero</label>
                    <input id="numero" type="text" name="numero" value="{{ old('numero') }}" class="form-control @error('numero') is-invalid @enderror" maxlength="10" required>
                    @error('numero')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-4">
                    <label for="places" class="form-label">Capacite</label>
                    <input id="places" type="number" name="places" value="{{ old('places', 2) }}" class="form-control @error('places') is-invalid @enderror" min="1" max="30" required>
                    @error('places')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-4">
                    <label for="disponible" class="form-label">Disponibilite</label>
                    <select id="disponible" name="disponible" class="form-select @error('disponible') is-invalid @enderror" required>
                        <option value="1" @selected(old('disponible', '1') === '1')>Disponible</option>
                        <option value="0" @selected(old('disponible') === '0')>Inactive</option>
                    </select>
                    @error('disponible')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-12 d-flex gap-2 flex-wrap">
                    <button type="submit" class="btn btn-primary">Enregistrer la table</button>
                    <a href="{{ route('admin.tables.index') }}" class="btn btn-outline-secondary">Retour</a>
                </div>
            </form>
        </div>
    </div>
@endsection