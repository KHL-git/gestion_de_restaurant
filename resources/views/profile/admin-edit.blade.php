@extends('layouts.admin')

@section('title', 'Profil administrateur')
@section('admin_title', 'Mon profil')
@section('admin_subtitle', 'Gère les informations du compte administrateur, le mot de passe et la suppression du compte.')

@push('styles')
    <style>
        .admin-profile-page .profile-section {
            border: 1px solid rgba(223, 212, 194, 0.9);
            border-radius: 24px;
            background: linear-gradient(180deg, rgba(255, 253, 248, 0.96) 0%, #fff 100%);
            box-shadow: 0 18px 42px rgba(30, 42, 58, 0.08);
        }

        .admin-profile-page .profile-section .card-body {
            padding: 2rem;
        }

        .admin-profile-page .profile-section section {
            max-width: 760px;
        }

        .admin-profile-page .profile-section header {
            margin-bottom: 1.25rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid rgba(223, 212, 194, 0.8);
        }

        .admin-profile-page .profile-section header h2,
        .admin-profile-page .profile-section .text-gray-900,
        .admin-profile-page .profile-section .dark\:text-gray-100 {
            color: #1f2933 !important;
        }

        .admin-profile-page .profile-section header p,
        .admin-profile-page .profile-section .text-gray-600,
        .admin-profile-page .profile-section .text-gray-700,
        .admin-profile-page .profile-section .text-gray-800,
        .admin-profile-page .profile-section .dark\:text-gray-300,
        .admin-profile-page .profile-section .dark\:text-gray-400,
        .admin-profile-page .profile-section .dark\:text-gray-200 {
            color: #5b6672 !important;
        }

        .admin-profile-page .profile-section input[type="text"],
        .admin-profile-page .profile-section input[type="email"],
        .admin-profile-page .profile-section input[type="password"] {
            width: 100%;
            background: #fffefb !important;
            color: #1f2933 !important;
            border: 1px solid #d7ccb9 !important;
            border-radius: 14px;
            min-height: 50px;
            padding: 0.85rem 1rem;
            box-shadow: inset 0 1px 2px rgba(31, 41, 51, 0.04);
        }

        .admin-profile-page .profile-section input:focus {
            border-color: #b7791f !important;
            box-shadow: 0 0 0 0.18rem rgba(183, 121, 31, 0.18) !important;
            outline: none;
        }

        .admin-profile-page .profile-section .text-red-600,
        .admin-profile-page .profile-section .dark\:text-red-400 {
            color: #c2410c !important;
        }

        .admin-profile-page .profile-section .inline-flex.items-center {
            border-radius: 12px;
            min-height: 42px;
            padding-inline: 1rem;
            font-weight: 700;
            letter-spacing: 0.04em;
        }

        .admin-profile-page .profile-section button[type="submit"] {
            background: linear-gradient(135deg, #b7791f 0%, #8c5a14 100%) !important;
            color: #fff !important;
            border: none !important;
        }

        .admin-profile-page .profile-section button[type="submit"]:hover {
            background: linear-gradient(135deg, #8c5a14 0%, #72480f 100%) !important;
        }

        .admin-profile-page .profile-section .ms-3 {
            margin-left: 0.75rem;
        }

        .admin-profile-page .profile-summary {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        .admin-profile-page .profile-summary-card {
            padding: 1.2rem 1.3rem;
            border-radius: 20px;
            background: linear-gradient(180deg, #fff9ef 0%, #f7eedf 100%);
            border: 1px solid rgba(223, 212, 194, 0.9);
        }

        .admin-profile-page .profile-summary-card .label {
            font-size: 0.82rem;
            color: #7a6a55;
            margin-bottom: 0.4rem;
        }

        .admin-profile-page .profile-summary-card .value {
            font-size: 1.05rem;
            font-weight: 700;
            color: #1f2933;
        }

        @media (max-width: 991.98px) {
            .admin-profile-page .profile-summary {
                grid-template-columns: 1fr;
            }

            .admin-profile-page .profile-section .card-body {
                padding: 1.25rem;
            }
        }
    </style>
@endpush

@section('content')
    <div class="admin-profile-page">
        <div class="profile-summary">
            <div class="profile-summary-card">
                <div class="label">Compte</div>
                <div class="value">{{ $user->name }}</div>
            </div>
            <div class="profile-summary-card">
                <div class="label">Email</div>
                <div class="value">{{ $user->email }}</div>
            </div>
            <div class="profile-summary-card">
                <div class="label">Rôle</div>
                <div class="value">Administrateur</div>
            </div>
        </div>

        <div class="row g-4">
        <div class="col-12">
            <div class="card shadow-sm border-0 profile-section">
                <div class="card-body p-4">
                    <div>
                        @include('profile.partials.update-profile-information-form')
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12">
            <div class="card shadow-sm border-0 profile-section">
                <div class="card-body p-4">
                    <div>
                        @include('profile.partials.update-password-form')
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12">
            <div class="card shadow-sm border-0 profile-section">
                <div class="card-body p-4">
                    <div>
                        @include('profile.partials.delete-user-form')
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
@endsection