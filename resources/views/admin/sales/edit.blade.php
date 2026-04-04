@extends('layouts.admin')

@section('title', 'Modifier une vente')
@section('admin_title', 'Modifier une vente')
@section('admin_subtitle', 'Ajuste les informations de transaction sans perdre la reference de suivi.')

@section('content')
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card shadow-sm border-0">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center gap-3 flex-wrap mb-3">
                <div>
                    <h2 class="h4 mb-1">{{ $sale->reference }}</h2>
                    <p class="text-muted mb-0">Reference de suivi conservee automatiquement.</p>
                </div>
                <a href="{{ route('admin.sales.show', $sale) }}" class="btn btn-outline-dark">Voir la fiche</a>
            </div>

            <form method="POST" action="{{ route('admin.sales.update', $sale) }}">
                @csrf
                @method('PUT')
                @include('admin.sales._form', ['submitLabel' => 'Mettre a jour la vente'])
            </form>
        </div>
    </div>
@endsection