@extends('layouts.admin')

@section('title', 'Dashboard admin')
@section('admin_title', 'Tableau de bord administrateur')
@section('admin_subtitle', 'Accède rapidement aux zones sensibles de l’administration sans repasser par le layout principal.')

@push('styles')
    <style>
        .stats-card .stats-label {
            color: #6b7280;
            font-size: 0.82rem;
            margin-bottom: 0.45rem;
            text-transform: uppercase;
            letter-spacing: 0.06em;
        }

        .stats-card .stats-value {
            font-size: 1.9rem;
            font-weight: 700;
            line-height: 1.1;
        }

        .stats-card .stats-meta {
            color: #6b7280;
            font-size: 0.9rem;
            margin-top: 0.65rem;
        }

        .chart-toolbar .btn.active {
            background-color: var(--admin-accent);
            border-color: var(--admin-accent);
            color: #fff;
        }

        .chart-stage {
            position: relative;
            min-height: 360px;
        }

        .chart-stage.chart-stage-sm {
            min-height: 320px;
        }

        .chart-summary {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 1rem;
            margin-top: 1rem;
        }

        .chart-summary-item {
            background: #fffaf2;
            border: 1px solid #ead9bd;
            border-radius: 18px;
            padding: 1rem;
        }

        .chart-summary-item strong {
            display: block;
            font-size: 1.15rem;
        }

        @media (max-width: 991.98px) {
            .chart-summary {
                grid-template-columns: 1fr;
            }
        }
    </style>
@endpush

@section('content')
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center gap-3 flex-wrap mb-3">
                <div>
                    <h2 class="h4 mb-1">Periode d analyse</h2>
                    <p class="text-muted mb-0">{{ $selectedPeriod['label'] }}</p>
                </div>
                <a href="{{ route('admin.dashboard.export-pdf', ['date_from' => $selectedPeriod['from'], 'date_to' => $selectedPeriod['to']]) }}" class="btn btn-outline-dark">Exporter le PDF</a>
            </div>

            <form method="GET" action="{{ route('admin.dashboard') }}" class="row g-3 align-items-end">
                <div class="col-md-4 col-lg-3">
                    <label for="dashboard-date-from" class="form-label">Date de debut</label>
                    <input id="dashboard-date-from" type="date" name="date_from" value="{{ $selectedPeriod['from'] }}" class="form-control">
                </div>
                <div class="col-md-4 col-lg-3">
                    <label for="dashboard-date-to" class="form-label">Date de fin</label>
                    <input id="dashboard-date-to" type="date" name="date_to" value="{{ $selectedPeriod['to'] }}" class="form-control">
                </div>
                <div class="col-md-4 col-lg-6 d-flex gap-2 flex-wrap">
                    <button type="submit" class="btn btn-primary">Appliquer la periode</button>
                    <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary">Revenir au tableau par defaut</a>
                </div>
            </form>
        </div>
    </div>

