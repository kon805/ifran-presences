@extends('layouts.app')
@section('content')
<div class="max-w-4xl mx-auto py-8">
    <h2 class="text-2xl font-bold mb-4">Liste des classes</h2>
    <a href="{{route('admin.classes.create')}}" class="bg-blue-600 text-white px-4 py-2 rounded mb-4 inline-block">Créer une classe</a>
    @if(session('success'))
        <div class="mb-4 p-2 bg-green-100 text-green-700">{{ session('success') }}</div>
    @endif
    <table class="w-full border text-sm">
        <thead class="bg-gray-100">
            <tr>
                <th class="border px-2 py-2">Nom</th>
                <th class="border px-2 py-2">Coordinateur</th>
                <th class="border px-2 py-2">Nb étudiants</th>
                <th class="border px-2 py-2">Année académique</th>
                <th class="border px-2 py-2">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($classes as $classe)
                <tr>
                    <td class="border px-2 py-2">{{ $classe->nom }}</td>
                    <td class="border px-2 py-2">{{ $classe->coordinateur ? $classe->coordinateur->name : '-' }}</td>
                    <td class="border px-2 py-2">{{ $classe->etudiants->count() }}</td>
                    <td class="border px-2 py-2">{{ $classe->annee_academique }} - {{ $classe->semestre }}</td>
                    <td class="border px-2 py-2">
                        @if($classe->statut === 'en_cours')
                            <a href="{{ route('coordinateur.classes.edit', $classe->id) }}" class="text-blue-600 underline mr-2">Modifier</a>
                            @if($classe->semestre === '1')
                                @if(Auth::user()->role === 'admin')
                                    <form action="{{ route('admin.classes.terminer-semestre', $classe->id) }}" method="POST" style="display:inline"
                                        onsubmit="return confirm('Êtes-vous sûr de vouloir terminer ce semestre ? Les étudiants seront automatiquement migrés vers le semestre 2 et vous ne pourrez plus modifier ce semestre.');">
                                        @csrf
                                        <button type="submit" class="bg-yellow-500 text-white px-2 py-1 rounded mx-1">Terminer le semestre</button>
                                    </form>
                                @elseif(Auth::user()->role === 'coordinateur' && $classe->coordinateur_id === Auth::id())
                                    <form action="{{ route('coordinateur.classes.terminer-semestre', $classe->id) }}" method="POST" style="display:inline"
                                        onsubmit="return confirm('Êtes-vous sûr de vouloir terminer ce semestre ? Les étudiants seront automatiquement migrés vers le semestre 2 et vous ne pourrez plus modifier ce semestre.');">
                                        @csrf
                                        <button type="submit" class="bg-yellow-500 text-white px-2 py-1 rounded mx-1">Terminer le semestre</button>
                                    </form>
                                @endif
                            @endif
                        @else
                            <span class="text-gray-500 italic">Semestre terminé</span>
                        @endif

                        @if(Auth::user()->role === 'admin')
                            <form action="{{ route('admin.classes.delete', $classe->id) }}" method="POST" style="display:inline" onsubmit="return confirm('Supprimer cette classe ?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 underline ml-2">Supprimer</button>
                            </form>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
