<?php

use App\Models\User;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('admin:create', function () {
    $name = $this->ask('Nom de l\'administrateur');
    $email = $this->ask('Email de l\'administrateur');
    $password = $this->secret('Mot de passe');

    if (blank($name) || blank($email) || blank($password)) {
        $this->error('Tous les champs sont obligatoires.');

        return self::FAILURE;
    }

    if (User::where('email', $email)->exists()) {
        $this->error('Un utilisateur avec cet email existe deja.');

        return self::FAILURE;
    }

    User::create([
        'name' => $name,
        'email' => $email,
        'password' => $password,
        'role' => User::ROLE_ADMIN,
    ]);

    $this->info('Compte administrateur cree avec succes.');

    return self::SUCCESS;
})->purpose('Creer un compte administrateur initial');
