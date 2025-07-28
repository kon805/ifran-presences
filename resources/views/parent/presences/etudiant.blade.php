@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto py-10 px-4">
    <div class="mb-6">
        <a href="{{ route('parent.presences.index') }}" class="inline-flex items-center text-blue-600 hover:text-blue-800">
            <i class="fa-solid fa-arrow-left mr-2"></i>
            Retour à la liste des enfants
        </a>
    </div>

    <h2 class="text-3xl font-extrabold text-blue-800 mb-8 flex items-center">
        <i class="fa-solid fa-user-graduate text-blue-500 mr-3"></i>
        Absences de {{ $etudiant->name }}
    </h2>

    <div class="bg-white rounded-2xl shadow-xl overflow-hidden mb-8 border border-gray-200">
        <!-- En-tête avec photo et informations de l'étudiant -->
        <div class="bg-gradient-to-r from-blue-50 to-white p-6">
            <div class="flex flex-col sm:flex-row items-start sm:items-center gap-6">
                <img class="h-20 w-20 rounded-full border-2 border-blue-200 shadow"
                     src="{{ $etudiant->profile_photo_url }}"
                     alt="{{ $etudiant->name }}">

                <div>
                    <h3 class="text-2xl font-bold text-gray-900">{{ $etudiant->name }}</h3>
                    <p class="text-gray-500">{{ $etudiant->email }}</p>
                    @if($etudiant->matricule)
                        <p class="text-sm text-blue-600 mt-1">
                            <i class="fa-solid fa-id-card mr-1"></i>
                            Matricule: {{ $etudiant->matricule }}
                        </p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Statistiques d'absences -->
        <div class="p-6 bg-gray-50 border-t border-gray-200">
  

            <!-- Taux de présence par matière -->
            <div class="bg-white rounded-lg p-5 border border-gray-200 shadow mb-8">
                <h4 class="text-lg font-semibold text-blue-800 mb-4 flex items-center">
                    <i class="fa-solid fa-book mr-2 text-blue-500"></i>
                    Taux de présence par matière
                </h4>
                <div class="space-y-4">
                    @forelse($presencesParMatiere as $matiere_id => $stats)
                        <div>
                            <div class="flex items-center justify-between mb-1">
                                <span class="text-sm text-gray-700 font-semibold">
                                    {{ $stats['nom'] }}
                                </span>
                                <span class="text-sm font-bold text-blue-700">
                                    {{ $stats['taux'] }}% <span class="text-gray-500">({{ $stats['presents'] }}/{{ $stats['total'] }})</span>
                                </span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2.5">
                                <div class="{{ str_replace('bg-', 'bg-', $stats['couleur']) }} h-2.5 rounded-full transition-all duration-300" style="width: {{ $stats['taux'] }}%"></div>
                            </div>
                        </div>
                    @empty
                        <p class="text-gray-500 text-center py-3">Aucune donnée disponible par matière</p>
                    @endforelse
                </div>
            </div>

            <!-- Liste détaillée des absences -->
            <h4 class="font-semibold text-lg text-gray-800 mb-4">Détail des absences</h4>

            @if($absences->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full bg-white border border-gray-200">
                        <thead>
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider bg-gray-50">Date</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider bg-gray-50">Matière</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider bg-gray-50">Classe</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider bg-gray-50">Horaire</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider bg-gray-50">Statut</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach($absences as $absence)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900">
                                        {{ \Carbon\Carbon::parse($absence->cours->date)->format('d/m/Y') }}
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900">
                                        {{ $absence->cours->matiere->nom }}
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900">
                                        {{ $absence->cours->classe ? $absence->cours->classe->nom : '-' }}
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900">
                                        {{ $absence->cours->heure_debut }} - {{ $absence->cours->heure_fin }}
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap text-sm">
                                        @if($absence->justification && $absence->justification->justifiee)
                                            <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">
                                                <i class="fa-solid fa-check-circle mr-1"></i>
                                                Absence justifiée
                                            </span>
                                        @else
                                            <span class="px-2 py-1 text-xs rounded-full bg-red-100 text-red-800">
                                                <i class="fa-solid fa-times-circle mr-1"></i>
                                                Non justifiée
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center p-6 bg-blue-50 rounded-lg border border-blue-200">
                    <i class="fa-solid fa-thumbs-up text-blue-500 text-2xl mb-2"></i>
                    <p class="text-blue-800">Aucune absence enregistrée pour cet étudiant sur les 3 derniers mois.</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
