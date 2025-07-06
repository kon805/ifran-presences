@extends('layouts.app')

@section('content')
<div class="container mx-auto py-8">
    <h1 class="text-2xl font-bold mb-4">Détails de la classe</h1>
    <div class="mb-4">
        <strong>Nom :</strong> {{ $classe->nom }}
    </div>
    <div class="mb-4">
        <strong>Étudiants :</strong>
        <ul class="list-disc ml-6">
            @forelse($classe->etudiants as $etudiant)
                <li>{{ $etudiant->name }} ({{ $etudiant->matricule }})</li>
            @empty
                <li>Aucun étudiant dans cette classe.</li>
            @endforelse
        </ul>
    </div>
    <a href="{{ route('coordinateur.classes.edit', $classe->id) }}" class="bg-yellow-500 text-white px-4 py-2 rounded">Modifier la classe</a>
    <a href="{{ route('coordinateur.classes.index') }}" class="ml-2 text-blue-600 underline">Retour à la liste</a>
</div>
@endsection
