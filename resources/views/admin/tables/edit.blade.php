@extends('layouts.admin')

@section('title', 'Modifier une table')
@section('admin_title', 'Modifier la table '.$table->numero)
@section('admin_subtitle', 'Mets à jour la capacité et la disponibilité de cette table.')

@section('content')
    <div class="card shadow-sm border-0">
        <div class="card-body p-4">
            <form method="POST" action="{{ route('admin.tables.update', $table) }}" class="row g-4">
                @csrf
                @method('PUT')

                <div class="col-md-4">
                    <label for="numero" class="form-label">Numero</label>
                    <input id="numero" type="text" name="numero" value="{{ old('numero', $table->numero) }}" class="form-control @error('numero') is-invalid @enderror" maxlength="10" required>
                    @error('numero')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-4">
                    <label for="places" class="form-label">Capacite</label>
                    <input id="places" type="number" name="places" value="{{ old('places', $table->places) }}" class="form-control @error('places') is-invalid @enderror" min="1" max="30" required>
                    @error('places')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-4">
                    <label for="disponible" class="form-label">Disponibilite</label>
                    <select id="disponible" name="disponible" class="form-select @error('disponible') is-invalid @enderror" required>
                        <option value="1" @selected(old('disponible', (string) (int) $table->disponible) === '1')>Disponible</option>
                        <option value="0" @selected(old('disponible', (string) (int) $table->disponible) === '0')>Inactive</option>
                    </select>
                    @error('disponible')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-12 d-flex gap-2 flex-wrap">
                    <button type="submit" class="btn btn-primary">Mettre à jour</button>
                    <a href="{{ route('admin.tables.index') }}" class="btn btn-outline-secondary">Retour</a>
                </div>
            </form>
        </div>
    </div>
@endsection