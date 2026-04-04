<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Reservation;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ReservationController extends Controller
{
    public function index(Request $request): View
    {
        $status = (string) $request->query('status', '');
        $search = trim((string) $request->query('search', ''));

        $reservations = Reservation::query()
            ->with(['user', 'table', 'menus'])
            ->when($status !== '', fn ($query) => $query->where('statut', $status))
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($builder) use ($search) {
                    $builder
                        ->where('id', $search)
                        ->orWhere('notes', 'like', "%{$search}%")
                        ->orWhereHas('user', fn ($userQuery) => $userQuery->where('name', 'like', "%{$search}%")->orWhere('email', 'like', "%{$search}%"))
                        ->orWhereHas('table', fn ($tableQuery) => $tableQuery->where('numero', 'like', "%{$search}%"))
                        ->orWhereHas('menus', fn ($menuQuery) => $menuQuery->where('nom', 'like', "%{$search}%"));
                });
            })
            ->latest('date_reservation')
            ->paginate(10)
            ->withQueryString();

        return view('admin.reservations.index', [
            'reservations' => $reservations,
            'status' => $status,
            'search' => $search,
            'statuses' => Reservation::statuses(),
        ]);
    }

    public function show(Reservation $reservation): View
    {
        $reservation->load(['user', 'table', 'menus']);

        return view('admin.reservations.show', [
            'reservation' => $reservation,
            'statuses' => Reservation::statuses(),
        ]);
    }

    public function updateStatus(Request $request, Reservation $reservation): RedirectResponse
    {
        $validated = $request->validate([
            'statut' => ['required', Rule::in(array_keys(Reservation::statuses()))],
        ]);

        $reservation->update(['statut' => $validated['statut']]);

        return back()->with('success', 'Le statut de la reservation a ete mis a jour.');
    }
}