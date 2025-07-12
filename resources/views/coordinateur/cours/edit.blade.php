@extends('layouts.app')
@section('content')
<div class="max-w-4xl mx-auto py-6">
    <h2 class="text-2xl font-bold mb-4">Modifier le cours</h2>
    <form method="POST" action="{{ route('emploi-du-temps.update', $cours->id) }}">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label>Classe</label>
                <select name="classe_id" class="w-full p-2 border rounded">
                    @foreach($classes as $classe)
                        <option value="{{ $classe->id }}" {{ $cours->classe_id == $classe->id ? 'selected' : '' }}>{{ $classe->nom }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label>Professeur</label>
                <select name="professeur_id" class="w-full p-2 border rounded">
                    @foreach($professeurs as $professeur)
                        <option value="{{ $professeur->id }}" {{ $cours->professeur_id == $professeur->id ? 'selected' : '' }}>{{ $professeur->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label>Matière</label>
                <input type="text" name="matiere" value="{{ $cours->matiere }}" class="w-full p-2 border rounded" required>
            </div>

            <div>
                <label>Date</label>
                <input type="date" name="date" value="{{ $cours->date }}" class="w-full p-2 border rounded" required>
            </div>

            <div>
                <label>Heure de début</label>
                <input type="time" name="heure_debut" value="{{ $cours->heure_debut }}" class="w-full p-2 border rounded" required>
            </div>

            <div>
                <label>Heure de fin</label>
                <input type="time" name="heure_fin" value="{{ $cours->heure_fin }}" class="w-full p-2 border rounded" required>
            </div>

            <div>
                <label>Type de cours</label>
                <select name="type_cours_id" class="w-full p-2 border rounded">
                    @foreach($typesCours as $type)
                        <option value="{{ $type->id }}" {{ $cours->types->contains($type->id) ? 'selected' : '' }}>
                            {{ $type->nom }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label>État</label>
                <select name="etat" class="w-full p-2 border rounded">
                    <option value="programmé" {{ $cours->etat == 'programmé' ? 'selected' : '' }}>Programmé</option>
                    <option value="annulé" {{ $cours->etat == 'annulé' ? 'selected' : '' }}>Annulé</option>
                    <option value="reporté" {{ $cours->etat == 'reporté' ? 'selected' : '' }}>Reporté</option>
                </select>
            </div>
        </div>

        <button type="submit" class="mt-6 bg-indigo-600 text-white px-4 py-2 rounded">Enregistrer les modifications</button>
    </form>
</div>
@endsection
