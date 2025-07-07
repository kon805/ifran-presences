@extends('layouts.app')
@section('content')
<div class="max-w-6xl mx-auto py-6">
    <h2 class="text-2xl font-bold mb-4">Emploi du temps – Liste des cours</h2>
    <a href="{{ route('emploi-du-temps.create') }}" class="bg-indigo-600 text-white px-4 py-2 rounded mb-4 inline-block">Ajouter un cours</a>

    @if(session('success'))
        <div class="mb-4 p-2 bg-green-100 text-green-700">{{ session('success') }}</div>
    @endif

    <table class="w-full border text-sm">
        <thead class="bg-gray-100">
            <tr>
                <th class="border px-2 py-2">Classe</th>
                <th class="border px-2 py-2">Matière</th>
                <th class="border px-2 py-2">Professeur</th>
                <th class="border px-2 py-2">Date</th>
                <th class="border px-2 py-2">Heure</th>
                <th class="border px-2 py-2">État</th>
                <th class="border px-2 py-2">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($cours as $c)
                <tr>
                    <td class="border px-2 py-2">{{ $c->classe->nom }}</td>
                    <td class="border px-2 py-2">{{ $c->matiere }}</td>
                    <td class="border px-2 py-2">{{ $c->professeur->name }}</td>
                    <td class="border px-2 py-2">{{ \Carbon\Carbon::parse($c->date)->format('d-m-Y') }}</td>
                    <td class="border px-2 py-2">{{ $c->heure_debut }} - {{ $c->heure_fin }}</td>
                    <td class="border px-2 py-2">{{ ucfirst($c->etat) }}</td>
                    <td class="border px-2 py-2">
                        <a href="{{ route('emploi-du-temps.edit', $c->id) }}" class="text-blue-600 underline">Modifier</a>
                        <form action="{{ route('emploi-du-temps.destroy', $c->id) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 underline ml-2" onclick="return confirm('Supprimer ce cours ?')">Supprimer</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
