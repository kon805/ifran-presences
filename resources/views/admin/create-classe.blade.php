@extends('layouts.app')
@section('content')
<div class="max-w-2xl mx-auto py-12 px-6">
    <div class="bg-gradient-to-br from-blue-50 via-white to-blue-100 shadow-2xl rounded-3xl p-8 border border-blue-200">
        <h2 class="text-3xl font-extrabold text-blue-900 mb-8 flex items-center gap-3">
            <i class="fa-solid fa-building-columns text-blue-500 text-3xl"></i>
            Créer une classe
        </h2>
        <form action="{{ route('admin.classes.store') }}" method="POST" class="space-y-7">
            @csrf
            <div>
                <label for="nom" class="block text-lg font-semibold text-blue-800 mb-2">
                    <i class="fa-solid fa-chalkboard-user mr-1"></i>
                    Nom de la classe
                </label>
                <input 
                    type="text" name="nom" id="nom" 
                    class="border-2 border-blue-200 rounded-xl w-full p-3 focus:ring-2 focus:ring-blue-300 text-lg shadow"
                    required
                    placeholder="Ex: 3ème A, Terminale S..."
                >
            </div>
            <div>
                <label for="annee_academique" class="block text-lg font-semibold text-blue-800 mb-2">
                    <i class="fa-solid fa-calendar-days mr-1"></i>
                    Année académique
                </label>
                <select 
                    name="annee_academique" id="annee_academique" 
                    class="border-2 border-blue-200 rounded-xl w-full p-3 focus:ring-2 focus:ring-blue-300 text-lg shadow"
                    required
                >
                    <option value="">-- Choisir une année académique --</option>
                    @php
                        $currentYear = (int)date('Y');
                        for($i = 0; $i < 3; $i++) {
                            $year = $currentYear + $i;
                            $academicYear = $year . '-' . ($year + 1);
                            echo "<option value=\"{$academicYear}\">{$academicYear}</option>";
                        }
                    @endphp
                </select>
            </div>
            <input type="hidden" name="semestre" value="1">

            <div>
                <label for="coordinateur_id" class="block text-lg font-semibold text-blue-800 mb-2">
                    <i class="fa-solid fa-user-tie mr-1"></i>
                    Coordinateur
                </label>
                <select 
                    name="coordinateur_id" id="coordinateur_id"
                    class="border-2 border-blue-200 rounded-xl w-full p-3 focus:ring-2 focus:ring-blue-300 text-lg shadow"
                    required
                >
                    <option value="">-- Choisir un coordinateur --</option>
                    @foreach($coordinateurs as $coordinateur)
                        <option value="{{ $coordinateur->id }}">{{ $coordinateur->name }} ({{ $coordinateur->email }})</option>
                    @endforeach
                </select>
            </div>

            <div class="bg-blue-100 border-l-4 border-blue-500 text-blue-900 p-4 rounded-xl shadow-sm">
                <i class="fa-solid fa-info-circle mr-2"></i>
                <span class="font-medium">Note :</span>
                <span>
                    La classe sera créée pour le semestre 1.<br>
                    Le semestre 2 sera généré automatiquement à la clôture du semestre 1.
                </span>
            </div>

            <div class="flex justify-end">
                <button type="submit" class="bg-gradient-to-r from-blue-700 to-blue-500 hover:from-blue-900 hover:to-blue-600 text-white px-8 py-3 rounded-2xl shadow font-bold text-lg flex items-center gap-2 transition-all duration-150">
                    <i class="fa-solid fa-plus mr-1"></i>
                    Créer la classe
                </button>
            </div>
        </form>
    </div>
</div>
@endsection