@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h1 class="mb-4 fw-bold">Tableau de bord Administrateur</h1>
    <div class="row g-4 mb-4">
        <!-- Profil -->
        <div class="col-md-6 col-lg-3">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body text-center">
                    <span class="fs-1">👤</span>
                    <h5 class="card-title mt-2">Mon Profil</h5>
                    <a href="#" class="btn btn-outline-primary btn-sm mt-2">Gérer mon profil</a>
                </div>
            </div>
        </div>
        <!-- Menu -->
        <div class="col-md-6 col-lg-3">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body text-center">
                    <span class="fs-1">🍽️</span>
                    <h5 class="card-title mt-2">Gestion du menu</h5>
                    <a href="#" class="btn btn-outline-primary btn-sm mt-2">Voir les plats</a>
                </div>
            </div>
        </div>
        <!-- Clients/Admins -->
        <div class="col-md-6 col-lg-3">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body text-center">
                    <span class="fs-1">🧑‍🤝‍🧑</span>
                    <h5 class="card-title mt-2">Utilisateurs</h5>
                    <a href="#" class="btn btn-outline-primary btn-sm mt-2">Gérer les utilisateurs</a>
                </div>
            </div>
        </div>
        <!-- Ventes -->
        <div class="col-md-6 col-lg-3">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body text-center">
                    <span class="fs-1">💰</span>
                    <h5 class="card-title mt-2">Ventes</h5>
                    <a href="#" class="btn btn-outline-primary btn-sm mt-2">Voir les ventes</a>
                </div>
            </div>
        </div>
    </div>
    <!-- Ici tu pourras ajouter des widgets/statistiques supplémentaires -->
</div>
@endsection
