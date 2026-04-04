@extends('layouts.admin')

@section('title', 'Modifier un utilisateur')
@section('admin_title', 'Modifier un utilisateur')
@section('admin_subtitle', 'Mets à jour les informations du compte, le rôle ou le mot de passe.')

@section('content')
    @if($errors->any())
        <div class="alert alert-danger mb-4">{{ $errors->first() }}</div>
    @endif

    <div class="card shadow-sm border-0">
        <div class="card-body p-4">
            <form method="POST" action="{{ route('admin.users.update', $user) }}">
                @csrf
                @method('PUT')
                @include('admin.users._form', ['submitLabel' => 'Enregistrer les modifications'])
            </form>
        </div>
    </div>
@endsection