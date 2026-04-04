<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class UserController extends Controller
{
    public function index(Request $request): View
    {
        $search = $request->string('search')->toString();
        $role = $request->string('role')->toString();

        $usersQuery = User::query()
            ->search($search)
            ->orderBy('name');

        if (in_array($role, array_keys(User::roles()), true)) {
            $usersQuery->where('role', $role);
        }

        $users = $usersQuery
            ->paginate(10)
            ->withQueryString();

        return view('admin.users.index', [
            'users' => $users,
            'search' => $search,
            'role' => $role,
            'roles' => User::roles(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate($this->storeRules());

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
        ]);

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'Compte créé avec succès.');
    }

    public function show(User $user): View
    {
        return view('admin.users.show', [
            'user' => $user,
        ]);
    }

    public function edit(User $user): View
    {
        return view('admin.users.edit', [
            'user' => $user,
            'roles' => User::roles(),
        ]);
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        $validated = $request->validate($this->updateRules($user));

        $newRole = $validated['role'];

        if ($response = $this->ensureAdminIntegrity($request->user(), $user, $newRole)) {
            return $response;
        }

        $payload = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'role' => $validated['role'],
        ];

        if ($user->email !== $validated['email']) {
            $payload['email_verified_at'] = null;
        }

        if (! empty($validated['password'])) {
            $payload['password'] = Hash::make($validated['password']);
        }

        $user->update($payload);

        return redirect()
            ->route('admin.users.show', $user)
            ->with('success', 'Utilisateur mis à jour avec succès.');
    }

    public function destroy(Request $request, User $user): RedirectResponse
    {
        if ($user->is($request->user())) {
            return back()->withErrors([
                'user' => 'Vous ne pouvez pas supprimer votre propre compte depuis cet écran.',
            ]);
        }

        if ($user->isAdmin() && User::where('role', User::ROLE_ADMIN)->count() === 1) {
            return back()->withErrors([
                'user' => 'Le dernier administrateur ne peut pas être supprimé.',
            ]);
        }

        $user->delete();

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'Utilisateur supprimé avec succès.');
    }

    protected function storeRules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role' => ['required', Rule::in(array_keys(User::roles()))],
        ];
    }

    protected function updateRules(User $user): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', Rule::unique(User::class)->ignore($user->id)],
            'password' => ['nullable', 'confirmed', Rules\Password::defaults()],
            'role' => ['required', Rule::in(array_keys(User::roles()))],
        ];
    }

    protected function ensureAdminIntegrity(User $currentUser, User $targetUser, string $newRole): ?RedirectResponse
    {
        if ($targetUser->is($currentUser) && $newRole !== User::ROLE_ADMIN) {
            return back()->withErrors([
                'role' => 'Vous ne pouvez pas retirer votre propre accès administrateur.',
            ]);
        }

        if ($targetUser->isAdmin() && $newRole !== User::ROLE_ADMIN && User::where('role', User::ROLE_ADMIN)->count() === 1) {
            return back()->withErrors([
                'role' => 'Au moins un administrateur doit conserver cet accès.',
            ]);
        }

        return null;
    }
}