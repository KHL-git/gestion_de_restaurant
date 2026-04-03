<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion | {{ config('app.name', 'Restaurant') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(120deg, #232946 0%, #3b3b5b 100%);
        }
        .login-main-card {
            border-radius: 2rem;
            box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.15);
            background: #fff;
            overflow: hidden;
            max-width: 700px;
        }
        .login-card {
            background: #fff;
            padding: 2.5rem 2rem;
            border-radius: 0 2rem 2rem 0;
            min-width: 0;
        }
        .login-motivation-card {
            background: linear-gradient(135deg, #6366f1 0%, #60a5fa 100%);
            color: #fff;
            padding: 2.5rem 2rem;
            border-radius: 2rem 0 0 2rem;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-width: 220px;
        }
        .login-logo {
            width: 60px;
            filter: drop-shadow(0 2px 8px #e0e7ff);
        }
    </style>
</head>
<body>
<div class="container d-flex align-items-center justify-content-center min-vh-100">
    <div class="card shadow-sm border-0 p-4" style="max-width: 370px; width: 100%; border-radius: 1.5rem; background: #fff;">
        <div class="text-center mb-4">
            <img src="https://img.icons8.com/ios-filled/100/restaurant.png" alt="Logo" style="width: 48px; filter: drop-shadow(0 2px 8px #e0e7ff);">
            <h2 class="fw-bold mb-1" style="font-size:2rem;">Connexion</h2>
            <p class="text-muted mb-0" style="font-size:1rem;">Accédez à votre espace</p>
        </div>
        @if (session('status'))
            <div class="alert alert-info">{{ session('status') }}</div>
        @endif
        <form method="POST" action="{{ route('login') }}" autocomplete="off">
            @csrf
            <div class="mb-3">
                <input id="email" type="email" class="form-control form-control-lg bg-light border-0 rounded-3 @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" placeholder="Adresse e-mail">
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <input id="password" type="password" class="form-control form-control-lg bg-light border-0 rounded-3 @error('password') is-invalid @enderror" name="password" required autocomplete="current-password" placeholder="Mot de passe">
                @error('password')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div class="form-check">
                    <input type="checkbox" class="form-check-input" id="remember_me" name="remember">
                    <label class="form-check-label small" for="remember_me">Se souvenir de moi</label>
                </div>
                @if (Route::has('password.request'))
                    <a class="small text-decoration-none" href="{{ route('password.request') }}">Mot de passe oublié ?</a>
                @endif
            </div>
            <button type="submit" class="btn btn-primary w-100 fw-bold py-2 rounded-3" style="font-size:1.1rem;">Se connecter</button>
        </form>
        <div class="text-center mt-3">
            <span class="text-muted small">Pas encore de compte ?</span>
            <a href="{{ route('register') }}" class="ms-1 small">Créer un compte</a>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
