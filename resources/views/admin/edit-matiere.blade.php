@extends('layouts.app')
@section('content')
<div class="max-w-2xl mx-auto py-8">
    <h2 class="text-2xl font-bold mb-4">Modifier une matière</h2>
    <form action="{{ route('admin.matieres.update', $matiere->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="mb-4">
            <label for="nom" class="block font-medium">Nom de la matière</label>
            <input type="text" name="nom" id="nom" class="border rounded w-full p-2" value="{{ $matiere->nom }}" required>
        </div>
        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Mettre à jour</button>
    </form>
</div>
@endsection
