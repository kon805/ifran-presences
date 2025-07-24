@extends('layouts.app')
@section('content')
<div class="max-w-5xl mx-auto py-10 px-4">
    <div class="flex items-center justify-between mb-8">
        <h2 class="text-3xl font-extrabold text-cyan-800 flex items-center">
            <i class="fa-solid fa-table-list text-cyan-500 mr-3 text-2xl"></i>
            Liste des classes
        </h2>
        <a href="{{route('admin.classes.create')}}"
           class="inline-flex items-center bg-cyan-600 hover:bg-cyan-700 text-white px-5 py-2 rounded-lg shadow font-semibold transition">
            <i class="fa-solid fa-plus mr-2"></i> Créer une classe
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
                    <th class="px-3 py-3 text-left font-bold text-cyan-700 bg-white border-b border-gray-200">Coordinateur</th>
                    <th class="px-3 py-3 text-center font-bold text-cyan-700 bg-white border-b border-gray-200">Nb étudiants</th>
                    <th class="px-3 py-3 text-center font-bold text-cyan-700 bg-white border-b border-gray-200">Année académique</th>
                    <th class="px-3 py-3 text-center font-bold text-cyan-700 bg-white border-b border-gray-200">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($classes as $classe)
                    <tr class="hover:bg-cyan-50 transition">
                        <td class="px-3 py-3 font-bold text-cyan-800">
                            <i class="fa-solid fa-user-group text-cyan-400 mr-2"></i>
                            {{ $classe->nom }}
                        </td>
                        <td class="px-3 py-3 font-semibold text-cyan-700">
                            <i class="fa-solid fa-user-tie text-cyan-300 mr-2"></i>
                            {{ $classe->coordinateur ? $classe->coordinateur->name : '-' }}
                        </td>
                        <td class="px-3 py-3 text-center">
                            <span class="inline-flex items-center bg-cyan-100 text-cyan-800 rounded-full px-3 py-1 text-xs font-semibold shadow-sm">
                                <i class="fa-solid fa-user-graduate mr-1"></i>
                                {{ $classe->etudiants->count() }}
                            </span>
                        </td>
                        <td class="px-3 py-3 text-center">
                            <span class="inline-flex items-center bg-blue-100 text-blue-800 rounded-full px-3 py-1 text-xs font-semibold shadow-sm">
                                <i class="fa-solid fa-calendar-days mr-1"></i>
                                {{ $classe->annee_academique }} - S{{ $classe->semestre }}
                            </span>
                        </td>
                        <td class="px-3 py-3 text-center">
                            @if($classe->statut === 'en_cours' && !$classe->semestre_termine)
                                <a href="{{ route('coordinateur.classes.edit', $classe->id) }}"
                                   class="inline-flex items-center px-3 py-1 rounded-lg text-xs font-semibold bg-blue-100 text-blue-700 hover:bg-blue-200 transition mr-2 shadow">
                                    <i class="fa-solid fa-pen-to-square mr-1"></i> Modifier
                                </a>
                                @if($classe->semestre === '1' && !$classe->semestre_termine)
                                    @if(Auth::user()->role === 'admin')
                                        <form action="{{ route('admin.classes.terminer-semestre', $classe->id) }}" method="POST" class="inline"
                                              onsubmit="return confirm('Êtes-vous sûr de vouloir terminer ce semestre ? Les étudiants seront automatiquement migrés vers le semestre 2 et vous ne pourrez plus modifier ce semestre.');">
                                            @csrf
                                            <button type="submit"
                                                    class="inline-flex items-center px-3 py-1 rounded-lg text-xs font-semibold bg-yellow-100 text-yellow-700 hover:bg-yellow-200 transition shadow">
                                                <i class="fa-solid fa-circle-check mr-1"></i> Terminer le semestre
                                            </button>
                                        </form>
                                    @elseif(Auth::user()->role === 'coordinateur' && $classe->coordinateur_id === Auth::id())
                                        <form action="{{ route('coordinateur.classes.terminer-semestre', $classe->id) }}" method="POST" class="inline"
                                              onsubmit="return confirm('Êtes-vous sûr de vouloir terminer ce semestre ? Les étudiants seront automatiquement migrés vers le semestre 2 et vous ne pourrez plus modifier ce semestre.');">
                                            @csrf
                                            <button type="submit"
                                                    class="inline-flex items-center px-3 py-1 rounded-lg text-xs font-semibold bg-yellow-100 text-yellow-700 hover:bg-yellow-200 transition shadow">
                                                <i class="fa-solid fa-circle-check mr-1"></i> Terminer le semestre
                                            </button>
                                        </form>


                                    @else
                                        <span class="inline-flex items-center px-3 py-1 rounded-lg text-xs font-semibold bg-gray-100 text-gray-500 italic shadow">
                                            <i class="fa-solid fa-lock mr-1"></i> Action non autorisée
                                        </span>
                                    @endif
                                @endif
                            @else
                                <span class="inline-flex items-center px-3 py-1 rounded-lg text-xs font-semibold bg-gray-100 text-gray-500 italic shadow">
                                    <i class="fa-solid fa-calendar-check mr-1"></i> Semestre terminé
                                </span>

                                @if(Auth::user()->role === 'admin' || (Auth::user()->role === 'coordinateur' && $classe->coordinateur_id === Auth::id()))
                                <a href="{{ route('coordinateur.classes.dropped-etudiants', $classe->id) }}"
                                   class="inline-flex items-center px-3 py-1 rounded-lg text-xs font-semibold bg-red-100 text-red-700 hover:bg-red-200 transition shadow ml-2">
                                    <i class="fa-solid fa-triangle-exclamation mr-1"></i> Voir étudiants en risque
                                </a>
                                @endif
                            @endif
                            @if(Auth::user()->role === 'admin')
                                <form action="{{ route('admin.classes.delete', $classe->id) }}" method="POST" class="inline"
                                      onsubmit="return confirm('Supprimer cette classe ?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="inline-flex items-center px-3 py-1 rounded-lg text-xs font-semibold bg-red-100 text-red-700 hover:bg-red-200 transition shadow ml-2">
                                        <i class="fa-solid fa-trash mr-1"></i> Supprimer
                                    </button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @endforeach


            </tbody>
        </table>
    </div>
</div>
@endsection
