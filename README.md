# Gestion de Restaurant

Application web de gestion de restaurant construite avec Laravel. Le projet couvre deux espaces:

- un espace public pour consulter le menu, passer une commande et effectuer une reservation
- un back-office administrateur pour gerer les utilisateurs, le menu, les ventes, les tables, les commandes et les reservations

## Fonctionnalites principales

- authentification des utilisateurs
- separation des roles `client` et `admin`
- gestion CRUD des utilisateurs par l'administrateur
- gestion du menu avec categories, prix et disponibilite
- tunnel de commande client avec historique
- reservation de table avec selection de 1 a 2 plats
- gestion des tables disponibles pour les reservations
- suivi admin des commandes et reservations
- tableau de bord admin avec statistiques et export PDF

## Stack technique

- PHP 8.2+
- Laravel 12
- Blade
- Vite
- Tailwind CSS
- Alpine.js
- Bootstrap 5 pour certaines vues admin
- SQLite par defaut via `.env.example` mais le projet peut etre adapte a MySQL

## Dependances du projet

### Dependances PHP principales

Issues de [composer.json](/home/bureau/Documents/Projets/gestion_de_restaurant/composer.json):

- `laravel/framework` ^12.0
- `laravel/tinker` ^2.10.1
- `barryvdh/laravel-dompdf` ^3.1

### Dependances PHP de developpement

- `laravel/breeze` ^2.4
- `laravel/pail` ^1.2.2
- `laravel/pint` ^1.24
- `laravel/sail` ^1.41
- `pestphp/pest` ^3.8
- `pestphp/pest-plugin-laravel` ^3.2
- `fakerphp/faker` ^1.23
- `mockery/mockery` ^1.6
- `nunomaduro/collision` ^8.6

### Dependances front-end

Issues de [package.json](/home/bureau/Documents/Projets/gestion_de_restaurant/package.json):

- `vite`
- `laravel-vite-plugin`
- `tailwindcss`
- `@tailwindcss/forms`
- `@tailwindcss/vite`
- `alpinejs`
- `axios`
- `autoprefixer`
- `postcss`
- `concurrently`

## Prerequis machine

Avant installation, verifier la presence de:

- PHP 8.2 ou plus recent
- Composer 2+
- Node.js 20.19+ ou 22.12+ recommande
- npm 10+ recommande
- SQLite 3 ou un serveur MySQL/MariaDB si vous changez la configuration `.env`

### Extensions PHP recommandees

Pour eviter les blocages Laravel classiques:

- `bcmath`
- `ctype`
- `fileinfo`
- `json`
- `mbstring`
- `openssl`
- `pdo`
- `pdo_sqlite` ou `pdo_mysql`
- `tokenizer`
- `xml`

## Installation rapide

Depuis la racine du projet:

```bash
composer run setup
```

Ce script fait deja les operations suivantes:

```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate --force
npm install
npm run build
```

## Installation manuelle detaillee

Si vous preferez executer les etapes une par une:

```bash
composer install
cp .env.example .env
php artisan key:generate
npm install
```

### Base de donnees

Le projet est configure par defaut pour SQLite dans [.env.example](/home/bureau/Documents/Projets/gestion_de_restaurant/.env.example):

```env
DB_CONNECTION=sqlite
```

Deux options:

1. Utiliser SQLite

```bash
touch database/database.sqlite
php artisan migrate
```

2. Utiliser MySQL ou MariaDB

Modifier le fichier `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=gestion_de_restaurant
DB_USERNAME=root
DB_PASSWORD=
```

Puis lancer:

```bash
php artisan migrate
```

### Lien de stockage

Le projet utilise des images pour les plats. Il faut donc creer le lien public vers le stockage:

```bash
php artisan storage:link
```

### Compilation front-end

Pour le developpement:

```bash
npm run dev
```

Pour une compilation de production:

```bash
npm run build
```

