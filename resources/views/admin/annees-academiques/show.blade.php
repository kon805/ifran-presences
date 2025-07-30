@extends('layouts.app')
@section('content')
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                <div class="mb-6">
                    <div class="flex justify-between items-center">
                        <h2 class="text-2xl font-bold text-gray-800">
                            Année Académique: {{ $anneeAcademique->annee }}
                        </h2>
                        <a href="{{ route('admin.annees-academiques.index') }}"
                           class="bg-gray-200 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-300">
                            Retour
                        </a>
                    </div>

                    <div class="mt-4">
                        <span class="px-3 py-1 inline-flex text-sm rounded-full
                            {{ $anneeAcademique->statut === 'en_cours'
                                ? 'bg-green-100 text-green-800'
                                : 'bg-gray-100 text-gray-800' }}">
                            {{ $anneeAcademique->statut === 'en_cours' ? 'En cours' : 'Terminée' }}
                        </span>
                    </div>
                </div>

                <!-- Statistiques globales -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                    <div class="bg-blue-50 p-6 rounded-lg">
                        <h3 class="text-lg font-semibold text-blue-800">Total Étudiants</h3>
                        <p class="text-3xl font-bold text-blue-600">{{ $statistiques['total_etudiants'] }}</p>
                    </div>
                    <div class="bg-green-50 p-6 rounded-lg">
                        <h3 class="text-lg font-semibold text-green-800">Total Professeurs</h3>
                        <p class="text-3xl font-bold text-green-600">{{ $statistiques['total_professeurs'] }}</p>
                    </div>
                    <div class="bg-yellow-50 p-6 rounded-lg">
                        <h3 class="text-lg font-semibold text-yellow-800">Total Matières</h3>
                        <p class="text-3xl font-bold text-yellow-600">{{ $statistiques['total_matieres'] }}</p>
                    </div>
                </div>

                <!-- Liste des classes -->
                <div class="mt-8">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Classes</h3>
                    <div class="bg-gray-50 rounded-lg p-6">
                        @if($anneeAcademique->classes->count() > 0)
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                                @foreach($anneeAcademique->classes as $classe)
                                    <div class="bg-white p-4 rounded-lg shadow">
                                        <h4 class="font-semibold text-lg text-gray-800">{{ $classe->nom }}</h4>
                                        <div class="mt-2 text-sm text-gray-600">
                                            <p>{{ $classe->etudiants->count() }} étudiants</p>
                                            <p>{{ $classe->matieres->count() }} matières</p>
                                        </div>
                                        @if($anneeAcademique->statut === 'en_cours')
                                            <a href="{{route('admin.annees-academiques.edit', $anneeAcademique->id) }}"  class="mt-3 inline-block text-sm text-blue-600 hover:text-blue-800">
                                                Modifier
                                            </a>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-gray-500">Aucune classe n'a été créée pour cette année académique.</p>
                        @endif
                    </div>
                </div>

                @if($anneeAcademique->statut === 'en_cours')
                    <div class="mt-8 border-t pt-6">
                        <form action="{{ route('admin.annees-academiques.terminer', $anneeAcademique) }}"
                              method="POST"
                              class="flex items-center gap-4">
                            @csrf
                            <button type="submit"
                                    class="bg-red-600 text-white px-4 py-2 rounded-md hover:bg-red-700"
                                    onclick="return confirm('Êtes-vous sûr de vouloir terminer cette année académique ? Cette action est irréversible.')">
                                Terminer l'année académique
                            </button>
                            <p class="text-sm text-gray-500">
                                Cette action verrouillera toutes les données de l'année académique.
                            </p>
                        </form>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
