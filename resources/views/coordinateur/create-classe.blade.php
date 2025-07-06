@extends('layouts.app')

@section('content')
<div class="container mx-auto py-8">
    <h1 class="text-2xl font-bold mb-4">Créer une classe</h1>
    @if (session('success'))
        <div class="text-green-600 mb-2">{{ session('success') }}</div>
    @endif
    @if ($errors->any())
        <div class="text-red-600 mb-2">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <form action="{{ route('coordinateur.classes.store') }}" method="POST" class="space-y-4">
        @csrf
        <div>
            <label for="nom" class="block font-medium">Nom de la classe</label>
            <input type="text" name="nom" id="nom" class="border rounded w-full p-2" required>
        </div>
        <div>
            <label class="block font-medium mb-2">Sélectionner les étudiants</label>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-2 max-h-64 overflow-y-auto border p-2 rounded">
                @foreach($etudiants as $etudiant)
                    <label class="flex items-center space-x-2">
                        <input type="checkbox" name="etudiants[]" value="{{ $etudiant->id }}">
                        <span>{{ $etudiant->name }} ({{ $etudiant->matricule }})</span>
                    </label>
                @endforeach
            </div>
        </div>
        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Créer la classe</button>
    </form>
</div>
@endsection
