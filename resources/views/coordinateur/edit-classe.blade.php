@extends('layouts.app')

@section('content')
<div class="container mx-auto py-8">
    <h1 class="text-2xl font-bold mb-4">Modifier la classe</h1>

    <div class="bg-blue-100 border-l-4 border-blue-500 text-blue-700 p-4 mb-4">
        <p class="font-bold">Important :</p>
        <p>Les étudiants que vous sélectionnez seront automatiquement :</p>
        <ul class="list-disc ml-6 mt-2">
            <li>Retirés de leurs classes actuelles (tous semestres)</li>
            <li>Assignés au semestre {{ $classe->semestre }} de cette classe</li>
            <li>Assignés au semestre {{ $classe->semestre == '1' ? '2' : '1' }} de la même année académique ({{ $classe->annee_academique }})</li>
        </ul>
    </div>

    @if ($errors->any())
        <div class="text-red-600 mb-2">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <form action="{{ route('coordinateur.classes.update', $classe->id) }}" method="POST" class="space-y-4">
        @csrf
        @method('PUT')
        @if(Auth::user()->role === 'admin')
        <div>
            <label for="nom" class="block font-medium">Nom de la classe</label>
            <input type="text" name="nom" id="nom" class="border rounded w-full p-2" value="{{ old('nom', $classe->nom) }}" required>
        </div>
        @else
        <div>
            <label for="nom" class="block font-medium">Nom de la classe</label>
            <input type="text" name="nom" id="nom" class="border rounded w-full p-2 bg-gray-100" value="{{ $classe->nom }}" readonly>
        </div>
        @endif
        <div>
            <label class="block font-medium mb-2">Sélectionner les étudiants</label>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-2 max-h-64 overflow-y-auto border p-2 rounded">
                @foreach($etudiants as $etudiant)
                    <label class="flex items-center space-x-2">
                        <input type="checkbox" name="etudiants[]" value="{{ $etudiant->id }}" {{ $classe->etudiants->contains($etudiant->id) ? 'checked' : '' }}>
                        <span>{{ $etudiant->name }} ({{ $etudiant->matricule }})</span>
                    </label>
                @endforeach
            </div>
        </div>
        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Enregistrer</button>
        <a href="{{ route('coordinateur.classes.show', $classe->id) }}" class="ml-2 text-blue-600 underline">Annuler</a>
    </form>
</div>
@endsection
