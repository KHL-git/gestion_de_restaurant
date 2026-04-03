@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h1 class="mb-4 fw-bold">Gestion des utilisateurs</h1>
    <table class="table table-hover align-middle bg-white">
        <thead class="table-light">
            <tr>
                <th>Nom</th>
                <th>Email</th>
                <th>Rôle</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $user)
                <tr>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>
                        <form method="POST" action="{{ route('admin.users.updateRole', $user) }}" class="d-flex align-items-center gap-2">
                            @csrf
                            @method('PATCH')
                            <select name="role" class="form-select form-select-sm w-auto">
                                <option value="user" @if($user->role === 'user') selected @endif>Utilisateur</option>
                                <option value="admin" @if($user->role === 'admin') selected @endif>Admin</option>
                            </select>
                            <button type="submit" class="btn btn-primary btn-sm">Enregistrer</button>
                        </form>
                    </td>
                    <td>
                        <!-- Autres actions possibles -->
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
