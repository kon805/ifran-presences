@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
    <div class="flex justify-between items-center">
        <h1 class="text-3xl font-bold text-gray-900">Détails de la classe {{ $classe->nom }}</h1>

        @if(!$classe->semestre_termine)
            <form action="{{ route('coordinateur.classes.terminer-semestre', $classe) }}" method="POST">
                @csrf
                <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700"
                        onclick="return confirm('Êtes-vous sûr de vouloir terminer ce semestre ? Cette action ne peut pas être annulée.')">
                    Terminer le semestre {{ $classe->semestre_actuel }}
                </button>
            </form>
        @else
            <span class="bg-gray-100 text-gray-800 px-4 py-2 rounded-md">
                Semestre {{ $classe->semestre_actuel }} terminé
            </span>
        @endif
    </div>

    <!-- Reste du contenu... -->
</div>
@endsection
