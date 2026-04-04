@extends('layouts.admin')

@section('title', 'Nouvelle vente')
@section('admin_title', 'Enregistrer une vente')
@section('admin_subtitle', 'Saisis rapidement une transaction et conserve un historique exploitable.')

@section('content')
    <div class="card shadow-sm border-0">
        <div class="card-body">
            <h2 class="h4 mb-3">Nouvelle vente</h2>
            <form method="POST" action="{{ route('admin.sales.store') }}">
                @csrf
                @include('admin.sales._form', ['submitLabel' => 'Enregistrer la vente'])
            </form>
        </div>
    </div>
@endsection