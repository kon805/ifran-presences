@extends('layouts.app')
@section('content')
<div class="max-w-4xl mx-auto py-6">
    <h2 class="text-2xl font-bold mb-4">Présences – {{ $cours->matiere }} ({{ $cours->classe->nom }})</h2>
    <p class="mb-4 text-sm text-gray-600">{{ $cours->date }} – {{ $cours->heure_debut }} à {{ $cours->heure_fin }}<br>Professeur : {{ $cours->professeur->name }}</p>

    <table class="w-full border">
        <thead class="bg-gray-100">
            <tr>
                <th class="border px-2 py-2">Étudiant</th>
                <th class="border px-2 py-2">Statut</th>
            </tr>
        </thead>
        <tbody>
            @foreach($cours->classe->etudiants as $etudiant)
                @php
                    $presence = $cours->presences->where('etudiant_id', $etudiant->id)->first();
                @endphp
                  @if(auth()->user()->role === 'coordinateur')
                        <td class="border px-2 py-2">
                            <a href="{{ route('coordinateur.presences.edit', $cours->id) }}" class="text-blue-600 underline">Ajouter</a>
                        </td>
                    @endif
                <tr>
                    <td class="border px-2 py-2">{{ $etudiant->name }}</td>
                    <td class="border px-2 py-2">
                        @if($presence)
                            <span class="capitalize">{{ $presence->statut }}</span>
                        @else
                            <span class="text-gray-500 italic">Non renseigné</span>
                        @endif
                    </td>

                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
