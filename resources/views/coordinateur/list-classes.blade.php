@extends('layouts.app')

@section('content')
<div class="container mx-auto py-8">
    <h1 class="text-2xl font-bold mb-4">Liste des classes</h1>
    @if (session('success'))
        <div class="text-green-600 mb-2">{{ session('success') }}</div>
    @endif
    <a href="{{ route('coordinateur.classes.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded mb-4 inline-block">Créer une classe</a>
    <div class="overflow-x-auto">
        <table class="min-w-full bg-white border">
            <thead>
                <tr>
                    <th class="px-4 py-2 border">Nom</th>
                    <th class="px-4 py-2 border">Étudiants</th>
                   <th class="px-4 py-2 border">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($classes as $classe)
                    <tr>
                        <td class="px-4 py-2 border">
                            <a href="{{ route('coordinateur.classes.show', $classe->id) }}" class="text-blue-600 underline hover:text-blue-800">{{ $classe->nom }}</a>
                        </td>
                        <td class="px-4 py-2 border">
                            @foreach($classe->etudiants as $etudiant)
                                <span class="inline-block bg-gray-200 rounded px-2 py-1 text-xs mr-1 mb-1">{{ $etudiant->name }} ({{ $etudiant->matricule }})</span>
                            @endforeach
                        </td>
                        <td class="px-4 py-2 border text-center">
                            <form action="{{ route('coordinateur.classes.delete', $classe->id) }}" method="POST" style="display:inline" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette classe ?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:underline bg-transparent border-none cursor-pointer">Supprimer</button>
                            </form>
                        </td>

                    </tr>
                @empty
                    <tr>
                        <td colspan="2" class="px-4 py-2 border text-center">Aucune classe trouvée.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
