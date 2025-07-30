@extends('layouts.app')
@section('content')
<div class="max-w-6xl mx-auto py-10 px-4">
    <div class="flex justify-between items-center mb-8">
        <h2 class="text-3xl font-extrabold text-blue-900 flex items-center gap-3">
            <i class="fa-solid fa-calendar-days text-indigo-600"></i>
            Mes séances programmées
        </h2>
        @if(auth()->user()->role === 'professeur')
            <a href="{{ route('professeur.cleanup.cours') }}"
               onclick="return confirm('Êtes-vous sûr de vouloir supprimer tous les cours sans classe ou matière ?')"
               class="bg-gradient-to-r from-red-600 to-pink-500 hover:from-red-700 hover:to-pink-700 text-white px-5 py-2 rounded-xl font-bold shadow transition duration-150">
                <i class="fa-solid fa-broom mr-2"></i>
                Nettoyer les cours incomplets
            </a>
        @endif
    </div>

    @if(session('success'))
        <div class="mb-4 p-3 rounded-xl bg-green-50 text-green-800 border border-green-200 shadow flex items-center">
            <i class="fa-solid fa-check-circle mr-2"></i> {{ session('success') }}
        </div>
    @endif
    @if($errors->any())
        <div class="mb-4 p-3 rounded-xl bg-red-50 text-red-800 border border-red-200 shadow flex items-center">
            <i class="fa-solid fa-circle-exclamation mr-2"></i> {{ $errors->first() }}
        </div>
    @endif

    <div class="bg-white rounded-2xl shadow-2xl border border-gray-200 overflow-x-auto">
        <table class="min-w-full text-base">
            <thead class="bg-gradient-to-r from-indigo-50 to-blue-50 border-b border-indigo-100">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-bold text-indigo-800 uppercase tracking-wider">Classe</th>
                    <th class="px-6 py-3 text-left text-xs font-bold text-indigo-800 uppercase tracking-wider">Matière</th>
                    <th class="px-6 py-3 text-left text-xs font-bold text-indigo-800 uppercase tracking-wider">Date</th>
                    <th class="px-6 py-3 text-left text-xs font-bold text-indigo-800 uppercase tracking-wider">Heure</th>
                    <th class="px-6 py-3 text-left text-xs font-bold text-indigo-800 uppercase tracking-wider">Statut</th>
                    <th class="px-6 py-3 text-left text-xs font-bold text-indigo-800 uppercase tracking-wider">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($cours as $c)
                    <tr class="hover:bg-indigo-50 transition">
                        <td class="px-6 py-3 font-semibold text-blue-900">{{ $c->classe->nom ?? 'Classe non définie' }}</td>
                        <td class="px-6 py-3 font-semibold text-blue-900">{{ $c->matiere->nom ?? 'Matière non définie' }}</td>
                        <td class="px-6 py-3 text-indigo-700">{{ \Carbon\Carbon::parse($c->date)->format('d-m-Y') }}</td>
                        <td class="px-6 py-3 text-indigo-700">{{ $c->heure_debut }} - {{ $c->heure_fin }}</td>
                        <td class="px-6 py-3">
                            @if($c->presences_count > 0)
                                <div class="flex items-center gap-2">
                                    <span class="h-3 w-3 rounded-full bg-green-500"></span>
                                    <span class="text-sm font-semibold text-green-700">{{ $c->presences_count }} présences saisies</span>
                                </div>
                            @elseif(Carbon\Carbon::parse($c->date)->isPast())
                                <div class="flex items-center gap-2">
                                    <span class="h-3 w-3 rounded-full bg-red-500"></span>
                                    <span class="text-sm font-semibold text-red-700">Non saisies</span>
                                </div>
                            @else
                                <div class="flex items-center gap-2">
                                    <span class="h-3 w-3 rounded-full bg-gray-400"></span>
                                    <span class="text-sm font-semibold text-gray-500">En attente</span>
                                </div>
                            @endif
                        </td>
                        <td class="px-6 py-3">
                            @if(!$c->classe || !$c->matiere)
                                <span class="text-gray-400 italic">Configuration incomplète</span>
                            @elseif($c->presences_count > 0)
                                <div class="flex items-center gap-2">
                                    <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-xs font-bold">
                                        Présences saisies & verrouillées
                                    </span>
                                    <span class="text-xs text-gray-600">({{ $c->presences_count }} étudiants)</span>
                                </div>
                            @elseif($c->classe && $c->matiere && (!Carbon\Carbon::parse($c->date)->isPast() || Carbon\Carbon::parse($c->date)->isToday()))
                                <a href="{{ route('presences.edit', $c->id) }}"
                                   class="inline-flex items-center px-4 py-2 rounded-xl text-xs font-bold text-white bg-indigo-600 hover:bg-indigo-700 transition duration-150 shadow">
                                    <i class="fa-solid fa-pencil mr-2"></i> Saisir présences
                                </a>
                            @else
                                <span class="text-gray-400 italic">Non saisies (délai expiré)</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-8 text-center text-gray-400 text-lg font-semibold">
                            <i class="fa-solid fa-face-sad-tear text-3xl text-indigo-200 mb-3"></i><br>
                            Aucun cours programmé n'est disponible pour la saisie des présences.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
