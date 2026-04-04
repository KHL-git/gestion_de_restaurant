@extends('layouts.admin')

@section('title', 'Ajouter un plat')
@section('admin_title', 'Ajouter un plat')
@section('admin_subtitle', 'Crée un nouveau plat et rends-le disponible dans le menu du restaurant.')

@section('content')
    <div class="card shadow-sm border-0">
        <div class="card-body p-4">
            <form method="POST" action="{{ route('admin.menus.store') }}" enctype="multipart/form-data">
                @csrf
                @include('admin.menus._form', ['submitLabel' => 'Créer le plat'])
            </form>
        </div>
    </div>
@endsection