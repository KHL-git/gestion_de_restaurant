@extends('layouts.admin')

@section('title', 'Modifier un plat')
@section('admin_title', 'Modifier un plat')
@section('admin_subtitle', 'Ajuste les informations, le prix ou la disponibilité d’un plat existant.')

@section('content')
    <div class="card shadow-sm border-0">
        <div class="card-body p-4">
            <form method="POST" action="{{ route('admin.menus.update', $menu) }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                @include('admin.menus._form', ['submitLabel' => 'Enregistrer les modifications'])
            </form>
        </div>
    </div>
@endsection