@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto py-10 px-4">
    <h2 class="text-3xl font-extrabold text-blue-800 mb-8 flex items-center">
        <i class="fa-solid fa-children text-blue-500 mr-3"></i>
        Suivi des absences de mes enfants
    </h2>

    @foreach($enfants as $enfant)
    <div class="bg-white rounded-2xl shadow-xl overflow-hidden mb-8 border border-gray-200"
         x-data="{ open: false }">
        <!-- En-tête avec informations de base et bouton déplier -->
        <div class="bg-gradient-to-r from-blue-50 to-white p-4 flex items-center justify-between cursor-pointer"
             @click="open = !open">
            <div class="flex items-center space-x-4">
                <div>
                    <img class="h-14 w-14 rounded-full border-2 border-blue-200 shadow" src="{{ $enfant->profile_photo_url }}" alt="{{ $enfant->name }}">
                </div>
                <div>
                    <h3 class="text-xl font-bold text-gray-900">{{ $enfant->name }}</h3>
                    <p class="text-sm text-gray-500">{{ $enfant->email }}</p>
                </div>
            </div>
            <button class="text-blue-500 hover:text-blue-700 focus:outline-none">
                <i class="fa-solid fa-chevron-down transition-transform duration-300" :class="open ? 'rotate-180' : ''"></i>
            </button>
        </div>

        <!-- Contenu détaillé (dépliable) -->
        <div x-show="open" x-transition class="bg-gray-50 p-6 space-y-8">
            <!-- Statistiques générales -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="bg-red-50 rounded-lg p-4 border border-red-200 shadow">
                    <h4 class="text-sm font-semibold text-red-800 mb-2 flex items-center">
                        <i class="fa-solid fa-triangle-exclamation mr-2"></i>
                        Absences non justifiées
                    </h4>
                    <p class="text-2xl font-bold text-red-600">{{ $statistiques[$enfant->id]['absences_non_justifiees'] }}</p>
                </div>
                <div class="bg-yellow-50 rounded-lg p-4 border border-yellow-200 shadow">
                    <h4 class="text-sm font-semibold text-yellow-800 mb-2 flex items-center">
                        <i class="fa-solid fa-file-circle-check mr-2"></i>
                        Absences justifiées
                    </h4>
                    <p class="text-2xl font-bold text-yellow-600">{{ $statistiques[$enfant->id]['absences_justifiees'] }}</p>
                </div>
                <div class="bg-green-50 rounded-lg p-4 border border-green-200 shadow">
                    <h4 class="text-sm font-semibold text-green-800 mb-2 flex items-center">
                        <i class="fa-solid fa-chart-line mr-2"></i>
                        Taux de présence global
                    </h4>
                    <p class="text-2xl font-bold text-green-600">{{ $statistiques[$enfant->id]['taux_presence_global'] }}%</p>
                    <p class="text-sm text-green-700 mt-1">
                        {{ $statistiques[$enfant->id]['presences_effectives'] }}/{{ $statistiques[$enfant->id]['total_presences'] }} cours
                </div>
            </div>

            <!-- Graphique des présences par semaine -->
            <div class="bg-white rounded-lg p-4 border border-gray-200 shadow">
                <h4 class="text-lg font-semibold text-blue-800 mb-4 flex items-center">
                    <i class="fa-solid fa-chart-column mr-2 text-blue-500"></i>
                    Taux de présence par semaine
                </h4>
                <div class="space-y-3">
                    @foreach($statistiques[$enfant->id]['presences_par_semaine'] as $semaine => $stats)
                        <div>
                            <div class="flex items-center justify-between mb-1">
                                <span class="text-sm text-gray-700 font-semibold">
                                    Semaine du {{ \Carbon\Carbon::parse($semaine)->format('d/m/Y') }}
                                </span>
                                <span class="text-sm font-bold text-blue-700">
                                    {{ $stats['taux'] }}% <span class="text-gray-500">({{ $stats['presents'] }}/{{ $stats['total'] }})</span>
                                </span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="bg-blue-500 h-2 rounded-full transition-all duration-300" style="width: {{ $stats['taux'] }}%"></div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Liste détaillée des absences -->
            <div class="bg-white rounded-lg border border-gray-200 shadow">
                <h4 class="text-lg font-semibold text-blue-800 p-4 border-b border-gray-200 flex items-center">
                    <i class="fa-solid fa-list-check mr-2 text-blue-500"></i>
                    Détail des absences
                </h4>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-bold text-blue-700 uppercase">Date</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-blue-700 uppercase">Matière</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-blue-700 uppercase">Statut</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-blue-700 uppercase">Justification</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-100">
                            @foreach($enfant->presences->where('present', false) as $presence)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ \Carbon\Carbon::parse($presence->cours->date)->format('d/m/Y') }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $presence->cours->matiere->nom }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($presence->justification && $presence->justification->justifiee)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-yellow-100 text-yellow-800">
                                                <i class="fa-solid fa-check-circle mr-1"></i>
                                                Justifiée
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-red-100 text-red-800">
                                                <i class="fa-solid fa-xmark-circle mr-1"></i>
                                                Non justifiée
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                        {{ $presence->justification ? $presence->justification->motif : 'Aucune justification' }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>
@endsection
