@extends('layouts.app')
@section('content')
<div class="max-w-4xl mx-auto py-8 px-2">
    <div class="flex items-center mb-6 gap-4">
        <h2 class="text-3xl font-extrabold text-indigo-800 flex items-center">
            <i class="fa-solid fa-pen-to-square text-indigo-500 mr-3"></i>
            Modifier le cours
        </h2>
        <a href="{{ route('emploi-du-temps.index') }}" class="ml-auto text-sm font-semibold text-indigo-600 hover:text-indigo-900 flex items-center">
            <i class="fa-solid fa-arrow-left mr-2"></i> Retour à la liste
        </a>
    </div>
    <form method="POST" action="{{ route('emploi-du-temps.update', $cours->id) }}" class="bg-white rounded-2xl shadow-lg p-8 border border-gray-100">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block mb-1 text-sm font-semibold text-gray-600">
                    <i class="fa-solid fa-school mr-1 text-indigo-400"></i> Classe
                </label>
                <select name="classe_id" class="w-full px-3 py-2 border rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                    @foreach($classes as $classe)
                        <option value="{{ $classe->id }}" {{ $cours->classe_id == $classe->id ? 'selected' : '' }}>{{ $classe->nom }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block mb-1 text-sm font-semibold text-gray-600">
                    <i class="fa-solid fa-user-tie mr-1 text-indigo-400"></i> Professeur
                </label>
                <select name="professeur_id" class="w-full px-3 py-2 border rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                    @foreach($professeurs as $professeur)
                        <option value="{{ $professeur->id }}" {{ $cours->professeur_id == $professeur->id ? 'selected' : '' }}>{{ $professeur->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block mb-1 text-sm font-semibold text-gray-600">
                    <i class="fa-solid fa-book mr-1 text-indigo-400"></i> Matière
                </label>
                <select name="matiere_id" class="w-full px-3 py-2 border rounded-lg focus:ring-indigo-500 focus:border-indigo-500" required>
                    <option value="">-- Choisir une matière --</option>
                    @foreach($matieres as $matiere)
                        <option value="{{ $matiere->id }}" {{ $cours->matiere_id == $matiere->id ? 'selected' : '' }}>{{ $matiere->nom }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block mb-1 text-sm font-semibold text-gray-600">
                    <i class="fa-regular fa-calendar mr-1 text-indigo-400"></i> Date
                </label>
                <input type="date" name="date" value="{{ $cours->date }}" class="w-full px-3 py-2 border rounded-lg focus:ring-indigo-500 focus:border-indigo-500" required>
            </div>

            <div>
                <label class="block mb-1 text-sm font-semibold text-gray-600">
                    <i class="fa-regular fa-clock mr-1 text-indigo-400"></i> Heure de début
                </label>
                <input type="time" name="heure_debut" value="{{ $cours->heure_debut }}" class="w-full px-3 py-2 border rounded-lg focus:ring-indigo-500 focus:border-indigo-500" required>
            </div>

            <div>
                <label class="block mb-1 text-sm font-semibold text-gray-600">
                    <i class="fa-regular fa-clock mr-1 text-indigo-400"></i> Heure de fin
                </label>
                <input type="time" name="heure_fin" value="{{ $cours->heure_fin }}" class="w-full px-3 py-2 border rounded-lg focus:ring-indigo-500 focus:border-indigo-500" required>
            </div>

            <div>
                <label class="block mb-1 text-sm font-semibold text-gray-600">
                    <i class="fa-solid fa-layer-group mr-1 text-indigo-400"></i> Type de cours
                </label>
                <select name="type_cours_id" class="w-full px-3 py-2 border rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                    @foreach($typesCours as $type)
                        <option value="{{ $type->id }}" {{ $cours->types->contains($type->id) ? 'selected' : '' }}>
                            {{ $type->nom }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block mb-1 text-sm font-semibold text-gray-600">
                    <i class="fa-solid fa-check-double mr-1 text-indigo-400"></i> État
                </label>
                <select name="etat" class="w-full px-3 py-2 border rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                    <option value="programmé" {{ $cours->etat == 'programmé' ? 'selected' : '' }}>Programmé</option>
                    <option value="annulé" {{ $cours->etat == 'annulé' ? 'selected' : '' }}>Annulé</option>
                    <option value="reporté" {{ $cours->etat == 'reporté' ? 'selected' : '' }}>Reporté</option>
                </select>
            </div>
        </div>

        <button type="submit" class="mt-8 w-full md:w-auto bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2 rounded-lg shadow font-bold transition">
            <i class="fa-solid fa-save mr-2"></i> Enregistrer les modifications
        </button>
    </form>
</div>
@endsection
