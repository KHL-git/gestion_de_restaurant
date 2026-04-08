@extends('layouts.public')

@section('title', 'Accueil | '.config('app.name', 'Restaurant'))


@section('content')
    <!-- Image de fond floutée pour l'ambiance -->
    <div class="fixed inset-0 -z-10 min-h-screen">
        <img src="https://images.unsplash.com/photo-1504674900247-0877df9cc836?auto=format&fit=crop&w=1200&q=80" alt="Ambiance restaurant" class="w-full h-full object-cover blur-sm brightness-90 min-h-screen" />
        <div class="absolute inset-0 bg-gradient-to-br from-white/80 to-orange-100/60 min-h-screen"></div>
    </div>

    <section class="min-h-screen flex items-center justify-center">
        <div class="max-w-6xl w-full mx-auto px-4 py-16 md:py-24 grid md:grid-cols-2 gap-12 items-center">
            <!-- Bloc de gauche : titre, slogan, boutons -->
            <div>
                <!-- Slogan -->
                <span class="inline-block bg-orange-100 text-orange-700 rounded-full px-4 py-1 text-xs font-semibold mb-4 shadow-sm">Cuisine vivante • carte connectée</span>
                <!-- Titre principal -->
                <h1 class="text-4xl md:text-5xl font-extrabold text-gray-900 mb-4 drop-shadow-lg">Savourez l’instant, découvrez nos saveurs et vivez une expérience culinaire unique</h1>
                <!-- Sous-titre -->
                <p class="text-lg text-gray-700 mb-8 max-w-xl"></p>
                <!-- Boutons d'action -->
                <div class="flex flex-wrap gap-4">
                    <a href="{{ route('menu.index') }}" class="bg-orange-500 hover:bg-orange-600 text-white font-semibold px-6 py-3 rounded-lg shadow transition-all duration-200">Découvrir la carte</a>
                    @auth
                        <a href="{{ route('dashboard') }}" class="bg-white border border-orange-400 text-orange-700 hover:bg-orange-50 font-semibold px-6 py-3 rounded-lg shadow transition-all duration-200">Espace personnel</a>
                        <a href="{{ route('reservations.create') }}" class="bg-white border border-green-400 text-green-700 hover:bg-green-50 font-semibold px-6 py-3 rounded-lg shadow transition-all duration-200">Réserver une table</a>
                    @else
                        <a href="{{ route('login') }}" class="bg-white border border-gray-400 text-gray-700 hover:bg-gray-50 font-semibold px-6 py-3 rounded-lg shadow transition-all duration-200">Connexion</a>
                    @endauth
                </div>
            </div>

            <!-- Bloc de droite : carte d'infos -->
            <div class="relative">
                <div class="absolute -right-10 -bottom-10 w-48 h-48 bg-orange-200 rounded-full opacity-30 blur-2xl"></div>
                <div class="relative z-10 bg-white/90 rounded-3xl shadow-xl p-8 flex flex-col gap-6">
                    <div class="flex justify-between items-center">
                        <span class="font-bold text-lg text-gray-800">Aperçu rapide</span>
                        <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-xs font-semibold">Service actif</span>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div class="bg-orange-50 rounded-xl p-4 flex flex-col items-center shadow">
                            <span class="text-xs text-gray-500 mb-1">Nombre de plats</span>
                            <span class="text-2xl font-extrabold text-orange-700">{{ $availableMenuCount }}</span>
                        </div>
                        <div class="bg-gray-900 rounded-xl p-4 flex flex-col items-center shadow">
                            <span class="text-xs text-gray-300 mb-1">Catégories au menu</span>
                            <span class="text-xl font-bold text-white">{{ $availableCategoryCount }}</span>
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div class="bg-white rounded-xl p-4 shadow flex flex-col items-center">
                            <span class="text-xs text-gray-400">Navigation</span>
                            <span class="font-semibold text-gray-700">Accueil, carte, espace perso</span>
                        </div>
                        <div class="bg-white rounded-xl p-4 shadow flex flex-col items-center">
                            <span class="text-xs text-gray-400">Compte</span>
                            <span class="font-semibold text-gray-700">Gérer mon profil</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <section class="public-grid max-w-6xl mx-auto px-4 pb-12" style="grid-template-columns: 1fr 1fr; margin-bottom: 24px;">
        <article class="public-card">
            <h3 style="margin: 0 0 14px; font-size: 1.2rem;">Modules publics actuellement actifs</h3>
            <div class="public-grid">
                <div class="public-card" style="padding: 16px; background: rgba(255,255,255,0.76);">
                    <div class="public-muted" style="font-size: 0.82rem;">Menu public</div>
                    <strong>Consultation des plats disponibles</strong>
                </div>
                <div class="public-card" style="padding: 16px; background: rgba(255,255,255,0.76);">
                    <div class="public-muted" style="font-size: 0.82rem;">Espace utilisateur</div>
                    <strong>Connexion, dashboard et profil</strong>
                </div>
            </div>
        </article>

        <article class="public-card">
            <h3 style="margin: 0 0 14px; font-size: 1.2rem;">Aperçu du menu</h3>
            <div class="public-grid">
                @forelse($featuredMenus as $menu)
                    <div class="public-card" style="padding: 16px; background: rgba(255,255,255,0.76); display:flex; justify-content:space-between; gap:14px; align-items:flex-start;">
                        <div>
                            <div class="public-muted" style="font-size: 0.82rem;">{{ $menu->categorie }}</div>
                            <strong>{{ $menu->nom }}</strong>
                        </div>
                        <strong style="white-space: nowrap;">{{ $menu->formattedPrice() }}</strong>
                    </div>
                @empty
                    <div class="public-card" style="padding: 16px; background: rgba(255,255,255,0.76);">
                        <strong>Aucun plat disponible pour le moment.</strong>
                    </div>
                @endforelse
            </div>
        </article>
    </section>

    <style>
        @media (max-width: 960px) {
            .public-panel > div,
            .public-grid {
                grid-template-columns: 1fr !important;
            }
        }
    </style>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const images = document.querySelectorAll('#slider-track img');
        const track = document.getElementById('slider-track');
        const prevBtn = document.getElementById('prevBtn');
        const nextBtn = document.getElementById('nextBtn');
        let current = 0;
        let interval = null;

        function updateSlider(index) {
            track.style.transform = `translateX(-${index * 100}%)`;
        }

        function showNext() {
            current = (current + 1) % images.length;
            updateSlider(current);
        }
        function showPrev() {
            current = (current - 1 + images.length) % images.length;
            updateSlider(current);
        }
        function startAutoplay() {
            interval = setInterval(showNext, 5000);
        }
        function stopAutoplay() {
            clearInterval(interval);
        }

        prevBtn.addEventListener('click', () => {
            stopAutoplay();
            showPrev();
            startAutoplay();
        });
        nextBtn.addEventListener('click', () => {
            stopAutoplay();
            showNext();
            startAutoplay();
        });

        document.getElementById('slider').addEventListener('mouseenter', stopAutoplay);
        document.getElementById('slider').addEventListener('mouseleave', startAutoplay);

        updateSlider(current);
        startAutoplay();
    });
    </script>
@endsection
