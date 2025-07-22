@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto py-10 px-4">
    <div class="flex items-center justify-between mb-8">
        <h1 class="text-3xl font-extrabold text-red-700 flex items-center">
            <i class="fa-solid fa-users-class text-red-500 mr-3"></i>
            Liste des classes
        </h1>
        <a href="{{ route('coordinateur.classes.create') }}"
           class="inline-flex items-center bg-gradient-to-r from-red-600 to-pink-500 text-white px-5 py-2 rounded-lg shadow hover:scale-105 transition font-semibold">
            <i class="fa-solid fa-plus mr-2"></i> Créer une classe
        </a>
    </div>
    @if (session('success'))
        <div class="mb-4 p-3 rounded-xl bg-red-50 text-red-800 border border-red-200 shadow flex items-center">
            <i class="fa-solid fa-check-circle mr-2"></i> {{ session('success') }}
        </div>
    @endif

    <div class="bg-gradient-to-br from-red-50 to-white rounded-2xl shadow-lg overflow-x-auto border border-gray-100">
        <table class="min-w-full text-base divide-y divide-gray-200">
            <thead class="bg-red-50">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-bold text-red-700 uppercase tracking-wider">Nom</th>
                    <th class="px-4 py-3 text-left text-xs font-bold text-red-700 uppercase tracking-wider">Étudiants</th>
                    <th class="px-4 py-3 text-center text-xs font-bold text-red-700 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-100">
                @forelse($classes as $classe)
                    <tr class="hover:bg-red-50 transition">
                        <td class="px-4 py-4 font-bold text-red-800 flex items-center">
                            <i class="fa-solid fa-user-group text-red-400 mr-2"></i>
                            <a href="{{ route('coordinateur.classes.show', $classe->id) }}"
                               class="hover:text-red-600 underline">{{ $classe->nom }}</a>
                        </td>
                        <td class="px-4 py-4">
                            @forelse($classe->etudiants as $etudiant)
                                <span class="inline-flex items-center bg-red-100 text-red-800 rounded-full px-3 py-1 text-xs font-semibold mr-1 mb-1 shadow-sm">
                                    <i class="fa-solid fa-user-graduate mr-1"></i>
                                    {{ $etudiant->name }} <span class="text-gray-400">({{ $etudiant->matricule }})</span>
                                </span>
                            @empty
                                <span class="text-gray-400 italic">Aucun étudiant</span>
                            @endforelse
                        </td>
                        <td class="px-4 py-4 text-center">
                            <form action="{{ route('coordinateur.classes.delete', $classe->id) }}" method="POST"
                                  class="inline"
                                  onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette classe ?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        class="inline-flex items-center px-3 py-1 rounded-lg text-xs font-semibold bg-red-100 text-red-700 hover:bg-red-200 transition shadow">
                                    <i class="fa-solid fa-trash mr-1"></i> Supprimer
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="px-4 py-4 text-center text-gray-500 italic">
                            <i class="fa-solid fa-circle-exclamation mr-1"></i>
                            Aucune classe trouvée.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