Si Laravel affiche une erreur `Vite manifest not found`, cela signifie generalement que les assets front n'ont pas encore ete compiles. Lancez `npm run build` pour generer `public/build/manifest.json`, ou `npm run dev` si vous travaillez en mode developpement.

## Lancement du projet

### Methode simple

Terminal 1:

```bash
php artisan serve
```

Terminal 2:

```bash
npm run dev
```

### Methode tout-en-un

Le script suivant lance le serveur Laravel, le listener de queue, les logs Laravel Pail et Vite:

```bash
composer run dev
```

## Comptes et acces

### Compte client

L'inscription publique cree uniquement des comptes `client`.

### Compte administrateur

Pour creer un premier administrateur:

```bash
php artisan admin:create
```

La commande demandera:

- le nom
- l'email
- le mot de passe

## Donnees initiales utiles

Pour que certaines fonctionnalites soient operationnelles, penser a configurer:

1. au moins un compte administrateur
2. au moins une table disponible dans l'administration
3. au moins un ou deux plats disponibles dans le menu

Sans tables actives, les reservations publiques ne peuvent pas etre validees.

## Scripts utiles

### Composer

- `composer run setup` : installation complete du projet
- `composer run dev` : lance l'environnement de developpement complet
- `composer run test` : lance les tests Laravel

### Artisan

- `php artisan migrate` : applique les migrations
- `php artisan migrate:fresh --seed` : reconstruit la base avec seeding
- `php artisan view:clear` : vide le cache des vues Blade
- `php artisan config:clear` : vide le cache de configuration
- `php artisan cache:clear` : vide le cache applicatif
- `php artisan route:clear` : vide le cache des routes
- `php artisan storage:link` : cree le lien public vers le stockage
- `php artisan admin:create` : cree un administrateur initial

### npm

- `npm run dev` : mode developpement Vite
- `npm run build` : build de production

## Workflow recommande pour les collaborateurs

Apres un `git pull`, executer en fonction des changements recuperes:

### Si `composer.json` ou `composer.lock` ont change

```bash
composer install
```

### Si `package.json` ou `package-lock.json` ont change

```bash
npm install
```

### Si de nouvelles migrations ont ete ajoutees

```bash
php artisan migrate
```

### Si des vues Blade ou la configuration semblent incoherentes

```bash
php artisan view:clear
php artisan config:clear
php artisan cache:clear
```

## Structure fonctionnelle resumee

- `app/Http/Controllers/Admin` : logique back-office
- `app/Http/Controllers` : logique publique et authentification
- `app/Models` : modeles Eloquent
- `resources/views/admin` : vues administrateur
- `resources/views/layouts` : layouts public et admin
- `resources/views/menu`, `orders`, `reservations`, `profile` : vues publiques
- `routes/web.php` : routes web principales
- `routes/console.php` : commandes Artisan custom, notamment `admin:create`

## Notes de fonctionnement

- les reservations publiques demandent une table disponible et 1 a 2 plats
- les commandes et reservations disposent d'un suivi client et admin
- les images des plats necessitent `php artisan storage:link`
- l'export PDF du dashboard admin repose sur `barryvdh/laravel-dompdf`

## Depannage rapide

### Page blanche ou styles absents

```bash
npm install
npm run dev
```

### Erreur de migration ou base non a jour

```bash
php artisan migrate
```

### Les images ne s'affichent pas

```bash
php artisan storage:link
```

### Une reservation ne peut pas etre creee

Verifier:

1. qu'au moins une table est disponible dans l'administration
2. qu'au moins un plat est disponible
3. que la date choisie est dans le futur
4. qu'il n'y a pas deja un conflit sur le creneau de la table

## Collaboration

Avant de pousser vos changements:

```bash
php artisan view:clear
php artisan config:clear
php artisan cache:clear
npm run build
```

Puis verifier que l'application demarre correctement.

## Auteur

Projet de gestion de restaurant base sur Laravel, maintenu pour un usage collaboratif en equipe.
