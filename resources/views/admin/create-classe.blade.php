@extends('layouts.app')
@section('content')
<div class="max-w-2xl mx-auto py-8">
    <h2 class="text-2xl font-bold mb-4">Créer une classe</h2>
    <form action="{{ route('admin.classes.store') }}" method="POST">
        @csrf
        <div class="mb-4">
            <label for="nom" class="block font-medium">Nom de la classe</label>
            <input type="text" name="nom" id="nom" class="border rounded w-full p-2" required>
        </div>
        <div class="mb-4">
            <label for="annee_academique" class="block font-medium">Année académique</label>
            <select name="annee_academique" id="annee_academique" class="border rounded w-full p-2" required>
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

        <div class="mb-4">
            <input type="hidden" name="semestre" value="1">
        </div>

        <div class="mb-4">
            <label for="coordinateur_id" class="block font-medium">Coordinateur</label>
            <select name="coordinateur_id" id="coordinateur_id" class="border rounded w-full p-2" required>
                <option value="">-- Choisir un coordinateur --</option>
                @foreach($coordinateurs as $coordinateur)
                    <option value="{{ $coordinateur->id }}">{{ $coordinateur->name }} ({{ $coordinateur->email }})</option>
                @endforeach
            </select>
        </div>

        <div class="bg-blue-100 border-l-4 border-blue-500 text-blue-700 p-4 mb-4">
            <p>Note : La classe sera créée pour le semestre 1. Le semestre 2 sera créé automatiquement lors de la clôture du semestre 1.</p>
        </div>

        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Créer la classe</button>
    </form>
</div>
@endsection
