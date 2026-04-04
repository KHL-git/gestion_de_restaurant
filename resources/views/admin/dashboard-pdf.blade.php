<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title>Statistiques dashboard</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            color: #1f2933;
            font-size: 12px;
            line-height: 1.45;
        }

        h1, h2, h3, p {
            margin: 0;
        }

        .header {
            margin-bottom: 20px;
            border-bottom: 2px solid #d4b483;
            padding-bottom: 12px;
        }

        .header h1 {
            font-size: 24px;
            margin-bottom: 4px;
        }

        .muted {
            color: #6b7280;
        }

        .grid {
            width: 100%;
            border-collapse: collapse;
            margin: 18px 0;
        }

        .grid td, .grid th {
            border: 1px solid #e5dccd;
            padding: 8px;
            vertical-align: top;
        }

        .grid th {
            background: #f8f2e8;
            text-align: left;
        }

        .stats {
            width: 100%;
            border-collapse: separate;
            border-spacing: 10px;
            margin: 16px -10px 10px;
        }

        .stats td {
            width: 50%;
            border: 1px solid #e5dccd;
            background: #fffaf2;
            border-radius: 10px;
            padding: 12px;
        }

        .label {
            display: block;
            font-size: 10px;
            text-transform: uppercase;
            color: #6b7280;
            margin-bottom: 6px;
        }

        .value {
            display: block;
            font-size: 18px;
            font-weight: bold;
        }

        .section {
            margin-top: 18px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Statistiques du tableau de bord</h1>
        <p class="muted">{{ $selectedPeriod['label'] }}</p>
    </div>

    <table class="stats">
        <tr>
            <td>
                <span class="label">Chiffre d affaires sur la periode</span>
                <span class="value">{{ number_format($salesStats['filteredRevenue'], 0, ',', ' ') }} FCFA</span>
            </td>
            <td>
                <span class="label">Nombre de ventes sur la periode</span>
                <span class="value">{{ $salesStats['filteredSalesCount'] }}</span>
            </td>
        </tr>
        <tr>
            <td>
                <span class="label">Clients touches sur la periode</span>
                <span class="value">{{ $salesStats['filteredCustomersCount'] }}</span>
            </td>
            <td>
                <span class="label">Articles vendus sur la periode</span>
                <span class="value">{{ $salesStats['filteredItemsSoldCount'] }}</span>
            </td>
        </tr>
    </table>

    <div class="section">
        <h2>Indicateurs globaux</h2>
        <table class="grid">
            <tr>
                <th>Chiffre d affaires total</th>
                <th>Clients</th>
                <th>Plats</th>
                <th>Ventes en attente</th>
            </tr>
            <tr>
                <td>{{ number_format($salesStats['totalRevenue'], 0, ',', ' ') }} FCFA</td>
                <td>{{ $salesStats['clientsCount'] }}</td>
                <td>{{ $salesStats['menusCount'] }}</td>
                <td>{{ $salesStats['pendingSalesCount'] }}</td>
            </tr>
        </table>
    </div>

    <div class="section">
        <h2>Plats les plus vendus</h2>
        <table class="grid">
            <tr>
                <th>Plat</th>
                <th>Quantite vendue</th>
                <th>Revenu</th>
            </tr>
            @forelse($topMenus as $menu)
                <tr>
                    <td>{{ $menu->nom }}</td>
                    <td>{{ $menu->quantity_sold }}</td>
                    <td>{{ number_format((float) $menu->revenue, 0, ',', ' ') }} FCFA</td>
                </tr>
            @empty
                <tr>
                    <td colspan="3">Aucune donnee disponible pour cette periode.</td>
                </tr>
            @endforelse
        </table>
    </div>

    <div class="section">
        <h2>Dernieres ventes</h2>
        <table class="grid">
            <tr>
                <th>Reference</th>
                <th>Client</th>
                <th>Panier</th>
                <th>Total</th>
                <th>Date</th>
            </tr>
            @forelse($recentSales as $sale)
                <tr>
                    <td>{{ $sale->reference }}</td>
                    <td>{{ $sale->customerLabel() }}</td>
                    <td>{{ $sale->itemsSummary(3) }}</td>
                    <td>{{ $sale->formattedTotal() }}</td>
                    <td>{{ $sale->sold_at->format('d/m/Y H:i') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5">Aucune vente recente sur cette periode.</td>
                </tr>
            @endforelse
        </table>
    </div>
</body>
</html>