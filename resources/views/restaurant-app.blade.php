<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restaurant Manager - Tableau de bord</title>
    <link rel="stylesheet" href="/restaurant-app/css/style.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <!-- Header -->
    <header class="header">
        <nav class="nav container">
            <div class="logo">🍽️ Restaurant Manager</div>
            <ul class="nav-links">
                <li><a href="/restaurant-app/index.html">Tableau de bord</a></li>
                <li><a href="/restaurant-app/menu.html" data-modal="menuModal">Menu</a></li>
                <li><a href="/restaurant-app/clients.html" data-modal="clientsModal">Clients</a></li>
                <li><a href="/restaurant-app/sales.html" data-modal="salesModal">Ventes</a></li>
                <li><a href="/restaurant-app/profile.html">Profil</a></li>
                <li><a href="/restaurant-app/login.html" class="btn btn-danger">Déconnexion</a></li>
            </ul>
        </nav>
    </header>
    <div class="container">
        <!-- Stats -->
        <div class="stats-grid">
            <div class="card stat-card">
                <div class="stat-number" data-stat="total-sales">€24,560</div>
                <div>Chiffre d'affaires</div>
            </div>
            <div class="card stat-card">
                <div class="stat-number" data-stat="total-orders">1,247</div>
                <div>Commandes totales</div>
            </div>
            <div class="card stat-card">
                <div class="stat-number" data-stat="active-clients">89</div>
                <div>Clients actifs</div>
            </div>
            <div class="card stat-card">
                <div class="stat-number" data-stat="popular-dish">Poulet rôti</div>
                <div>Plat populaire</div>
            </div>
        </div>
        <!-- Graphiques -->
        <div class="grid-2" style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem;">
            <div class="card">
                <h3>Ventes par jour</h3>
                <canvas id="salesChart"></canvas>
            </div>
            <div class="card">
                <h3>Plats les plus vendus</h3>
                <canvas id="dishesChart"></canvas>
            </div>
        </div>
        <!-- Actions rapides -->
        <div class="card">
            <h3>Actions rapides</h3>
            <div style="display: flex; gap: 1rem; flex-wrap: wrap;">
                <a href="#" class="btn btn-primary" data-modal="menuModal">Gérer le menu</a>
                <a href="#" class="btn btn-success" data-modal="salesModal">Nouvelle vente</a>
                <a href="#" class="btn btn-warning" data-modal="clientsModal">Nouveau client</a>
            </div>
        </div>
    </div>
    <!-- Modal Menu -->
    <div id="menuModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Gestion du Menu</h2>
            <form data-action="menu">
                <div class="form-group">
                    <label>Nom du plat</label>
                    <input type="text" name="name" required>
                </div>
                <div class="form-group">
                    <label>Prix (€)</label>
                    <input type="number" name="price" step="0.01" required>
                </div>
                <div class="form-group">
                    <label>Catégorie</label>
                    <select name="category">
                        <option>Entrée</option>
                        <option>Plat principal</option>
                        <option>Dessert</option>
                        <option>Boisson</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">Ajouter le plat</button>
            </form>
        </div>
    </div>
    <script src="/restaurant-app/js/script.js"></script>
    <script>
        // Initialiser les graphiques
        const salesCtx = document.getElementById('salesChart').getContext('2d');
        new Chart(salesCtx, {
            type: 'line',
            data: {
                labels: ['Lun', 'Mar', 'Mer', 'Jeu', 'Ven', 'Sam', 'Dim'],
                datasets: [{
                    label: 'Ventes (€)',
                    data: [1200, 1900, 1500, 2300, 2800, 3500, 3200],
                    borderColor: '#667eea',
                    backgroundColor: 'rgba(102, 126, 234, 0.1)',
                    tension: 0.4
                }]
            },
            options: { responsive: true, maintainAspectRatio: false }
        });
        const dishesCtx = document.getElementById('dishesChart').getContext('2d');
        new Chart(dishesCtx, {
            type: 'doughnut',
            data: {
                labels: ['Poulet rôti', 'Pizza', 'Salade', 'Pâtes', 'Burger'],
                datasets: [{
                    data: [35, 25, 20, 15, 5],
                    backgroundColor: ['#667eea', '#764ba2', '#f093fb', '#56ab2f', '#ff416c']
                }]
            },
            options: { responsive: true, maintainAspectRatio: false }
        });
    </script>
</body>
</html>
