@extends('layouts.app')
@section('content')
<div class="max-w-6xl mx-auto py-12 px-4">
    <div class="flex items-center justify-between mb-10">
        <h2 class="text-3xl font-extrabold text-gray-900 flex items-center">
            <span class="inline-flex justify-center items-center w-10 h-10 bg-black rounded-lg shadow mr-3">
                <i class="fa-solid fa-users-gear text-white text-xl"></i>
            </span>
            Liste des utilisateurs
        </h2>
        <a href="{{ route('admin.users.create') }}"
           class="inline-flex items-center bg-black hover:bg-gray-800 text-white px-6 py-2 rounded-lg shadow font-bold transition text-base">
            <i class="fa-solid fa-user-plus mr-2"></i> Créer un utilisateur
        </a>
    </div>
    @if(session('success'))
        <div class="mb-6 p-3 rounded-lg bg-green-100 text-green-800 border border-green-200 shadow flex items-center">
            <i class="fa-solid fa-check-circle mr-2"></i> {{ session('success') }}
        </div>
    @endif
    <div class="bg-white rounded-xl shadow-lg overflow-x-auto border border-gray-100">
        <table class="min-w-full text-sm">
            <thead>
                <tr>
                    <th class="px-4 py-3 text-left font-bold text-black bg-gray-50 border-b">Photo</th>
                    <th class="px-4 py-3 text-left font-bold text-black bg-gray-50 border-b">Nom</th>
                    <th class="px-4 py-3 text-left font-bold text-black bg-gray-50 border-b">Email</th>
                    <th class="px-4 py-3 text-left font-bold text-black bg-gray-50 border-b">Rôle</th>
                    <th class="px-4 py-3 text-left font-bold text-black bg-gray-50 border-b">Matricule</th>
                    <th class="px-4 py-3 text-left font-bold text-black bg-gray-50 border-b">Classe</th>
                    <th class="px-4 py-3 text-center font-bold text-black bg-gray-50 border-b">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                <tr class="hover:bg-black/10 transition">
                    <td class="px-4 py-3">
                        @if($user->photo)
                            <img src="{{ asset('storage/' . $user->photo) }}" alt="Photo de {{ $user->name }}"
                                 class="w-10 h-10 rounded-full object-cover border-2 border-black shadow" />
                        @else
                            <span class="inline-flex w-10 h-10 items-center justify-center rounded-full bg-gray-200 text-gray-500 border border-black">
                                <i class="fa-solid fa-user"></i>
                            </span>
                        @endif
                    </td>
                    <td class="px-4 py-3 font-semibold text-gray-900 flex items-center">
                        <i class="fa-solid fa-user text-black mr-2"></i>
                        {{ $user->name }}
                    </td>
                    <td class="px-4 py-3 text-gray-700">{{ $user->email }}</td>
                    <td class="px-4 py-3">
                        <span class="inline-block rounded-full px-3 py-1 text-xs font-semibold
                            @if($user->role == 'admin') bg-black text-white
                            @elseif($user->role == 'coordinateur') bg-gray-800 text-white
                            @elseif($user->role == 'etudiant') bg-gray-600 text-white
                            @else bg-gray-300 text-black
                            @endif
                        ">
                            {{ ucfirst($user->role) }}
                        </span>
                    </td>
                    <td class="px-4 py-3 text-gray-700">{{ $user->matricule }}</td>
                    <td class="px-4 py-3">
                        <span class="inline-block rounded px-2 py-1 bg-black/10 text-black text-xs font-semibold">
                            {{ $user->classe ? $user->classe->nom : 'Aucune' }}
                        </span>
                    </td>
                    <td class="px-4 py-3 text-center">
                        <a href="{{ route('admin.users.edit', $user->id) }}"
                           class="inline-flex items-center px-3 py-1 rounded text-xs font-semibold bg-gray-800 text-white hover:bg-gray-900 mr-2 shadow-sm transition">
                            <i class="fa-solid fa-pen-to-square mr-1"></i> Modifier
                        </a>
                        <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" class="inline"
                              onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                    class="inline-flex items-center px-3 py-1 rounded text-xs font-semibold bg-red-100 text-red-700 hover:bg-red-200 shadow-sm ml-2 transition">
                                <i class="fa-solid fa-trash mr-1"></i> Supprimer
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="py-8 text-center text-gray-500">Aucun utilisateur trouvé.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-8 flex justify-center">
        {{ $users->links() }}
    </div>
</div>
@endsection
