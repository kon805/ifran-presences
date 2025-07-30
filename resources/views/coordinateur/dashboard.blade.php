@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
    <!-- En-tête -->
    <h2 class="text-4xl font-black text-transparent bg-gradient-to-r from-blue-900 via-blue-600 to-blue-400 bg-clip-text mb-8 flex items-center">
        <i class="fas fa-chart-line text-blue-600 mr-4"></i>
        Tableau de bord
    </h2>
  {{-- liens qui mene vers la page graphique --}}
    <div class="bg-white shadow-lg rounded-2xl p-6 border border-blue-200 mb-8 bg-gradient-to-br from-blue-50 via-white to-blue-100">
        <div class="flex items-center mb-4">
            <div class="bg-blue-100 p-3 rounded-full mr-4">
                <i class="fas fa-tachometer-alt text-blue-600 text-xl"></i>
            </div>
            <h3 class="text-xl font-bold text-gray-800">Bienvenue dans votre espace coordinateur</h3>
        </div>
        <p class="text-gray-700">
            Surveillez facilement les performances académiques et la présence des étudiants. Consultez ci-dessous les informations récentes et accédez aux détails complets en un seul clic.
        </p>
        <a href="{{ route('coordinateur.graphiques.index') }}" class="text-blue-600 hover:underline">Voir les statistiques détaillées</a>
    </div>



    <!-- Cartes de statistiques principales -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Total étudiants -->
        <div class="bg-gradient-to-br from-blue-50 to-white rounded-2xl p-6 border border-blue-200 shadow-lg">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-semibold text-blue-800 mb-1">Total Étudiants</p>
                    <h3 class="text-3xl font-black text-blue-900">{{ $stats['total_etudiants'] }}</h3>
                </div>
                <div class="bg-blue-100 rounded-full p-3">
                    <i class="fas fa-user-graduate text-2xl text-blue-600"></i>
                </div>
            </div>
            <div class="mt-4 flex items-center text-sm">
                <span class="text-blue-800">{{ $stats['nombre_classes'] }} classes</span>
                <span class="mx-2 text-gray-300">•</span>
                <span class="text-blue-800">{{ $stats['nombre_professeurs'] }} professeurs</span>
            </div>
        </div>

        <!-- Taux d'absentéisme -->
        <div class="bg-gradient-to-br from-red-50 to-white rounded-2xl p-6 border border-red-200 shadow-lg">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-semibold text-red-800 mb-1">Taux d'absentéisme</p>
                    <h3 class="text-3xl font-black text-red-900">{{ $stats['taux_absenteisme'] }}%</h3>
                </div>
                <div class="bg-red-100 rounded-full p-3">
                    <i class="fas fa-chart-pie text-2xl text-red-600"></i>
                </div>
            </div>
            <div class="mt-4 flex items-center text-sm">
                <span class="text-red-800">{{ $stats['absences_total'] }} absences totales</span>
            </div>
        </div>

        <!-- Absences justifiées -->
        <div class="bg-gradient-to-br from-green-50 to-white rounded-2xl p-6 border border-green-200 shadow-lg">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-semibold text-green-800 mb-1">Absences justifiées</p>
                    <h3 class="text-3xl font-black text-green-900">{{ $stats['absences_justifiees'] }}</h3>
                </div>
                <div class="bg-green-100 rounded-full p-3">
                    <i class="fas fa-check-circle text-2xl text-green-600"></i>
                </div>
            </div>
            <div class="mt-4 flex items-center text-sm">
                <span class="text-green-800">{{ round(($stats['absences_justifiees'] / max($stats['absences_total'], 1)) * 100) }}% du total</span>
            </div>
        </div>

        <!-- Étudiants en difficulté -->
        <div class="bg-gradient-to-br from-yellow-50 to-white rounded-2xl p-6 border border-yellow-200 shadow-lg">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-semibold text-yellow-800 mb-1">Étudiants dropped</p>
                    <h3 class="text-3xl font-black text-yellow-900">{{ $stats['etudiants_dropped'] }}</h3>
                </div>
                <div class="bg-yellow-100 rounded-full p-3">
                    <i class="fas fa-exclamation-triangle text-2xl text-yellow-600"></i>
                </div>
            </div>
            <div class="mt-4 flex items-center text-sm">
                <span class="text-yellow-800">{{ round(($stats['etudiants_dropped'] / max($stats['total_etudiants'], 1)) * 100) }}% des étudiants</span>
            </div>
        </div>
    </div>

    <!-- Statistiques par classe -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
        <!-- Taux de présence par classe -->
        <div class="bg-white rounded-2xl p-6 shadow-lg border border-gray-200">
            <h3 class="text-lg font-bold text-gray-900 mb-6 flex items-center">
                <i class="fas fa-school text-blue-500 mr-2"></i>
                Taux de présence par classe
            </h3>
            <div class="space-y-4">
                @foreach($statsParClasse as $classe)
                    <div class="bg-gray-50 rounded-xl p-4">
                        <div class="flex items-center justify-between mb-2">
                            <span class="font-semibold text-gray-700">{{ $classe['nom'] }}</span>
                            <span class="text-{{ $classe['couleur'] }}-600 font-bold">{{ $classe['taux_presence'] }}%</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2.5">
                            <div class="bg-{{ $classe['couleur'] }}-600 h-2.5 rounded-full transition-all"
                                style="width: {{ $classe['taux_presence'] }}%"></div>
                        </div>
                        <p class="text-xs text-gray-500 mt-2">{{ $classe['etudiants_count'] }} étudiants</p>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Top 5 des matières avec le plus d'absences -->
        <div class="bg-white rounded-2xl p-6 shadow-lg border border-gray-200">
            <h3 class="text-lg font-bold text-gray-900 mb-6 flex items-center">
                <i class="fas fa-book text-red-500 mr-2"></i>
                Matières avec le plus d'absences
            </h3>
            <div class="space-y-4">
                @foreach($topMatieres as $matiere)
                    <div class="bg-gray-50 rounded-xl p-4">
                        <div class="flex items-center justify-between mb-2">
                            <span class="font-semibold text-gray-700">{{ $matiere['nom'] }}</span>
                            <span class="text-{{ $matiere['couleur'] }}-600 font-bold">{{ $matiere['taux_absence'] }}%</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2.5">
                            <div class="bg-{{ $matiere['couleur'] }}-600 h-2.5 rounded-full transition-all"
                                style="width: {{ $matiere['taux_absence'] }}%"></div>
                        </div>
                        <p class="text-xs text-gray-500 mt-2">
                            {{ $matiere['total_absences'] }} absences sur {{ $matiere['total_presences'] }} cours
                        </p>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Évolution sur 6 mois -->
    <div class="bg-white rounded-2xl p-6 shadow-lg border border-gray-200 mb-8">
        <h3 class="text-lg font-bold text-gray-900 mb-6 flex items-center">
            <i class="fas fa-chart-line text-indigo-500 mr-2"></i>
            Évolution sur 6 mois
        </h3>
        <div class="space-y-4">
            @foreach($statsParMois as $stat)
                <div class="bg-gray-50 rounded-xl p-4">
                    <div class="flex items-center justify-between mb-2">
                        <span class="font-semibold text-gray-700">
                            {{ \Carbon\Carbon::createFromFormat('Y-m', $stat['mois'])->format('F Y') }}
                        </span>
                        <span class="text-{{ $stat['couleur'] }}-600 font-bold">{{ $stat['taux_presence'] }}%</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2.5">
                        <div class="bg-{{ $stat['couleur'] }}-600 h-2.5 rounded-full transition-all"
                            style="width: {{ $stat['taux_presence'] }}%"></div>
                    </div>
                    <div class="flex items-center justify-between text-xs text-gray-500 mt-2">
                        <span>{{ $stat['presences'] }} présences</span>
                        <span>{{ $stat['absences'] }} absences</span>
                        <span>Total: {{ $stat['total'] }}</span>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
