@extends('layouts.app')

@section('content')
<div class="max-w-xl mx-auto py-10 px-4">
    <h1 class="text-3xl font-extrabold text-yellow-700 mb-6 flex items-center">
        <i class="fa-solid fa-school text-yellow-500 mr-3"></i>
        Détails de la classe
    </h1>
    <div class="mb-6 bg-white rounded-xl shadow border border-gray-100 p-6">
        <div class="mb-4 flex items-center font-semibold text-lg text-gray-700">
            <i class="fa-solid fa-chalkboard-user text-yellow-400 mr-2"></i>
            <span>Nom :</span>
            <span class="ml-2 text-yellow-800">{{ $classe->nom }}</span>
        </div>
        <div class="mb-4">
            <div class="font-semibold text-gray-700 mb-2 flex items-center">
                <i class="fa-solid fa-users text-yellow-400 mr-2"></i>
                <span>Étudiants :</span>
            </div>
            <ul class="list-disc ml-6">
                @forelse($classe->etudiants as $etudiant)
                    <li class="mb-1 flex items-center">
                        <i class="fa-solid fa-user-graduate text-yellow-500 mr-2"></i>
                        <span>{{ $etudiant->name }} <span class="text-gray-400">({{ $etudiant->matricule }})</span></span>
                    </li>
                @empty
                    <li class="text-gray-500 italic">Aucun étudiant dans cette classe.</li>
                @endforelse
            </ul>
        </div>
    </div>
    <div class="flex items-center gap-3 mt-8">
        <a href="{{ route('coordinateur.classes.edit', $classe->id) }}"
           class="inline-flex items-center bg-yellow-500 hover:bg-yellow-600 text-white px-5 py-2 rounded-lg shadow font-bold transition">
            <i class="fa-solid fa-pen-to-square mr-2"></i> Modifier la classe
        </a>
        <a href="{{ route('coordinateur.classes.index') }}"
           class="text-yellow-700 underline font-semibold hover:text-yellow-900 transition">
            <i class="fa-solid fa-arrow-left mr-1"></i> Retour à la liste
        </a>
    </div>
</div>
@endsection
