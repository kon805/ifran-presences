@extends('layouts.app')
@section('content')
<div class="max-w-5xl mx-auto py-8">
    <h2 class="text-2xl font-bold mb-4">Étudiants droppés par matière</h2>
    <table class="w-full border text-sm">
        <thead>
            <tr>
                <th>Matière</th>
                <th>Étudiant</th>
                <th>Statut</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach($matieres as $matiere)
                @foreach($matiere->etudiants as $etudiant)
                    @if($etudiant->pivot->dropped)
                    <tr>
                        <td>{{ $matiere->nom }}</td>
                        <td>{{ $etudiant->name }}</td>
                        <td><span class="text-red-600 font-semibold">Droppé</span></td>
                        <td>
                            <span class="text-xs text-gray-500">L'étudiant devra reprendre ce module l'année prochaine.</span>
                        </td>
                    </tr>
                    @endif
                @endforeach
            @endforeach
        </tbody>
    </table>
</div>
@endsection
