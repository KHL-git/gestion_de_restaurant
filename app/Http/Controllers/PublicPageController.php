<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\Table;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PublicPageController extends Controller
{
    public function home(): View
    {
        $availableMenus = Menu::query()
            ->where('disponible', true)
            ->orderBy('categorie')
            ->orderBy('nom')
            ->get();

        return view('welcome', [
            'featuredMenus' => $availableMenus->take(4),
            'availableMenuCount' => $availableMenus->count(),
            'availableCategoryCount' => $availableMenus->pluck('categorie')->filter()->unique()->count(),
        ]);
    }

    public function dashboard(Request $request): View|RedirectResponse
    {
        if ($request->user()->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }

        $availableMenus = Menu::query()
            ->where('disponible', true)
            ->latest()
            ->get();

        $selectedMenu = null;

        if ($request->filled('selected_menu')) {
            $selectedMenu = Menu::query()
                ->where('disponible', true)
                ->find($request->integer('selected_menu'));
        }

        return view('dashboard', [
            'availableMenuCount' => $availableMenus->count(),
            'availableCategoryCount' => $availableMenus->pluck('categorie')->filter()->unique()->count(),
            'recentMenus' => $availableMenus->take(3),
            'userOrdersCount' => $request->user()->orders()->count(),
            'userReservationsCount' => $request->user()->reservations()->count(),
            'availableTablesCount' => Table::query()->where('disponible', true)->count(),
            'selectedMenu' => $selectedMenu,
        ]);
    }
}