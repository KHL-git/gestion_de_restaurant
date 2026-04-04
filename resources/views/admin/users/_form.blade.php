<div class="row g-3">
    <div class="col-md-6">
        <label for="name" class="form-label">Nom</label>
        <input id="name" name="name" type="text" value="{{ old('name', $user->name ?? '') }}" class="form-control @error('name') is-invalid @enderror" required>
        @error('name')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        <label for="email" class="form-label">Email</label>
        <input id="email" name="email" type="email" value="{{ old('email', $user->email ?? '') }}" class="form-control @error('email') is-invalid @enderror" required>
        @error('email')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-4">
        <label for="role" class="form-label">Rôle</label>
        <select id="role" name="role" class="form-select @error('role') is-invalid @enderror" required>
            @foreach($roles as $value => $label)
                <option value="{{ $value }}" @selected(old('role', $user->role ?? \App\Models\User::ROLE_CLIENT) === $value)>{{ $label }}</option>
            @endforeach
        </select>
        @error('role')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-4">
        <label for="password" class="form-label">{{ isset($user) ? 'Nouveau mot de passe' : 'Mot de passe' }}</label>
        <input id="password" name="password" type="password" class="form-control @error('password') is-invalid @enderror" {{ isset($user) ? '' : 'required' }}>
        <div class="form-text">{{ isset($user) ? 'Laisse vide pour conserver le mot de passe actuel.' : 'Choisis un mot de passe sécurisé.' }}</div>
        @error('password')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-4">
        <label for="password_confirmation" class="form-label">Confirmation</label>
        <input id="password_confirmation" name="password_confirmation" type="password" class="form-control" {{ isset($user) ? '' : 'required' }}>
    </div>
</div>

<div class="d-flex gap-2 mt-4">
    <button type="submit" class="btn btn-primary">{{ $submitLabel }}</button>
    <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">Annuler</a>
</div>