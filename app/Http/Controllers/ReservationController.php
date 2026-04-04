<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\Reservation;
use App\Models\Table;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ReservationController extends Controller
{
    public function index(Request $request): View
    {
        $reservations = $request->user()
            ->reservations()
            ->with(['table', 'menus'])
            ->latest('date_reservation')
            ->paginate(10)
            ->withQueryString();

        return view('reservations.index', [
            'reservations' => $reservations,
        ]);
    }

    public function create(Request $request): View
    {
        $selectedMenuIds = Menu::query()
            ->where('disponible', true)
            ->when($request->filled('menu_id'), fn ($query) => $query->whereKey($request->integer('menu_id')))
            ->pluck('id')
            ->all();

        return view('reservations.create', [
            'tables' => Table::query()->where('disponible', true)->orderBy('numero')->get(),
            'menus' => Menu::query()->where('disponible', true)->orderBy('categorie')->orderBy('nom')->get(),
            'selectedMenuIds' => $selectedMenuIds,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'table_id' => ['required', 'exists:tables,id'],
            'date_reservation' => ['required', 'date', 'after:now'],
            'nombre_personnes' => ['required', 'integer', 'min:1'],
            'menu_ids' => ['required', 'array', 'min:1', 'max:2'],
            'menu_ids.*' => ['required', 'integer', 'distinct', Rule::exists('menus', 'id')->where('disponible', true)],
            'notes' => ['nullable', 'string', 'max:1000'],
        ]);

        $table = Table::query()->findOrFail($validated['table_id']);
        $reservationDate = Carbon::parse($validated['date_reservation']);

        if (! $table->disponible) {
            return back()->withErrors(['table_id' => 'Cette table n est pas disponible actuellement.'])->withInput();
        }

        if ((int) $validated['nombre_personnes'] > $table->places) {
            return back()->withErrors(['nombre_personnes' => 'Le nombre de personnes depasse la capacite de la table selectionnee.'])->withInput();
        }

        $conflict = Reservation::query()
            ->where('table_id', $table->id)
            ->where('statut', '!=', Reservation::STATUS_CANCELLED)
            ->whereBetween('date_reservation', [
                $reservationDate->copy()->subHours(2),
                $reservationDate->copy()->addHours(2),
            ])
            ->exists();

        if ($conflict) {
            return back()->withErrors(['date_reservation' => 'Cette table est deja reservee sur ce creneau.'])->withInput();
        }

        DB::transaction(function () use ($request, $table, $reservationDate, $validated) {
            $reservation = $request->user()->reservations()->create([
                'table_id' => $table->id,
                'date_reservation' => $reservationDate,
                'nombre_personnes' => $validated['nombre_personnes'],
                'statut' => Reservation::STATUS_PENDING,
                'notes' => $validated['notes'] ?? null,
            ]);

            $reservation->menus()->sync($validated['menu_ids']);
        });

        return redirect()
            ->route('reservations.index')
            ->with('success', 'Votre reservation a ete enregistree avec succes.');
    }

    public function show(Request $request, Reservation $reservation): View
    {
        $this->ensureOwnership($request, $reservation);
        $reservation->load(['table', 'menus']);

        return view('reservations.show', [
            'reservation' => $reservation,
        ]);
    }

    public function cancel(Request $request, Reservation $reservation): RedirectResponse
    {
        $this->ensureOwnership($request, $reservation);

        if ($reservation->statut !== Reservation::STATUS_PENDING) {
            return back()->withErrors(['reservation' => 'Seules les reservations en attente peuvent etre annulees.']);
        }

        $reservation->update(['statut' => Reservation::STATUS_CANCELLED]);

        return back()->with('success', 'La reservation a ete annulee.');
    }

    private function ensureOwnership(Request $request, Reservation $reservation): void
    {
        throw_if($reservation->user_id !== $request->user()->id, AuthorizationException::class);
    }
}