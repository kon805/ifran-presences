@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <h1 class="text-2xl font-semibold mb-6">Étudiants en risque d'échec - {{ $classe->nom }}</h1>

        <div class="mb-4">
            <a href="{{ route('coordinateur.classes.show', $classe->id) }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                Retour à la classe
            </a>
        </div>

        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
            @if($etudiantsDropped->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full bg-white">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="py-2 px-4 border-b text-left">Étudiant</th>
                                <th class="py-2 px-4 border-b text-left">Email</th>
                                <th class="py-2 px-4 border-b text-left">Matières en risque d'échec</th>
                                <th class="py-2 px-4 border-b text-left">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($etudiantsDropped as $etudiant)
                                <tr>
                                    <td class="py-2 px-4 border-b">{{ $etudiant->name }}</td>
                                    <td class="py-2 px-4 border-b">{{ $etudiant->email }}</td>
                                    <td class="py-2 px-4 border-b">
                                        @php
                                            $matieresDropped = \App\Models\Matiere::whereHas('etudiants', function ($query) use ($etudiant) {
                                                $query->where('users.id', $etudiant->id)
                                                      ->where('matiere_user.dropped', true);
                                            })->get();
                                        @endphp

                                        @if($matieresDropped->count() > 0)
                                            <ul class="list-disc pl-5">
                                                @foreach($matieresDropped as $matiere)
                                                    <li>{{ $matiere->nom }}</li>
                                                @endforeach
                                            </ul>
                                        @else
                                            <span class="text-gray-400">Aucune matière en risque d'échec</span>
                                        @endif
                                    </td>
                                    <td class="py-2 px-4 border-b">
                                        <a href="{{ route('etudiant.presences.index', ['etudiant_id' => $etudiant->id]) }}" class="text-indigo-600 hover:text-indigo-900">
                                            Voir les présences
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="text-gray-600">Aucun étudiant en risque d'échec dans cette classe.</p>
            @endif
        </div>
    </div>
</div>
@endsection