<div class="row g-4 mb-4">
        <!-- Profil -->
        <div class="col-md-6 col-lg-3">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body text-center">
                    <span class="fs-1">👤</span>
                    <h5 class="card-title mt-2">Mon Profil</h5>
                    <a href="{{ route('profile.edit') }}" class="btn btn-outline-primary btn-sm mt-2">Gérer mon profil</a>
                </div>
            </div>
        </div>
        <!-- Menu -->
        <div class="col-md-6 col-lg-3">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body text-center">
                    <span class="fs-1">🍽️</span>
                    <h5 class="card-title mt-2">Gestion du menu</h5>
                    <a href="{{ route('admin.menus.index') }}" class="btn btn-outline-primary btn-sm mt-2">Gérer les plats</a>
                </div>
            </div>
        </div>
        <!-- Clients/Admins -->
        <div class="col-md-6 col-lg-3">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body text-center">
                    <span class="fs-1">🧑‍🤝‍🧑</span>
                    <h5 class="card-title mt-2">Utilisateurs</h5>
                    <a href="{{ route('admin.users.index') }}" class="btn btn-outline-primary btn-sm mt-2">Gérer les utilisateurs</a>
                </div>
            </div>
        </div>
        <!-- Ventes -->
        <div class="col-md-6 col-lg-3">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body text-center">
                    <span class="fs-1">💰</span>
                    <h5 class="card-title mt-2">Ventes</h5>
                    <a href="{{ route('admin.sales.index') }}" class="btn btn-outline-primary btn-sm mt-2">Gerer les ventes</a>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-md-6 col-lg-6">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body text-center">
                    <span class="fs-1">🧾</span>
                    <h5 class="card-title mt-2">Commandes clients</h5>
                    <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-primary btn-sm mt-2">Gerer les commandes</a>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-lg-6">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body text-center">
                    <span class="fs-1">📅</span>
                    <h5 class="card-title mt-2">Reservations clients</h5>
                    <a href="{{ route('admin.reservations.index') }}" class="btn btn-outline-primary btn-sm mt-2">Gerer les reservations</a>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-md-6 col-xl-3">
            <div class="card shadow-sm border-0 h-100 stats-card">
                <div class="card-body">
                    <div class="stats-label">Chiffre d affaires sur la periode</div>
                    <div class="stats-value">{{ number_format($salesStats['filteredRevenue'], 0, ',', ' ') }} FCFA</div>
                    <div class="stats-meta">{{ $salesStats['filteredSalesCount'] }} vente{{ $salesStats['filteredSalesCount'] > 1 ? 's' : '' }} sur la periode</div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-xl-3">
            <div class="card shadow-sm border-0 h-100 stats-card">
                <div class="card-body">
                    <div class="stats-label">Clients sur la periode</div>
                    <div class="stats-value">{{ $salesStats['filteredCustomersCount'] }}</div>
                    <div class="stats-meta">Clients identifies par compte ou nom libre</div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-xl-3">
            <div class="card shadow-sm border-0 h-100 stats-card">
                <div class="card-body">
                    <div class="stats-label">Articles vendus sur la periode</div>
                    <div class="stats-value">{{ $salesStats['filteredItemsSoldCount'] }}</div>
                    <div class="stats-meta">Quantite totale ecoulee sur les ventes payees</div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-xl-3">
            <div class="card shadow-sm border-0 h-100 stats-card">
                <div class="card-body">
                    <div class="stats-label">Panier moyen sur la periode</div>
                    <div class="stats-value">{{ number_format($salesStats['filteredAverageBasket'], 0, ',', ' ') }} FCFA</div>
                    <div class="stats-meta">Moyenne calculee sur les ventes payees filtrees</div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-md-4">
            <div class="card shadow-sm border-0 h-100 stats-card">
                <div class="card-body">
                    <div class="stats-label">Clients enregistres</div>
                    <div class="stats-value">{{ $salesStats['clientsCount'] }}</div>
                    <div class="stats-meta">Base clients globale du restaurant</div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow-sm border-0 h-100 stats-card">
                <div class="card-body">
                    <div class="stats-label">Plats catalogues</div>
                    <div class="stats-value">{{ $salesStats['menusCount'] }}</div>
                    <div class="stats-meta">Elements actuellement presents dans le menu</div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow-sm border-0 h-100 stats-card">
                <div class="card-body">
                    <div class="stats-label">Articles vendus au global</div>
                    <div class="stats-value">{{ $salesStats['itemsSoldCount'] }}</div>
                    <div class="stats-meta">Historique cumule de toutes les ventes payees</div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-xl-8">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center gap-3 flex-wrap mb-4">
                        <div>
                            <h2 class="h4 mb-1">Evolution des ventes</h2>
                            <p class="text-muted mb-0">Visualise les ventes payees sur la periode choisie, par jour ou par mois.</p>
                        </div>
                        <div class="btn-group chart-toolbar" role="group" aria-label="Periode du graphique">
                            <button type="button" class="btn btn-outline-primary active" data-chart-mode="day">Par jour</button>
                            <button type="button" class="btn btn-outline-primary" data-chart-mode="month">Par mois</button>
                        </div>
                    </div>

                    <div class="chart-stage">
                        <canvas id="sales-chart"></canvas>
                    </div>

                    <div class="chart-summary">
                        <div class="chart-summary-item">
                            <span class="text-muted small">Periode visible</span>
                            <strong id="chart-period-label">14 derniers jours</strong>
                        </div>
                        <div class="chart-summary-item">
                            <span class="text-muted small">Chiffre d affaires cumule</span>
                            <strong id="chart-total-revenue">0 FCFA</strong>
                        </div>
                        <div class="chart-summary-item">
                            <span class="text-muted small">Nombre de ventes</span>
                            <strong id="chart-total-count">0</strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center gap-3 flex-wrap mb-4">
                        <div>
                            <h2 class="h4 mb-1">Graphique des plats vendus</h2>
                            <p class="text-muted mb-0">Classement visuel des plats les plus vendus sur la periode.</p>
                        </div>
                    </div>

                    <div class="chart-stage chart-stage-sm">
                        <canvas id="top-menus-chart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-xl-5">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3 gap-3 flex-wrap">
                        <h2 class="h4 mb-0">Plats les plus vendus</h2>
                        <a href="{{ route('admin.sales.index') }}" class="btn btn-outline-primary btn-sm">Voir toutes les ventes</a>
                    </div>

                    <div class="list-group list-group-flush">
                        @forelse($topMenus as $menu)
                            <div class="list-group-item px-0 d-flex justify-content-between align-items-start">
                                <div>
                                    <div class="fw-semibold">{{ $menu->nom }}</div>
                                    <div class="text-muted small">{{ number_format((float) $menu->revenue, 0, ',', ' ') }} FCFA generes</div>
                                </div>
                                <span class="badge text-bg-dark">{{ $menu->quantity_sold }} plat{{ $menu->quantity_sold > 1 ? 's' : '' }}</span>
                            </div>
                        @empty
                            <div class="text-muted">Aucune statistique disponible pour le moment.</div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-7">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body">
                    <h2 class="h4 mb-3">Dernieres ventes</h2>
                    <div class="list-group list-group-flush">
                        @forelse($recentSales as $sale)
                            <div class="list-group-item px-0 d-flex justify-content-between align-items-start gap-3">
                                <div>
                                    <div class="fw-semibold">{{ $sale->reference }} - {{ $sale->customerLabel() }}</div>
                                    <div class="text-muted small">{{ $sale->itemsSummary(3) }} | {{ $sale->sold_at->format('d/m/Y H:i') }}</div>
                                </div>
                                <div class="text-end">
                                    <div class="fw-semibold">{{ $sale->formattedTotal() }}</div>
                                    <span class="badge {{ $sale->status === 'payee' ? 'text-bg-success' : ($sale->status === 'annulee' ? 'text-bg-danger' : 'text-bg-warning') }}">{{ $sale->statusLabel() }}</span>
                                </div>
                            </div>
                        @empty
                            <div class="text-muted">Aucune vente recente disponible.</div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-12">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center gap-3 flex-wrap">
                        <div>
                            <h2 class="h4 mb-1">Compte connecté</h2>
                            <p class="text-muted mb-0">{{ auth()->user()->name }} - {{ auth()->user()->email }}</p>
                        </div>
                        <span class="badge text-bg-dark">Administrateur</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.3/dist/chart.umd.min.js"></script>
    <script>
        (() => {
            const canvas = document.getElementById('sales-chart');
            const topMenusCanvas = document.getElementById('top-menus-chart');
            const toggleButtons = document.querySelectorAll('[data-chart-mode]');

            if (!canvas || !topMenusCanvas || typeof Chart === 'undefined') {
                return;
            }

            const datasets = {
                day: {
                    label: 'Ventes par jour',
                    periodLabel: @json($selectedPeriod['label']),
                    labels: @json($dayChart['labels']),
                    revenues: @json($dayChart['revenues']),
                    counts: @json($dayChart['counts']),
                },
                month: {
                    label: 'Ventes par mois',
                    periodLabel: @json('Vue mensuelle - '.$selectedPeriod['label']),
                    labels: @json($monthChart['labels']),
                    revenues: @json($monthChart['revenues']),
                    counts: @json($monthChart['counts']),
                },
            };

            const periodLabel = document.getElementById('chart-period-label');
            const totalRevenueLabel = document.getElementById('chart-total-revenue');
            const totalCountLabel = document.getElementById('chart-total-count');
            const moneyFormatter = new Intl.NumberFormat('fr-FR');

            const computeSummary = (source) => ({
                revenue: source.revenues.reduce((sum, value) => sum + Number(value || 0), 0),
                count: source.counts.reduce((sum, value) => sum + Number(value || 0), 0),
            });

            const chart = new Chart(canvas, {
                type: 'bar',
                data: {
                    labels: datasets.day.labels,
                    datasets: [
                        {
                            type: 'bar',
                            label: 'Chiffre d affaires',
                            data: datasets.day.revenues,
                            yAxisID: 'yRevenue',
                            backgroundColor: 'rgba(183, 121, 31, 0.75)',
                            borderRadius: 10,
                            maxBarThickness: 34,
                        },
                        {
                            type: 'line',
                            label: 'Nombre de ventes',
                            data: datasets.day.counts,
                            yAxisID: 'yCount',
                            borderColor: '#213246',
                            backgroundColor: '#213246',
                            tension: 0.35,
                            fill: false,
                            pointRadius: 4,
                            pointHoverRadius: 5,
                        },
                    ],
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    interaction: {
                        mode: 'index',
                        intersect: false,
                    },
                    plugins: {
                        legend: {
                            position: 'bottom',
                        },
                        tooltip: {
                            callbacks: {
                                label(context) {
                                    const value = context.raw ?? 0;

                                    if (context.dataset.yAxisID === 'yRevenue') {
                                        return `${context.dataset.label}: ${moneyFormatter.format(value)} FCFA`;
                                    }

                                    return `${context.dataset.label}: ${moneyFormatter.format(value)}`;
                                },
                            },
                        },
                    },
                    scales: {
                        yRevenue: {
                            position: 'left',
                            beginAtZero: true,
                            ticks: {
                                callback(value) {
                                    return moneyFormatter.format(value) + ' FCFA';
                                },
                            },
                        },
                        yCount: {
                            position: 'right',
                            beginAtZero: true,
                            grid: {
                                drawOnChartArea: false,
                            },
                        },
                    },
                },
            });

            new Chart(topMenusCanvas, {
                type: 'bar',
                data: {
                    labels: @json($topMenusChart['labels']),
                    datasets: [
                        {
                            label: 'Quantite vendue',
                            data: @json($topMenusChart['quantities']),
                            backgroundColor: ['#213246', '#38526e', '#4e7398', '#6d91b2', '#91afc8', '#b7cad8', '#d9e3ea'],
                            borderRadius: 10,
                        },
                    ],
                },
                options: {
                    indexAxis: 'y',
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false,
                        },
                        tooltip: {
                            callbacks: {
                                afterLabel(context) {
                                    const revenues = @json($topMenusChart['revenues']);
                                    const revenue = revenues[context.dataIndex] ?? 0;
                                    return 'Revenu: ' + moneyFormatter.format(revenue) + ' FCFA';
                                },
                            },
                        },
                    },
                    scales: {
                        x: {
                            beginAtZero: true,
                        },
                    },
                },
            });

            const updateChart = (mode) => {
                const source = datasets[mode] ?? datasets.day;
                const summary = computeSummary(source);

                chart.data.labels = source.labels;
                chart.data.datasets[0].label = source.label;
                chart.data.datasets[0].data = source.revenues;
                chart.data.datasets[1].data = source.counts;
                chart.update();

                periodLabel.textContent = source.periodLabel;
                totalRevenueLabel.textContent = moneyFormatter.format(summary.revenue) + ' FCFA';
                totalCountLabel.textContent = moneyFormatter.format(summary.count);

                toggleButtons.forEach((button) => {
                    button.classList.toggle('active', button.dataset.chartMode === mode);
                });
            };

            toggleButtons.forEach((button) => {
                button.addEventListener('click', () => updateChart(button.dataset.chartMode));
            });

            updateChart('day');
        })();
    </script>
@endpush
