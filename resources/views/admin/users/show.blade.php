@extends('layouts.admin')

@section('title', 'Fiche utilisateur')
@section('admin_title', $user->name)
@section('admin_subtitle', 'Consulte les informations détaillées du compte utilisateur.')

@section('content')
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger">{{ $errors->first() }}</div>
    @endif

    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-start gap-3 flex-wrap mb-4">
                        <div>
                            <h2 class="h3 mb-1">{{ $user->name }}</h2>
                            <div class="text-muted">{{ $user->email }}</div>
                        </div>
                        <span class="badge {{ $user->isAdmin() ? 'text-bg-dark' : 'text-bg-light' }}">{{ $user->roleLabel() }}</span>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="border rounded-4 p-3 bg-light-subtle h-100">
                                <div class="text-muted small">Date de création</div>
                                <div class="fw-semibold">{{ $user->created_at->format('d/m/Y H:i') }}</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="border rounded-4 p-3 bg-light-subtle h-100">
                                <div class="text-muted small">Email vérifié</div>
                                <div class="fw-semibold">{{ $user->email_verified_at ? $user->email_verified_at->format('d/m/Y H:i') : 'Non vérifié' }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body p-4 d-grid gap-2 align-content-start">
                    <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-primary">Modifier l'utilisateur</a>
                    <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">Retour à la liste</a>

                    <form method="POST" action="{{ route('admin.users.destroy', $user) }}" onsubmit="return confirm('Supprimer définitivement cet utilisateur ?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-outline-danger w-100" @disabled(auth()->id() === $user->id)>Supprimer</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection