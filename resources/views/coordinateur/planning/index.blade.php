@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="px-4 py-6 sm:px-0">
        <div class="flex flex-col space-y-4 mb-6">
            <div class="flex justify-between items-center">
                <h2 class="text-2xl font-bold text-gray-900">
                    Emploi du temps
                    <span class="text-gray-500 text-lg font-normal">
                        (Semaine du {{ $currentMonday->format('d/m/Y') }} au {{ $endOfWeek->format('d/m/Y') }})
                    </span>
                </h2>
                <div class="flex space-x-3">
                    <a href="{{ route('coordinateur.planning.export', [
                        'start_date' => $currentMonday->format('Y-m-d'),
                        'classe_id' => $classeId
                    ]) }}"
                       class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700">
                        <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        Exporter en PDF
                    </a>
                </div>
            </div>

            <div class="flex justify-between items-center bg-gray-50 px-4 py-3 rounded-lg shadow-sm">
                <a href="{{ route('coordinateur.planing.index', ['week' => $previousWeek, 'classe_id' => $classeId]) }}"
                   class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                    <svg class="-ml-1 mr-2 h-5 w-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                    Semaine précédente
                </a>

                <div class="flex items-center space-x-4">
                    <div class="text-sm text-gray-700">
                        <i class="fa-solid fa-calendar-week mr-2 text-indigo-500"></i>
                        {{ $cours->count() > 0 ? 'Classes affichées : ' . $cours->pluck('classe.nom')->unique()->implode(', ') : 'Aucune classe affichée' }}
                    </div>
                </div>

                <a href="{{ route('coordinateur.planing.index', ['week' => $nextWeek, 'classe_id' => $classeId]) }}"
                   class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                    Semaine suivante
                    <svg class="ml-2 h-5 w-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </a>
            </div>

            <!-- Filtres -->
            <div class="bg-white px-4 py-5 border-b border-gray-200 sm:px-6 rounded-lg shadow-sm mb-4">
                <form action="{{ route('coordinateur.planing.index') }}" method="GET" class="space-y-4">
                    <input type="hidden" name="week" value="{{ request('week', $currentMonday->format('Y-m-d')) }}">

                    <div class="flex flex-wrap items-end space-x-4">
                        <div class="w-full sm:w-auto">
                            <label for="classe_id" class="block text-sm font-medium text-gray-700">Classe</label>
                            <select id="classe_id" name="classe_id" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                                <option value="">Toutes les classes</option>
                                @foreach($classes as $classe)
                                    <option value="{{ $classe->id }}" {{ $classeId == $classe->id ? 'selected' : '' }}>{{ $classe->nom }} (Semestre {{ $classe->semestre }})</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="w-full sm:w-auto">
                            <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                                </svg>
                                Filtrer
                            </button>

                            <a href="{{ route('coordinateur.planing.index', ['week' => request('week', $currentMonday->format('Y-m-d'))]) }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 ml-2">
                                <svg class="-ml-1 mr-2 h-5 w-5 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                </svg>
                                Réinitialiser
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="bg-white shadow overflow-hidden sm:rounded-lg">
            @forelse($cours as $date => $coursJour)
                <div class="border-t border-gray-200">
                    <div class="bg-gray-50 px-4 py-5 sm:px-6">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">
                            {{ Carbon\Carbon::parse($date)->locale('fr')->format('l d F Y') }}
                        </h3>
                    </div>
                    <div class="border-t border-gray-200">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Horaire</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Matière</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Professeur</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Classe</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">État</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($coursJour as $cours)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $cours->heure_debut }} - {{ $cours->heure_fin }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $cours->matiere->nom }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $cours->professeur->name }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                                {{ $cours->classe->nom }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            @foreach($cours->types as $type)
                                                @if($type->code === 'workshop')
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                        {{ $type->nom }}
                                                    </span>
                                                @elseif($type->code === 'e-learning')
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                        {{ $type->nom }}
                                                    </span>
                                                @else
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                        {{ $type->nom }}
                                                    </span>
                                                @endif
                                            @endforeach
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            @if($cours->etat === 'programmé')
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                    Programmé
                                                </span>
                                            @elseif($cours->etat === 'annulé')
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                                    Annulé
                                                </span>
                                            @else
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                    Reporté
                                                </span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @empty
                <div class="text-center py-8 text-gray-500">
                    Aucun cours programmé pour cette semaine
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection
