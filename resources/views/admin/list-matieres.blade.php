@extends('layouts.app')
@section('content')
<div class="max-w-4xl mx-auto py-8">
    <h2 class="text-2xl font-bold mb-4">Liste des matières</h2>
    <a href="{{ route('admin.matieres.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded mb-4 inline-block">Créer une matière</a>
    @if(session('success'))
        <div class="mb-4 p-2 bg-green-100 text-green-700">{{ session('success') }}</div>
    @endif
    <table class="w-full border text-sm">
        <thead class="bg-gray-100">
            <tr>
                <th class="border px-2 py-2">Nom</th>
                <th class="border px-2 py-2">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($matieres as $matiere)
                <tr>
                    <td class="border px-2 py-2">{{ $matiere->nom }}</td>
                    <td class="border px-2 py-2">
                        <a href="{{ route('admin.matieres.edit', $matiere->id) }}" class="text-blue-600 underline mr-2">Modifier</a>
                        <form action="{{ route('admin.matieres.destroy', $matiere->id) }}" method="POST" style="display:inline" onsubmit="return confirm('Supprimer cette matière ?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 underline ml-2">Supprimer</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
