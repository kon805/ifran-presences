@extends('layouts.app')
@section('content')
<div class="max-w-4xl mx-auto py-6">
    <h2 class="text-2xl font-bold mb-4">Présences – {{ $cours->matiere->nom ?? 'Matière non définie' }} ({{ $cours->classe->nom ?? 'Classe non définie' }})</h2>
    <p class="mb-4 text-sm text-gray-600">{{ $cours->date }} – {{ $cours->heure_debut }} à {{ $cours->heure_fin }}<br>Professeur : {{ $cours->professeur->name ?? 'Non défini' }}</p>

    <table class="w-full border">
        <thead class="bg-gray-100">
            <tr>
                <th class="border px-2 py-2">Étudiant</th>
                <th class="border px-2 py-2">Statut</th>
            </tr>
        </thead>
        <tbody>
            @if($cours->classe && $cours->classe->etudiants)
                @foreach($cours->classe->etudiants as $etudiant)
                    @php
                        $presence = $cours->presences->where('etudiant_id', $etudiant->id)->first();
                    @endphp
                    <tr>
                        <td class="border px-2 py-2">{{ $etudiant->name }}</td>
                        <td class="border px-2 py-2">
                            @if($presence)
                                <span class="capitalize">{{ $presence->statut }}</span>
                            @else
                                @if(auth()->user()->role === 'coordinateur')
                                    <a href="{{ route('coordinateur.presences.edit', $cours->id) }}" class="text-blue-600 underline">Ajouter</a>
                                @else
                                    <span class="text-gray-500 italic">Non renseigné</span>
                                @endif
                            @endif
                        </td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="2" class="border px-2 py-2 text-center text-gray-500 italic">
                        Aucun étudiant trouvé pour cette classe
                    </td>
                </tr>
            @endif
        </tbody>
    </table>
</div>
@endsection
