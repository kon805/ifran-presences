@extends('layouts.app')
@section('content')
<div class="max-w-2xl mx-auto py-8">
    <h2 class="text-2xl font-bold mb-4">Modifier une classe</h2>
    <form action="{{ route('coordinateur.classes.update', $classe->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="mb-4">
            <label for="nom" class="block font-medium">Nom de la classe</label>
            <input type="text" name="nom" id="nom" class="border rounded w-full p-2" value="{{ old('nom', $classe->nom) }}" required>
        </div>
        <div class="mb-4">
            <label for="coordinateur_id" class="block font-medium">Coordinateur</label>
            <select name="coordinateur_id" id="coordinateur_id" class="border rounded w-full p-2">
                <option value="">-- Choisir un coordinateur --</option>
                @foreach($coordinateurs as $coordinateur)
                    <option value="{{ $coordinateur->id }}" @if($classe->coordinateur_id == $coordinateur->id) selected @endif>{{ $coordinateur->name }} ({{ $coordinateur->email }})</option>
                @endforeach
            </select>
        </div>
        <div class="mb-4">
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
        <a href="{{ route('coordinateur.classes.index') }}" class="ml-2 text-blue-600 underline">Annuler</a>
    </form>
</div>
@endsection
