<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class MenuController extends Controller
{
    public function index(Request $request): View
    {
        $search = $request->string('search')->toString();

        $menus = Menu::query()
            ->search($search)
            ->orderBy('categorie')
            ->orderBy('nom')
            ->paginate(10)
            ->withQueryString();

        return view('admin.menus.index', [
            'menus' => $menus,
            'search' => $search,
        ]);
    }

    public function publicIndex(Request $request): View
    {
        $search = $request->string('search')->toString();
        $category = $request->string('category')->toString();

        $categoryCounts = Menu::query()
            ->where('disponible', true)
            ->selectRaw('categorie, COUNT(*) as total')
            ->groupBy('categorie')
            ->orderBy('categorie')
            ->get();

        $menus = Menu::query()
            ->where('disponible', true)
            ->when($category !== '', fn ($query) => $query->where('categorie', $category))
            ->search($search)
            ->orderBy('categorie')
            ->orderBy('nom')
            ->get()
            ->groupBy(fn (Menu $menu) => $menu->categorie ?: 'Suggestions du chef');

        return view('menu.index', [
            'menus' => $menus,
            'search' => $search,
            'category' => $category,
            'categories' => $categoryCounts,
        ]);
    }

    public function create(): View
    {
        return view('admin.menus.create', [
            'categories' => Menu::categories(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate($this->rules());

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('menus', 'public');
        }

        Menu::create($validated);

        return redirect()
            ->route('admin.menus.index')
            ->with('success', 'Plat créé avec succès.');
    }

    public function show(Menu $menu): View
    {
        return view('admin.menus.show', compact('menu'));
    }

    public function edit(Menu $menu): View
    {
        return view('admin.menus.edit', [
            'menu' => $menu,
            'categories' => Menu::categories(),
        ]);
    }

    public function update(Request $request, Menu $menu): RedirectResponse
    {
        $validated = $request->validate($this->rules());

        if ($request->hasFile('image')) {
            if ($menu->image) {
                Storage::disk('public')->delete($menu->image);
            }

            $validated['image'] = $request->file('image')->store('menus', 'public');
        }

        $menu->update($validated);

        return redirect()
            ->route('admin.menus.index')
            ->with('success', 'Plat mis à jour avec succès.');
    }

    public function destroy(Menu $menu): RedirectResponse
    {
        if ($menu->image) {
            Storage::disk('public')->delete($menu->image);
        }

        $menu->delete();

        return redirect()
            ->route('admin.menus.index')
            ->with('success', 'Plat supprimé avec succès.');
    }

    protected function rules(): array
    {
        return [
            'nom' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'prix' => ['required', 'numeric', 'min:0'],
            'categorie' => ['required', 'in:'.implode(',', array_keys(Menu::categories()))],
            'disponible' => ['required', 'boolean'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ];
    }
}
