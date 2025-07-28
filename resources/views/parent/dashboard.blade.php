
@extends('layouts.app')
@section('content')
<div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
    <h2 class="text-3xl font-extrabold text-blue-800 mb-6 flex items-center">
        <i class="fa-solid fa-house-user text-blue-500 mr-3"></i>
        Tableau de bord parent
    </h2>

    <div class="bg-white shadow-lg rounded-2xl p-6 border border-blue-200 mb-8 bg-gradient-to-br from-blue-50 via-white to-blue-100">
        <div class="flex items-center mb-4">
            <div class="bg-blue-100 p-3 rounded-full mr-4">
                <i class="fa-solid fa-child text-blue-600 text-xl"></i>
            </div>
            <h3 class="text-xl font-bold text-gray-800">Bienvenue dans votre espace parent</h3>
        </div>
        <p class="text-gray-700">
            Surveillez facilement les présences et absences de vos enfants. Consultez ci-dessous les informations
            récentes et accédez aux détails complets en un seul clic.
        </p>
    </div>

    <!-- Débogage - Informations brutes -->
    <div class="bg-blue-50 p-4 border border-blue-200 rounded-lg mb-6 overflow-x-auto">
        <h3 class="text-lg font-bold text-blue-800 mb-2">Informations de débogage</h3>
        <div class="grid grid-cols-2 gap-4 mb-4">
            <div>
                <p class="font-semibold text-blue-700">Variable $enfants:</p>

                <p>Vide? {{ isset($enfants) && count($enfants) === 0 ? 'Oui' : 'Non' }}</p>
                <p>Nombre: {{ isset($enfants) ? count($enfants) : 'Variable non définie' }}</p>
            </div>
            <div>
                <p class="font-semibold text-blue-700">Variable $absences:</p>
                <p>Définie? {{ isset($absences) ? 'Oui' : 'Non' }}</p>
                <p>Nombre d'entrées: {{ isset($absences) ? count($absences) : 'Variable non définie' }}</p>
            </div>
        </div>

        @if(isset($enfants) && count($enfants) > 0)
            <h4 class="font-semibold text-blue-700 mb-2">Détails des enfants:</h4>
            <div class="overflow-x-auto">
                <table class="min-w-full bg-white border border-blue-200">
                    <thead>
                        <tr>
                            <th class="px-3 py-2 border-b">ID</th>
                            <th class="px-3 py-2 border-b">Nom</th>
                            <th class="px-3 py-2 border-b">Email</th>
                            <th class="px-3 py-2 border-b">Rôle</th>
                            <th class="px-3 py-2 border-b">Type</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($enfants as $enfant)
                            <tr>
                                <td class="px-3 py-2 border-b">{{ $enfant->id ?? 'N/A' }}</td>
                                <td class="px-3 py-2 border-b">{{ $enfant->name ?? 'N/A' }}</td>
                                <td class="px-3 py-2 border-b">{{ $enfant->email ?? 'N/A' }}</td>
                                <td class="px-3 py-2 border-b">{{ $enfant->role ?? 'N/A' }}</td>
                                <td class="px-3 py-2 border-b">{{ get_class($enfant) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="bg-yellow-100 p-4 rounded-lg border border-yellow-300">
                <p class="text-yellow-800"><strong>Attention:</strong> La variable $enfants est vide ou non définie.</p>
            </div>
        @endif
    </div>

    <!-- Carte pour chaque enfant -->
    @if(!isset($enfants) || (is_object($enfants) && method_exists($enfants, 'isEmpty') && $enfants->isEmpty()) || (is_countable($enfants) && count($enfants) === 0))
        <div class="bg-white shadow-lg rounded-2xl p-8 border border-yellow-200 mb-8">
            <div class="flex flex-col md:flex-row items-center text-center md:text-left">
                <div class="bg-yellow-100 p-6 rounded-full mb-6 md:mb-0 md:mr-8">
                    <i class="fa-solid fa-triangle-exclamation text-yellow-600 text-3xl"></i>
                </div>
                <div>
                    <h3 class="text-2xl font-bold text-gray-800 mb-4">Aucun étudiant assigné</h3>
                    <p class="text-gray-600 text-lg mb-4">
                        Aucun enfant n'est actuellement assigné à votre compte parent.
                    </p>
                    <div class="bg-yellow-50 p-4 rounded-xl border border-yellow-200">
                        <h4 class="font-semibold text-yellow-800 mb-2">Pourquoi cela peut se produire :</h4>
                        <ul class="list-disc ml-5 text-gray-700 space-y-2">
                            <li>L'association parent-étudiant n'a pas encore été effectuée</li>
                            <li>Une erreur s'est produite lors de l'attribution des étudiants</li>
                        </ul>
                    </div>
                    <div class="mt-6">
                        <a href="mailto:admin@ifran-presences.com" class="inline-flex items-center px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition shadow-md">
                            <i class="fa-solid fa-envelope mr-2"></i> Contacter l'administration
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            @foreach($enfants as $enfant)
                @php
                    // Récupérer les données d'absence de cet enfant
                    $absenceData = $absences[$enfant->id] ?? [
                        'total_mois' => 0,
                        'absences_recentes' => 0,
                        'derniere_absence' => null
                    ];

                    // Déterminer le statut de l'élève
                    $statusColor = 'green';
                    $statusText = 'Excellent';
                    $statusIcon = 'fa-circle-check';

                    if ($absenceData['absences_recentes'] > 2) {
                        $statusColor = 'red';
                        $statusText = 'À surveiller';
                        $statusIcon = 'fa-circle-exclamation';
                    } elseif ($absenceData['total_mois'] > 3) {
                        $statusColor = 'yellow';
                        $statusText = 'Attention';
                        $statusIcon = 'fa-triangle-exclamation';
                    }
                @endphp

                <div class="bg-white rounded-2xl shadow-lg overflow-hidden border border-gray-200 transition-all duration-300 hover:shadow-xl">
                    <!-- En-tête avec statut -->
                    <div class="bg-gradient-to-r from-blue-50 to-white p-6 border-b border-gray-200 flex justify-between items-center">
                        <div class="flex items-center space-x-4">
                            <div class="relative">
                                <img class="h-16 w-16 rounded-full border-2 border-blue-300 shadow-lg"
                                    src="{{ $enfant->profile_photo_url }}" alt="{{ $enfant->name }}">
                                <span class="absolute bottom-0 right-0 h-5 w-5 bg-{{ $statusColor }}-500 border-2 border-white rounded-full"></span>
                            </div>
                            <div>
                                <h3 class="text-xl font-bold text-gray-900">{{ $enfant->name }}</h3>
                                <p class="text-sm text-gray-500">{{ $enfant->email ?? 'Email non disponible' }}</p>
                            </div>
                        </div>

                        <div class="bg-{{ $statusColor }}-100 text-{{ $statusColor }}-800 px-4 py-2 rounded-full text-sm font-bold flex items-center">
                            <i class="fa-solid {{ $statusIcon }} mr-2"></i>
                            {{ $statusText }}
                        </div>
                    </div>

                    <div class="p-6 bg-gray-50">
                        <!-- Information sur les absences -->
                        <div class="mb-6">
                            <h4 class="text-gray-700 font-semibold mb-3 flex items-center">
                                <i class="fa-solid fa-calendar-check text-blue-500 mr-2"></i>
                                Suivi des absences
                            </h4>

                            <div class="grid grid-cols-2 gap-4 mb-4">
                                <div class="bg-white rounded-xl p-4 shadow-sm border border-gray-100">
                                    <div class="text-xs uppercase text-gray-500 font-semibold">Ce mois-ci</div>
                                    <div class="mt-2 flex items-center">
                                        <span class="text-2xl font-bold {{ $absenceData['total_mois'] > 0 ? 'text-red-600' : 'text-green-600' }}">
                                            {{ $absenceData['total_mois'] }}
                                        </span>
                                        <span class="ml-2 text-gray-600">absence(s)</span>
                                    </div>
                                </div>
                                <div class="bg-white rounded-xl p-4 shadow-sm border border-gray-100">
                                    <div class="text-xs uppercase text-gray-500 font-semibold">Semaine dernière</div>
                                    <div class="mt-2 flex items-center">
                                        <span class="text-2xl font-bold {{ $absenceData['absences_recentes'] > 0 ? 'text-red-600' : 'text-green-600' }}">
                                            {{ $absenceData['absences_recentes'] }}
                                        </span>
                                        <span class="ml-2 text-gray-600">absence(s)</span>
                                    </div>
                                </div>
                            </div>

                            @if($absenceData['derniere_absence'])
                                <div class="bg-red-50 rounded-xl p-4 border border-red-200">
                                    <div class="flex items-center">
                                        <div class="bg-red-100 p-2 rounded-lg mr-3">
                                            <i class="fa-solid fa-calendar-xmark text-red-500"></i>
                                        </div>
                                        <div>
                                            <span class="text-sm text-red-800 font-medium">
                                                Dernière absence enregistrée:
                                            </span>
                                            <span class="ml-1 text-red-700 font-bold">
                                                {{ $absenceData['derniere_absence'] }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            @else
                                <div class="bg-green-50 rounded-xl p-4 border border-green-200">
                                    <div class="flex items-center">
                                        <div class="bg-green-100 p-2 rounded-lg mr-3">
                                            <i class="fa-solid fa-check-double text-green-500"></i>
                                        </div>
                                        <div class="text-sm text-green-800 font-medium">
                                            Aucune absence récente enregistrée
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>

                        <!-- Boutons d'action -->
                        <div class="grid grid-cols-2 gap-4">
                            <a href="{{ route('parent.presences.etudiant', $enfant->id) }}"
                               class="flex items-center justify-center px-4 py-3 bg-red-100 text-red-800 rounded-xl hover:bg-red-200 transition shadow-sm">
                                <i class="fa-solid fa-list-check mr-2"></i> Voir toutes les absences
                            </a>
                            <a href="{{ route('parent.presences.index') }}"
                               class="flex items-center justify-center px-4 py-3 bg-blue-600 text-white rounded-xl hover:bg-blue-700 transition shadow-sm">
                                <i class="fa-solid fa-chart-line mr-2"></i> Rapport détaillé
                            </a>
                        </div>
                    </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
