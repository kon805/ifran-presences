@extends('layouts.app')
@section('content')
<div class="max-w-5xl mx-auto py-10 px-4">
    <div class="flex items-center justify-between mb-8">
        <h2 class="text-3xl font-extrabold text-cyan-800 flex items-center">
            <i class="fa-solid fa-users-gear text-cyan-500 mr-3"></i>
            Liste des utilisateurs
        </h2>
        <a href="{{ route('admin.users.create') }}"
           class="inline-flex items-center bg-cyan-600 hover:bg-cyan-700 text-white px-5 py-2 rounded-lg shadow font-semibold transition">
            <i class="fa-solid fa-user-plus mr-2"></i> Créer un utilisateur
        </a>
    </div>
    @if(session('success'))
        <div class="mb-4 p-3 rounded-xl bg-green-50 text-green-800 border border-green-200 shadow flex items-center">
            <i class="fa-solid fa-check-circle mr-2"></i> {{ session('success') }}
        </div>
    @endif
    <div class="bg-gradient-to-br from-cyan-50 to-white rounded-xl shadow-lg overflow-x-auto border border-gray-100">
        <table class="min-w-full text-sm divide-y divide-gray-200">
            <thead>
                <tr>
                    <th class="px-3 py-3 text-left font-bold text-cyan-700 bg-white border-b border-gray-200">Nom</th>
                    <th class="px-3 py-3 text-left font-bold text-cyan-700 bg-white border-b border-gray-200">Email</th>
                    <th class="px-3 py-3 text-left font-bold text-cyan-700 bg-white border-b border-gray-200">Rôle</th>
                    <th class="px-3 py-3 text-left font-bold text-cyan-700 bg-white border-b border-gray-200">Matricule</th>
                    <th class="px-3 py-3 text-left font-bold text-cyan-700 bg-white border-b border-gray-200">Classe</th>
                    <th class="px-3 py-3 text-center font-bold text-cyan-700 bg-white border-b border-gray-200">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                    <tr class="hover:bg-cyan-50 transition">
                        <td class="px-3 py-3 font-bold text-cyan-800 flex items-center">
                            <i class="fa-solid fa-user text-cyan-400 mr-2"></i>
                            {{ $user->name }}
                        </td>
                        <td class="px-3 py-3 font-semibold text-cyan-700">{{ $user->email }}</td>
                        <td class="px-3 py-3">
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold
                                @if($user->role == 'admin') bg-indigo-100 text-indigo-800
                                @elseif($user->role == 'coordinateur') bg-blue-100 text-blue-800
                                @elseif($user->role == 'etudiant') bg-green-100 text-green-800
                                @else bg-gray-100 text-gray-800
                                @endif
                            ">
                                <i class="fa-solid fa-id-badge mr-1"></i>
                                {{ ucfirst($user->role) }}
                            </span>
                        </td>
                        <td class="px-3 py-3">{{ $user->matricule }}</td>
                        <td class="px-3 py-3">
                            <span class="inline-flex items-center px-2 py-0.5 rounded bg-cyan-100 text-cyan-800 text-xs font-semibold">
                                <i class="fa-solid fa-school mr-1"></i>
                                {{ $user->classe ? $user->classe->nom : 'Aucune' }}
                            </span>
                        </td>
                        <td class="px-3 py-3 text-center">
                            <a href="{{ route('admin.users.edit', $user->id) }}"
                               class="inline-flex items-center px-3 py-1 rounded-lg text-xs font-semibold bg-blue-100 text-blue-700 hover:bg-blue-200 mr-2 shadow">
                                <i class="fa-solid fa-pen-to-square mr-1"></i> Modifier
                            </a>
                            <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" class="inline"
                                  onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        class="inline-flex items-center px-3 py-1 rounded-lg text-xs font-semibold bg-red-100 text-red-700 hover:bg-red-200 shadow ml-2">
                                    <i class="fa-solid fa-trash mr-1"></i> Supprimer
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="mt-6 flex justify-center">
        {{ $users->links() }}
    </div>
</div>
@endsection
