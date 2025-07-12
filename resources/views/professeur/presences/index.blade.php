@extends('layouts.app')
@section('content')
<div class="max-w-5xl mx-auto py-6">
    <div class="flex justify-between items-center mb-4">
        <h2 class="text-2xl font-bold">Mes séances programmées</h2>
        @if(auth()->user()->role === 'professeur')
            <a href="{{ route('professeur.cleanup.cours') }}"
               onclick="return confirm('Êtes-vous sûr de vouloir supprimer tous les cours sans classe ou matière ?')"
               class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded">
                Nettoyer les cours incomplets
            </a>
        @endif
    </div>

    @if(session('success'))
        <div class="mb-4 p-2 bg-green-100 text-green-700">{{ session('success') }}</div>
    @endif
    @if($errors->any())
        <div class="mb-4 p-2 bg-red-100 text-red-700">
            {{ $errors->first() }}
        </div>
    @endif
    <table class="w-full border">
        <thead class="bg-gray-100">
            <tr>
                <th class="border px-3 py-2">Classe</th>
                <th class="border px-3 py-2">Matière</th>
                <th class="border px-3 py-2">Date</th>
                <th class="border px-3 py-2">Heure</th>
                <th class="border px-3 py-2">Action</th>
            </tr>
        </thead>
        <tbody>
            @forelse($cours as $c)
                <tr>
                    <td class="border px-3 py-2">{{ $c->classe->nom ?? 'Classe non définie' }}</td>
                    <td class="border px-3 py-2">{{ $c->matiere->nom ?? 'Matière non définie' }}</td>
                    <td class="border px-3 py-2">{{ \Carbon\Carbon::parse($c->date)->format('d-m-Y') }}</td>
                    <td class="border px-3 py-2">{{ $c->heure_debut }} - {{ $c->heure_fin }}</td>
                    <td class="border px-3 py-2">
                        @if($c->classe && $c->matiere)
                            <a href="{{ route('presences.edit', $c->id) }}" class="text-indigo-600 underline">Saisir présences</a>
                        @else
                            <span class="text-gray-500 italic">Configuration incomplète</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="border px-3 py-4 text-center text-gray-500">
                        Aucun cours programmé n'est disponible pour la saisie des présences.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>


</div>
@endsection
