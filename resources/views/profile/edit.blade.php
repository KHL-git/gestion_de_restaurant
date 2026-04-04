@extends('layouts.public')

@section('title', 'Mon profil | '.config('app.name', 'Restaurant'))

@push('styles')
    <style>
        .public-profile-shell {
            display: grid;
            gap: 24px;
        }

        .public-profile-hero {
            padding: 34px;
        }

        .public-profile-summary {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 16px;
        }

        .public-profile-summary-card {
            padding: 18px;
            border-radius: 22px;
            border: 1px solid var(--public-line);
            background: rgba(255, 255, 255, 0.76);
        }

        .public-profile-summary-card .label {
            font-size: 0.82rem;
            color: var(--public-muted);
            margin-bottom: 6px;
        }

        .public-profile-summary-card .value {
            font-size: 1.1rem;
            font-weight: 800;
        }

        .public-profile-grid {
            display: grid;
            grid-template-columns: 1.1fr 0.9fr;
            gap: 24px;
        }

        .public-profile-stack {
            display: grid;
            gap: 24px;
        }

        .public-profile-section {
            padding: 26px;
        }

        .public-profile-section h3 {
            margin: 0 0 8px;
            font-size: 1.3rem;
        }

        .public-profile-section p {
            margin: 0;
            color: var(--public-muted);
            line-height: 1.7;
        }

        .public-profile-form {
            display: grid;
            gap: 16px;
            margin-top: 20px;
        }

        .public-profile-field {
            display: grid;
            gap: 8px;
        }

        .public-profile-field label {
            font-size: 0.92rem;
            font-weight: 700;
        }

        .public-profile-field input {
            width: 100%;
            border-radius: 18px;
            padding: 14px 16px;
            border: 1px solid rgba(101, 83, 59, 0.16);
            background: rgba(255, 255, 255, 0.84);
            font: inherit;
            color: var(--public-text);
        }

        .public-profile-field input:focus {
            outline: none;
            border-color: rgba(192, 99, 43, 0.42);
            box-shadow: 0 0 0 4px rgba(192, 99, 43, 0.1);
        }

        .public-profile-actions {
            display: flex;
            align-items: center;
            gap: 12px;
            flex-wrap: wrap;
        }

        .public-profile-message {
            font-size: 0.9rem;
            color: var(--public-green);
            font-weight: 700;
        }

        .public-profile-error {
            color: #b42318;
            font-size: 0.9rem;
        }

        .public-profile-danger {
            border-color: rgba(180, 35, 24, 0.18);
            background: linear-gradient(180deg, rgba(255, 244, 242, 0.92) 0%, rgba(255, 250, 243, 0.96) 100%);
        }

        .public-profile-danger-button {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 12px 16px;
            border-radius: 999px;
            background: #b42318;
            color: #fff;
            font-weight: 800;
            border: none;
            cursor: pointer;
        }

        .public-profile-danger-button:hover {
            background: #921d14;
        }

        @media (max-width: 960px) {
            .public-profile-summary,
            .public-profile-grid {
                grid-template-columns: 1fr;
            }

            .public-profile-hero,
            .public-profile-section {
                padding: 24px;
            }
        }
    </style>
@endpush

@section('content')
    <div class="public-profile-shell">
        <section class="public-panel public-profile-hero">
            <div style="display:flex; justify-content:space-between; gap:20px; align-items:flex-start; flex-wrap:wrap;">
                <div>
                    <span class="public-kicker">Profil public</span>
                    <h2 style="font-size: clamp(2rem, 3vw, 3.4rem); margin: 16px 0 10px;">Gère ton compte depuis le layout public.</h2>
                    <p class="public-muted" style="margin: 0; max-width: 62ch; line-height: 1.75;">
                        Mets à jour tes informations, sécurise ton mot de passe et gère ton compte sans revenir au layout Breeze par défaut.
                    </p>
                </div>
                <a href="{{ route('dashboard') }}" class="public-button-secondary">Retour au dashboard</a>
            </div>
        </section>

        <section class="public-profile-summary">
            <div class="public-profile-summary-card">
                <div class="label">Nom</div>
                <div class="value">{{ $user->name }}</div>
            </div>
            <div class="public-profile-summary-card">
                <div class="label">Email</div>
                <div class="value">{{ $user->email }}</div>
            </div>
            <div class="public-profile-summary-card">
                <div class="label">Rôle</div>
                <div class="value">{{ $user->roleLabel() }}</div>
            </div>
        </section>

        <section class="public-profile-grid">
            <div class="public-profile-stack">
                <article class="public-card public-profile-section">
                    <h3>Informations du profil</h3>
                    <p>Modifie ton nom et ton adresse email à partir des données réellement utilisées par l'application.</p>

                    <form id="send-verification" method="POST" action="{{ route('verification.send') }}">
                        @csrf
                    </form>

                    <form method="POST" action="{{ route('profile.update') }}" class="public-profile-form">
                        @csrf
                        @method('PATCH')

                        <div class="public-profile-field">
                            <label for="name">Nom</label>
                            <input id="name" name="name" type="text" value="{{ old('name', $user->name) }}" required autocomplete="name">
                            @if($errors->get('name'))
                                <div class="public-profile-error">{{ $errors->first('name') }}</div>
                            @endif
                        </div>

                        <div class="public-profile-field">
                            <label for="email">Email</label>
                            <input id="email" name="email" type="email" value="{{ old('email', $user->email) }}" required autocomplete="username">
                            @if($errors->get('email'))
                                <div class="public-profile-error">{{ $errors->first('email') }}</div>
                            @endif

                            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                                <div class="public-muted" style="font-size: 0.92rem;">
                                    Ton adresse email n'est pas vérifiée.
                                    <button form="send-verification" style="background:none; border:none; padding:0; color: var(--public-accent); font-weight:700; cursor:pointer;">Renvoyer l'email de vérification</button>
                                </div>

                                @if (session('status') === 'verification-link-sent')
                                    <div class="public-profile-message">Un nouveau lien de vérification a été envoyé.</div>
                                @endif
                            @endif
                        </div>

                        <div class="public-profile-actions">
                            <button type="submit" class="public-button">Enregistrer</button>
                            @if (session('status') === 'profile-updated')
                                <div class="public-profile-message">Profil mis à jour.</div>
                            @endif
                        </div>
                    </form>
                </article>

                <article class="public-card public-profile-section">
                    <h3>Mot de passe</h3>
                    <p>Choisis un mot de passe plus sûr sans quitter l'espace public.</p>

                    <form method="POST" action="{{ route('password.update') }}" class="public-profile-form">
                        @csrf
                        @method('PUT')

                        <div class="public-profile-field">
                            <label for="current_password">Mot de passe actuel</label>
                            <input id="current_password" name="current_password" type="password" autocomplete="current-password">
                            @if($errors->updatePassword->get('current_password'))
                                <div class="public-profile-error">{{ $errors->updatePassword->first('current_password') }}</div>
                            @endif
                        </div>

                        <div class="public-profile-field">
                            <label for="password">Nouveau mot de passe</label>
                            <input id="password" name="password" type="password" autocomplete="new-password">
                            @if($errors->updatePassword->get('password'))
                                <div class="public-profile-error">{{ $errors->updatePassword->first('password') }}</div>
                            @endif
                        </div>

                        <div class="public-profile-field">
                            <label for="password_confirmation">Confirmer le mot de passe</label>
                            <input id="password_confirmation" name="password_confirmation" type="password" autocomplete="new-password">
                            @if($errors->updatePassword->get('password_confirmation'))
                                <div class="public-profile-error">{{ $errors->updatePassword->first('password_confirmation') }}</div>
                            @endif
                        </div>

                        <div class="public-profile-actions">
                            <button type="submit" class="public-button">Mettre à jour</button>
                            @if (session('status') === 'password-updated')
                                <div class="public-profile-message">Mot de passe mis à jour.</div>
                            @endif
                        </div>
                    </form>
                </article>
            </div>

            <article class="public-card public-profile-section public-profile-danger">
                <h3>Supprimer le compte</h3>
                <p>Cette action est définitive. Saisis ton mot de passe pour confirmer la suppression de ton compte.</p>

                <form method="POST" action="{{ route('profile.destroy') }}" class="public-profile-form">
                    @csrf
                    @method('DELETE')

                    <div class="public-profile-field">
                        <label for="delete_password">Mot de passe</label>
                        <input id="delete_password" name="password" type="password" autocomplete="current-password">
                        @if($errors->userDeletion->get('password'))
                            <div class="public-profile-error">{{ $errors->userDeletion->first('password') }}</div>
                        @endif
                    </div>

                    <div class="public-profile-actions">
                        <button type="submit" class="public-profile-danger-button">Supprimer mon compte</button>
                    </div>
                </form>
            </article>
        </section>
    </div>
@endsection
