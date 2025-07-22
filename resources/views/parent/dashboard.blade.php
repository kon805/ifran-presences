
@extends('layouts.app')
@section('content')
<div class="max-w-4xl mx-auto py-6">
    <h2 class="text-2xl font-bold mb-4">Tableau de bord des parents</h2>

    <div class="bg-white shadow-lg rounded-2xl p-8 border border-gray-100 space-y-6">
        <p class="text-gray-700 mb-4">
            Bienvenue sur votre tableau de bord, vous pouvez consulter les absences de vos enfants.
        </p>
        <a href="{{ route('parent.presences.index') }}"
           class="inline-block bg-cyan-600 hover:bg-cyan-700 text-white px-6 py-3 rounded-lg shadow font-bold transition">
            <i class="fa-solid fa-calendar-check mr-2"></i> Voir les absences
        </a>
    </div>

  </div>

@endsection
