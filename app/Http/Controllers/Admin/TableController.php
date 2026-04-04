<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Table;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class TableController extends Controller
{
    public function index(Request $request): View
    {
        $search = trim((string) $request->query('search', ''));

        $tables = Table::query()
            ->withCount(['orders', 'reservations'])
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($builder) use ($search) {
                    $builder
                        ->where('numero', 'like', "%{$search}%")
                        ->orWhere('places', $search)
                        ->orWhere('disponible', filter_var($search, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE));
                });
            })
            ->orderBy('numero')
            ->paginate(12)
            ->withQueryString();

        return view('admin.tables.index', [
            'tables' => $tables,
            'search' => $search,
        ]);
    }

    public function create(): View
    {
        return view('admin.tables.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validatePayload($request);

        Table::query()->create($validated);

        return redirect()
            ->route('admin.tables.index')
            ->with('success', 'La table a ete creee avec succes.');
    }

    public function edit(Table $table): View
    {
        return view('admin.tables.edit', [
            'table' => $table,
        ]);
    }

    public function update(Request $request, Table $table): RedirectResponse
    {
        $validated = $this->validatePayload($request, $table);

        $table->update($validated);

        return redirect()
            ->route('admin.tables.index')
            ->with('success', 'La table a ete mise a jour avec succes.');
    }

    public function destroy(Table $table): RedirectResponse
    {
        if ($table->orders()->exists() || $table->reservations()->exists()) {
            return back()->withErrors([
                'table' => 'Cette table ne peut pas etre supprimee car elle est liee a des commandes ou reservations.',
            ]);
        }

        $table->delete();

        return redirect()
            ->route('admin.tables.index')
            ->with('success', 'La table a ete supprimee avec succes.');
    }

    private function validatePayload(Request $request, ?Table $table = null): array
    {
        return $request->validate([
            'numero' => [
                'required',
                'string',
                'max:10',
                Rule::unique('tables', 'numero')->ignore($table),
            ],
            'places' => ['required', 'integer', 'min:1', 'max:30'],
            'disponible' => ['required', 'boolean'],
        ]);
    }
}