@extends('layouts.app')
@section('content')
<div class="max-w-6xl mx-auto py-8 px-3">
    <div class="flex items-center justify-between mb-4">
        <h2 class="text-3xl font-extrabold text-red-700 flex items-center">
            <i class="fa-solid fa-list-check text-red-500 mr-3 text-2xl"></i>
            Gestion des présences
        </h2>
    </div>

    <!-- Filtres -->
    <div class="bg-white p-4 rounded-xl shadow mb-6 border border-red-100">
        <form method="GET" action="{{ route('coordinateur.presences.index') }}" class="grid md:grid-cols-4 gap-4">
            <div>
                <label for="classe" class="block text-sm font-medium text-red-700 mb-1">Classe</label>
                <select id="classe" name="classe" class="w-full rounded-md border-red-200 shadow-sm focus:border-red-300 focus:ring focus:ring-red-200 focus:ring-opacity-50">
                    <option value="">Toutes les classes</option>
                    @foreach($classes ?? [] as $classeOption)
                        <option value="{{ $classeOption->id }}" {{ request('classe') == $classeOption->id ? 'selected' : '' }}>
                            {{ $classeOption->nom }} (S{{ $classeOption->semestre }})
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="matiere" class="block text-sm font-medium text-red-700 mb-1">Matière</label>
                <select id="matiere" name="matiere" class="w-full rounded-md border-red-200 shadow-sm focus:border-red-300 focus:ring focus:ring-red-200 focus:ring-opacity-50">
                    <option value="">Toutes les matières</option>
                    @foreach($matieres ?? [] as $matiereOption)
                        <option value="{{ $matiereOption->id }}" {{ request('matiere') == $matiereOption->id ? 'selected' : '' }}>
                            {{ $matiereOption->nom }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="date" class="block text-sm font-medium text-red-700 mb-1">Date</label>
                <input type="date" id="date" name="date" value="{{ request('date') }}" class="w-full rounded-md border-red-200 shadow-sm focus:border-red-300 focus:ring focus:ring-red-200 focus:ring-opacity-50">
            </div>
            <div class="flex items-end">
                <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg transition font-medium flex items-center">
                    <i class="fas fa-filter mr-2"></i> Filtrer
                </button>
                @if(request()->has('classe') || request()->has('matiere') || request()->has('date'))
                    <a href="{{ route('coordinateur.presences.index') }}" class="ml-2 bg-gray-200 hover:bg-gray-300 text-gray-800 px-4 py-2 rounded-lg transition font-medium">
                        <i class="fas fa-times"></i>
                    </a>
                @endif
            </div>
        </form>
    </div>

    <div class="bg-gradient-to-br from-red-50 to-white rounded-2xl shadow-lg overflow-x-auto border border-gray-100">
        <table class="min-w-full text-base divide-y divide-gray-200">
            <thead>
                <tr>
                    <th class="px-3 py-3 text-center font-bold text-red-700 bg-white">Classe</th>
                    <th class="px-3 py-3 text-center font-bold text-red-700 bg-white">Matière</th>
                    <th class="px-3 py-3 text-center font-bold text-red-700 bg-white">Professeur</th>
                    <th class="px-3 py-3 text-center font-bold text-red-700 bg-white">Date</th>
                    <th class="px-3 py-3 text-center font-bold text-red-700 bg-white">Type</th>
                    <th class="px-3 py-3 text-center font-bold text-red-700 bg-white">Présences</th>
                    <th class="px-3 py-3 text-center font-bold text-red-700 bg-white">Actions</th>
                </tr>
            </thead>
            <tbody>
            @foreach($cours as $c)
                <tr class="hover:bg-red-50 transition"
                    data-classe="{{ $c->classe_id }}"
                    data-matiere="{{ $c->matiere_id }}"
                    data-date="{{ $c->date }}"
                    data-presence-status="{{ $c->presences_count > 0 ? ($c->presences_count >= ($c->classe->etudiants_count ?? 0) ? 'complete' : 'incomplete') : 'none' }}">
                    <td class="px-3 py-3 text-center font-bold text-red-800">
                        <i class="fa-solid fa-school text-red-400 mr-1"></i>
                        {{ $c->classe ? $c->classe->nom : 'Non attribuée' }}
                    </td>
                    <td class="px-3 py-3 text-center font-semibold text-red-700">
                        <i class="fa-solid fa-book text-red-300 mr-1"></i>
                        {{ $c->matiere ? $c->matiere->nom : 'Non attribuée' }}
                    </td>
                    <td class="px-3 py-3 text-center">
                        <i class="fa-solid fa-user-tie text-red-400 mr-1"></i>
                        {{ $c->professeur->name }}
                    </td>
                    <td class="px-3 py-3 text-center text-gray-700 font-semibold">
                        <i class="fa-regular fa-calendar text-red-400 mr-1"></i>
                        {{ \Carbon\Carbon::parse($c->date)->format('d-m-Y') }}
                    </td>
                    <td class="px-3 py-3 text-center">
                        @forelse($c->types as $type)
                            @if($type->code === 'workshop')
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-bold bg-orange-100 text-orange-800">
                                    <i class="fa-solid fa-flask mr-1"></i>{{ $type->nom }}
                                </span>
                            @elseif($type->code === 'e-learning')
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-bold bg-blue-100 text-blue-800">
                                    <i class="fa-solid fa-laptop mr-1"></i>{{ $type->nom }}
                                </span>
                            @else
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-bold bg-gray-100 text-gray-800">
                                    <i class="fa-solid fa-book-open mr-1"></i>{{ $type->nom }}
                                </span>
                            @endif
                        @empty
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-bold bg-gray-50 text-gray-400">
                                <i class="fa-solid fa-question mr-1"></i>Non défini
                            </span>
                        @endforelse
                    </td>
                    <td class="px-3 py-3 text-center">
                        @php
                            $totalEtudiants = $c->classe->etudiants_count ?? 0;
                            $totalPresences = $c->presences_count ?? 0;
                            $ratio = $totalEtudiants > 0 ? ($totalPresences / $totalEtudiants) * 100 : 0;
                        @endphp

                        @if($totalPresences === 0)
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-gray-100 text-gray-600">
                                <i class="fa-regular fa-hourglass mr-1"></i> En attente
                            </span>
                        @elseif($ratio === 100)
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-green-100 text-green-700">
                                <i class="fa-solid fa-check-circle mr-1"></i> Complète ({{ $totalPresences }}/{{ $totalEtudiants }})
                            </span>
                        @else
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-yellow-100 text-yellow-700">
                                <i class="fa-solid fa-clock mr-1"></i> {{ number_format($ratio, 0) }}% ({{ $totalPresences }}/{{ $totalEtudiants }})
                            </span>
                        @endif
                    </td>
                    <td class="px-3 py-3 text-center">
                        <a href="{{ route('coordinateur.presences.show', $c->id) }}"
                           class="inline-flex items-center px-3 py-1 rounded-lg text-xs font-bold bg-red-100 text-red-700 hover:bg-red-200 transition shadow">
                            <i class="fa-solid fa-eye mr-1"></i> Voir détails
                        </a>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-6">
        {{ $cours->links() }}
    </div>
</div>
@endsection
