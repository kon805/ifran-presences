@extends('layouts.app')
@section('content')
<div class="max-w-3xl mx-auto py-8 px-2">
    <h2 class="text-3xl font-extrabold mb-8 text-red-700 flex items-center">
        <i class="fa-solid fa-plus mr-3 text-red-500"></i>
        Ajouter un cours à l'emploi du temps
    </h2>

    @if(session('success'))
        <div class="mb-4 p-3 rounded-lg bg-red-50 text-red-800 border border-red-200 flex items-center shadow-sm">
            <i class="fa-solid fa-check-circle mr-2"></i> {{ session('success') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="mb-4 p-3 rounded-lg bg-red-100 text-red-800 border border-red-300">
            <ul class="list-disc pl-6 space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('emploi-du-temps.store') }}" class="bg-gradient-to-br from-red-50 to-white p-8 rounded-2xl shadow-lg border border-gray-100">
        @csrf
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-red-700 font-semibold mb-1">Classe</label>
                <select name="classe_id" class="w-full p-3 border border-red-200 rounded-lg focus:ring-2 focus:ring-red-200" required>
                    <option value="">-- Choisir une classe --</option>
                    @foreach($classes as $classe)
                        <option value="{{ $classe->id }}">{{ $classe->nom }} (Semestre {{ $classe->semestre }})</option>

                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-red-700 font-semibold mb-1">Professeur</label>
                <select name="professeur_id" class="w-full p-3 border border-red-200 rounded-lg focus:ring-2 focus:ring-red-200" required>
                    <option value="">-- Choisir un professeur --</option>
                    @foreach($professeurs as $professeur)
                        <option value="{{ $professeur->id }}">{{ $professeur->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-red-700 font-semibold mb-1">Matière</label>
                <select name="matiere_id" class="w-full p-3 border border-red-200 rounded-lg focus:ring-2 focus:ring-red-200" required>
                    <option value="">-- Choisir une matière --</option>
                    @foreach($matieres as $matiere)
                        <option value="{{ $matiere->id }}">{{ $matiere->nom }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-red-700 font-semibold mb-1">Type de cours</label>
                <select name="type_cours_id" class="w-full p-3 border border-red-200 rounded-lg focus:ring-2 focus:ring-red-200" required>
                    <option value="">-- Choisir un type de cours --</option>
                    @foreach($types as $type)
                        <option value="{{ $type->id }}">{{ $type->nom }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-red-700 font-semibold mb-1">Date</label>
                <input type="date" name="date" class="w-full p-3 border border-red-200 rounded-lg focus:ring-2 focus:ring-red-200" required>
            </div>

            <div>
                <label class="block text-red-700 font-semibold mb-1">Heure de début</label>
                <input type="time" name="heure_debut" class="w-full p-3 border border-red-200 rounded-lg focus:ring-2 focus:ring-red-200" required>
            </div>

            <div>
                <label class="block text-red-700 font-semibold mb-1">Heure de fin</label>
                <input type="time" name="heure_fin" class="w-full p-3 border border-red-200 rounded-lg focus:ring-2 focus:ring-red-200" required>
            </div>
        </div>

        <button type="submit" class="mt-8 bg-red-600 hover:bg-red-700 text-white px-6 py-3 rounded-lg shadow font-semibold text-lg transition">
            <i class="fa-solid fa-circle-plus mr-2"></i> Ajouter
        </button>
    </form>
</div>
@endsection
