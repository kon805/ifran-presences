@extends('layouts.app')
@section('content')
<div class="max-w-4xl mx-auto py-8">
    <h2 class="text-2xl font-bold mb-4">Mes matières</h2>
    <table class="w-full border text-sm">
        <thead>
            <tr>
                <th>Matière</th>
                <th>Statut</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach($matieres as $matiere)
            <tr>
                <td>{{ $matiere->nom }}</td>
                <td>
                    @if($matiere->etudiants->isNotEmpty())
                        @php $pivot = $matiere->etudiants->first()->pivot; // L'étudiant actuel est le seul chargé dans la relation @endphp
                        @if($pivot->dropped)
                            <span class="text-red-600 font-semibold">Droppé (présence &lt; 70%)</span>
                        @else
                            <span class="text-green-600 font-semibold">Autorisé</span>
                        @endif
                    @else
                        <span class="text-gray-600">Statut non disponible</span>
                    @endif
                </td>
                <td>
                    @if($matiere->etudiants->isNotEmpty())
                        @php $pivot = $matiere->etudiants->first()->pivot; // L'étudiant actuel est le seul chargé dans la relation @endphp
                        @if($pivot->dropped)
                            <span class="text-xs text-gray-500">Vous devrez reprendre ce module .</span>
                        @else
                            <a href="{{ route('etudiant.matieres.show', $matiere) }}" class="text-blue-600 hover:text-blue-800">
                                Voir les détails
                            </a>
                        @endif
                    @else
                        <span class="text-xs text-gray-500">-</span>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
