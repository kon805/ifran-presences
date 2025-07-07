@extends('layouts.app')
@section('content')
<div class="max-w-4xl mx-auto py-6">
    <h2 class="text-2xl font-bold mb-4">Ajouter un cours à l'emploi du temps</h2>
    <form method="POST" action="{{ route('emploi-du-temps.store') }}">
        @csrf

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label>Classe</label>
                <select name="classe_id" class="w-full p-2 border rounded">
                    @foreach($classes as $classe)
                        <option value="{{ $classe->id }}">{{ $classe->nom }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label>Professeur</label>
                <select name="professeur_id" class="w-full p-2 border rounded">
                    @foreach($professeurs as $professeur)
                        <option value="{{ $professeur->id }}">{{ $professeur->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label>Matière</label>
                <input type="text" name="matiere" class="w-full p-2 border rounded" required>
            </div>

            <div>
                <label>Date</label>
                <input type="date" name="date" class="w-full p-2 border rounded" required>
            </div>

            <div>
                <label>Heure de début</label>
                <input type="time" name="heure_debut" class="w-full p-2 border rounded" required>
            </div>

            <div>
                <label>Heure de fin</label>
                <input type="time" name="heure_fin" class="w-full p-2 border rounded" required>
            </div>
        </div>

        <button type="submit" class="mt-6 bg-indigo-600 text-white px-4 py-2 rounded">Ajouter</button>
    </form>
</div>
@endsection
