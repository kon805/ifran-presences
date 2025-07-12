@extends('layouts.app')
@section('content')
<div class="max-w-6xl mx-auto py-8">
    <h2 class="text-2xl font-bold mb-4">Liste des utilisateurs</h2>
    <a href="{{ route('admin.users.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded mb-4 inline-block">Créer un utilisateur</a>
    @if(session('success'))
        <div class="mb-4 p-2 bg-green-100 text-green-700">{{ session('success') }}</div>
    @endif
    <table class="w-full border text-sm">
        <thead class="bg-gray-100">
            <tr>
                <th class="border px-2 py-2">Nom</th>
                <th class="border px-2 py-2">Email</th>
                <th class="border px-2 py-2">Rôle</th>
                <th class="border px-2 py-2">Matricule</th>
                <th class="border px-2 py-2">Classe</th>
                <th class="border px-2 py-2">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $user)
                <tr>
                    <td class="border px-2 py-2">{{ $user->name }}</td>
                    <td class="border px-2 py-2">{{ $user->email }}</td>
                    <td class="border px-2 py-2">{{ $user->role }}</td>
                    <td class="border px-2 py-2">{{ $user->matricule }}</td>
                    <td class="border px-2 py-2">{{ $user->classe ? $user->classe->nom : 'Aucune' }}</td>
                    <td class="border px-2 py-2">
                        <a href="{{ route('admin.users.edit', $user->id) }}" class="text-blue-600 underline mr-2">Modifier</a>
                        <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" style="display:inline" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 underline ml-2">Supprimer</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
