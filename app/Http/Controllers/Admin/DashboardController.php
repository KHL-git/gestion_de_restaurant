<?php

namespace App\Http\Controllers\Admin;

use Barryvdh\DomPDF\Facade\Pdf;
use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Models\Sale;
use App\Models\SaleLine;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        return view('admin.dashboard', $this->buildDashboardViewData($request));
    }

    public function exportPdf(Request $request): Response
    {
        $data = $this->buildDashboardViewData($request);

        return Pdf::loadView('admin.dashboard-pdf', $data)
            ->setPaper('a4', 'portrait')
            ->download('statistiques-dashboard-'.$data['selectedPeriod']['filenameSuffix'].'.pdf');
    }

    private function buildDashboardViewData(Request $request): array
    {
        $filters = $request->validate([
            'date_from' => ['nullable', 'date'],
            'date_to' => ['nullable', 'date', 'after_or_equal:date_from'],
        ]);

        $today = Carbon::today();
        $monthStart = Carbon::now()->startOfMonth();
        $selectedStart = isset($filters['date_from'])
            ? Carbon::parse($filters['date_from'])->startOfDay()
            : Carbon::today()->subDays(13)->startOfDay();
        $selectedEnd = isset($filters['date_to'])
            ? Carbon::parse($filters['date_to'])->endOfDay()
            : Carbon::today()->endOfDay();
        $monthRangeStart = $selectedStart->copy()->startOfMonth();
        $monthRangeEnd = $selectedEnd->copy()->endOfMonth();

        $totalRevenue = (float) Sale::query()
            ->where('status', Sale::STATUS_PAID)
            ->sum('total');

        $todayRevenue = (float) Sale::query()
            ->where('status', Sale::STATUS_PAID)
            ->whereDate('sold_at', $today)
            ->sum('total');

        $monthRevenue = (float) Sale::query()
            ->where('status', Sale::STATUS_PAID)
            ->where('sold_at', '>=', $monthStart)
            ->sum('total');

        $salesCount = Sale::query()->count();
        $todaySalesCount = Sale::query()->whereDate('sold_at', $today)->count();
        $pendingSalesCount = Sale::query()->where('status', Sale::STATUS_PENDING)->count();
        $averageBasket = $salesCount > 0 ? $totalRevenue / $salesCount : 0;
        $clientsCount = User::query()->where('role', User::ROLE_CLIENT)->count();
        $menusCount = Menu::query()->count();
        $itemsSoldCount = (int) SaleLine::query()
            ->join('sales', 'sales.id', '=', 'sale_lines.sale_id')
            ->where('sales.status', Sale::STATUS_PAID)
            ->sum('sale_lines.quantity');

        $filteredPaidSales = Sale::query()
            ->where('status', Sale::STATUS_PAID)
            ->whereBetween('sold_at', [$selectedStart, $selectedEnd]);

        $filteredRevenue = (float) (clone $filteredPaidSales)->sum('total');
        $filteredSalesCount = (clone $filteredPaidSales)->count();
        $filteredAverageBasket = $filteredSalesCount > 0 ? $filteredRevenue / $filteredSalesCount : 0;
        $filteredItemsSoldCount = (int) SaleLine::query()
            ->join('sales', 'sales.id', '=', 'sale_lines.sale_id')
            ->where('sales.status', Sale::STATUS_PAID)
            ->whereBetween('sales.sold_at', [$selectedStart, $selectedEnd])
            ->sum('sale_lines.quantity');
        $filteredCustomersCount = (int) Sale::query()
            ->whereBetween('sold_at', [$selectedStart, $selectedEnd])
            ->selectRaw("COUNT(DISTINCT COALESCE(NULLIF(client_name, ''), CAST(user_id AS TEXT), reference)) as aggregate")
            ->value('aggregate');

        $topMenus = SaleLine::query()
            ->select('menus.nom', DB::raw('SUM(sale_lines.quantity) as quantity_sold'), DB::raw('SUM(sale_lines.total) as revenue'))
            ->join('menus', 'menus.id', '=', 'sale_lines.menu_id')
            ->join('sales', 'sales.id', '=', 'sale_lines.sale_id')
            ->where('sales.status', Sale::STATUS_PAID)
            ->whereBetween('sales.sold_at', [$selectedStart, $selectedEnd])
            ->groupBy('menus.nom')
            ->orderByDesc('quantity_sold')
            ->limit(7)
            ->get();

        $recentSales = Sale::query()
            ->with(['lines.menu', 'user'])
            ->whereBetween('sold_at', [$selectedStart, $selectedEnd])
            ->latest('sold_at')
            ->limit(5)
            ->get();

        $dailySales = Sale::query()
            ->select('sold_at', 'total')
            ->where('status', Sale::STATUS_PAID)
            ->whereBetween('sold_at', [$selectedStart, $selectedEnd])
            ->orderBy('sold_at')
            ->get();

        $monthlySales = Sale::query()
            ->select('sold_at', 'total')
            ->where('status', Sale::STATUS_PAID)
            ->whereBetween('sold_at', [$monthRangeStart, $monthRangeEnd])
            ->orderBy('sold_at')
            ->get();

        $dayChart = $this->buildDailyChart($dailySales, $selectedStart, $selectedEnd);
        $monthChart = $this->buildMonthlyChart($monthlySales, $monthRangeStart, $monthRangeEnd);
        $topMenusChart = [
            'labels' => $topMenus->pluck('nom')->all(),
            'quantities' => $topMenus->pluck('quantity_sold')->map(fn ($value) => (int) $value)->all(),
            'revenues' => $topMenus->pluck('revenue')->map(fn ($value) => (float) $value)->all(),
        ];

        return [
            'salesStats' => [
                'totalRevenue' => $totalRevenue,
                'todayRevenue' => $todayRevenue,
                'monthRevenue' => $monthRevenue,
                'salesCount' => $salesCount,
                'todaySalesCount' => $todaySalesCount,
                'pendingSalesCount' => $pendingSalesCount,
                'averageBasket' => $averageBasket,
                'clientsCount' => $clientsCount,
                'menusCount' => $menusCount,
                'itemsSoldCount' => $itemsSoldCount,
                'filteredRevenue' => $filteredRevenue,
                'filteredSalesCount' => $filteredSalesCount,
                'filteredAverageBasket' => $filteredAverageBasket,
                'filteredItemsSoldCount' => $filteredItemsSoldCount,
                'filteredCustomersCount' => $filteredCustomersCount,
            ],
            'topMenus' => $topMenus,
            'recentSales' => $recentSales,
            'dayChart' => $dayChart,
            'monthChart' => $monthChart,
            'topMenusChart' => $topMenusChart,
            'selectedPeriod' => [
                'from' => $selectedStart->toDateString(),
                'to' => $selectedEnd->toDateString(),
                'label' => 'Du '.$selectedStart->translatedFormat('d/m/Y').' au '.$selectedEnd->translatedFormat('d/m/Y'),
                'filenameSuffix' => $selectedStart->format('Ymd').'-'.$selectedEnd->format('Ymd'),
            ],
        ];
    }

    private function buildDailyChart(Collection $sales, Carbon $startDate, Carbon $endDate): array
    {
        $labels = [];
        $revenues = [];
        $counts = [];

        for ($date = $startDate->copy(); $date->lte($endDate); $date->addDay()) {
            $key = $date->toDateString();
            $daySales = $sales->filter(fn (Sale $sale) => $sale->sold_at->toDateString() === $key);

            $labels[] = $date->translatedFormat('d M');
            $revenues[] = (float) $daySales->sum('total');
            $counts[] = $daySales->count();
        }

        return [
            'labels' => $labels,
            'revenues' => $revenues,
            'counts' => $counts,
        ];
    }

    private function buildMonthlyChart(Collection $sales, Carbon $startDate, Carbon $endDate): array
    {
        $labels = [];
        $revenues = [];
        $counts = [];

        for ($date = $startDate->copy(); $date->lte($endDate->copy()->startOfMonth()); $date->addMonth()) {
            $monthKey = $date->format('Y-m');
            $monthSales = $sales->filter(fn (Sale $sale) => $sale->sold_at->format('Y-m') === $monthKey);

            $labels[] = ucfirst($date->translatedFormat('M Y'));
            $revenues[] = (float) $monthSales->sum('total');
            $counts[] = $monthSales->count();
        }

        return [
            'labels' => $labels,
            'revenues' => $revenues,
            'counts' => $counts,
        ];
    }
}