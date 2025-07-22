@extends('layouts.app')

@section('content')
<div class="max-w-xl mx-auto py-10 px-4">
    <h1 class="text-3xl font-extrabold text-red-800 mb-6 flex items-center">
        <i class="fa-solid fa-pen-to-square text-red-500 mr-3"></i>
        Modifier la classe
    </h1>

    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded-xl shadow">
        <p class="font-bold">Important :</p>
        <p>Les étudiants que vous sélectionnez seront automatiquement :</p>
        <ul class="list-disc ml-6 mt-2">
            <li>Retirés de leurs classes actuelles (tous semestres)</li>
            <li>Assignés au semestre {{ $classe->semestre }} de cette classe</li>
            <li>Assignés au semestre {{ $classe->semestre == '1' ? '2' : '1' }} de la même année académique ({{ $classe->annee_academique }})</li>
        </ul>
    </div>

    @if ($errors->any())
        <div class="mb-4 p-3 rounded-xl bg-red-50 text-red-800 border border-red-200 shadow flex items-center">
            <i class="fa-solid fa-circle-exclamation mr-2"></i>
            <ul class="ml-2">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('coordinateur.classes.update', $classe->id) }}" method="POST" class="bg-white rounded-2xl shadow-lg p-8 border border-gray-100 space-y-6">
        @csrf
        @method('PUT')
        @if(Auth::user()->role === 'admin')
        <div>
            <label for="nom" class="block text-sm font-semibold text-red-700 mb-1">
                <i class="fa-solid fa-school mr-1 text-red-400"></i> Nom de la classe
            </label>
            <input type="text" name="nom" id="nom" class="border rounded-lg w-full px-3 py-2 focus:ring-red-500 focus:border-red-500" value="{{ old('nom', $classe->nom) }}" required>
        </div>
        @else
        <div>
            <label for="nom" class="block text-sm font-semibold text-red-700 mb-1">
                <i class="fa-solid fa-school mr-1 text-red-400"></i> Nom de la classe
            </label>
            <input type="text" name="nom" id="nom" class="border rounded-lg w-full px-3 py-2 bg-gray-100 text-gray-500" value="{{ $classe->nom }}" readonly>
        </div>
        @endif
        <div>
            <label class="block text-sm font-semibold text-red-700 mb-2">
                <i class="fa-solid fa-users mr-1 text-red-400"></i> Sélectionner les étudiants
            </label>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-2 max-h-64 overflow-y-auto border p-2 rounded">
                @foreach($etudiants as $etudiant)
                    <label class="flex items-center space-x-2">
                        <input type="checkbox" name="etudiants[]" value="{{ $etudiant->id }}" {{ $classe->etudiants->contains($etudiant->id) ? 'checked' : '' }}>
                        <span>{{ $etudiant->name }} <span class="text-gray-400">({{ $etudiant->matricule }})</span></span>
                    </label>
                @endforeach
            </div>
        </div>
        <div class="flex items-center justify-between pt-2">
            <button type="submit"
                    class="inline-flex items-center bg-red-600 hover:bg-red-700 text-white px-6 py-2 rounded-lg shadow font-bold transition">
                <i class="fa-solid fa-floppy-disk mr-2"></i> Enregistrer
            </button>
            <a href="{{ route('coordinateur.classes.show', $classe->id) }}" class="text-red-600 underline font-semibold hover:text-red-800 transition ml-4">
                Annuler
            </a>
        </div>
    </form>
</div>
@endsection
