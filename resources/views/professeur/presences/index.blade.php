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
                <th class="border px-3 py-2">Statut</th>
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
                        @if($c->presences_count > 0)
                            <div class="flex items-center">
                                <div class="h-2.5 w-2.5 rounded-full bg-green-500 mr-2"></div>
                                <span class="text-sm text-gray-700">{{ $c->presences_count }} présences saisies</span>
                            </div>
                        @elseif(Carbon\Carbon::parse($c->date)->isPast())
                            <div class="flex items-center">
                                <div class="h-2.5 w-2.5 rounded-full bg-red-500 mr-2"></div>
                                <span class="text-sm text-gray-700">Non saisies</span>
                            </div>
                        @else
                            <div class="flex items-center">
                                <div class="h-2.5 w-2.5 rounded-full bg-gray-300 mr-2"></div>
                                <span class="text-sm text-gray-700">En attente</span>
                            </div>
                        @endif
                    </td>
                    <td class="border px-3 py-2">
                        @if(!$c->classe || !$c->matiere)
                            <span class="text-gray-500 italic">Configuration incomplète</span>
                        @elseif($c->presences_count > 0)
                            <div class="flex items-center space-x-2">
                                <span class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-xs font-medium">
                                    Présences saisies et verrouillées
                                </span>
                                <span class="text-sm text-gray-500">({{ $c->presences_count }} étudiants)</span>
                            </div>
                        @elseif($c->classe && $c->matiere && (!Carbon\Carbon::parse($c->date)->isPast() || Carbon\Carbon::parse($c->date)->isToday()))
                            <a href="{{ route('presences.edit', $c->id) }}"
                               class="inline-flex items-center px-3 py-1 border border-transparent rounded-md text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700">
                                Saisir présences
                            </a>
                        @else
                            <span class="text-gray-500 italic">Non saisies (délai expiré)</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="border px-3 py-4 text-center text-gray-500">
                        Aucun cours programmé n'est disponible pour la saisie des présences.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>


</div>
@endsection
