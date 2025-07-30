@extends('layouts.app')
@section('content')
    <div class="py-12 bg-white min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-lg rounded-2xl p-8 border border-gray-200">
                <div class="flex justify-between items-center mb-8">
                    <h2 class="text-2xl font-bold text-gray-800">Années Académiques</h2>
                    <a href="{{ route('admin.annees-academiques.create') }}"
                       class="bg-gray-900 text-white px-5 py-2 rounded-lg hover:bg-gray-800 transition">
                        Nouvelle Année Académique
                    </a>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead>
                            <tr>
                                <th class="px-6 py-3 bg-gray-50 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                    Année
                                </th>
                                <th class="px-6 py-3 bg-gray-50 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                    Période
                                </th>
                                <th class="px-6 py-3 bg-gray-50 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                    Statut
                                </th>
                                <th class="px-6 py-3 bg-gray-50 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                    Classes
                                </th>
                                <th class="px-6 py-3 bg-gray-50 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                    Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-100">
                            @foreach($annees as $annee)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-bold text-gray-700">{{ $annee->annee }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-600">
                                        {{ $annee->date_debut }} -
                                        {{ $annee->date_fin }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-3 py-1 inline-flex text-xs font-semibold rounded-full
                                        {{ $annee->statut === 'en_cours'
                                            ? 'bg-green-50 text-green-700 border border-green-200'
                                            : 'bg-gray-100 text-gray-700 border border-gray-200' }}">
                                        {{ $annee->statut === 'en_cours' ? 'En cours' : 'Terminée' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                    {{ $annee->classes_count }} classes
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <a href="{{ route('admin.annees-academiques.show', $annee) }}"
                                       class="text-gray-900 hover:text-gray-600 font-semibold transition">
                                        Détails
                                    </a>
                                    @if($annee->statut === 'en_cours')
                                        <form action="{{ route('admin.annees-academiques.terminer', $annee) }}"
                                              method="POST"
                                              class="inline-block ml-3">
                                            @csrf
                                            <button type="submit"
                                                    class="text-red-600 hover:text-red-900 font-semibold transition"
                                                    onclick="return confirm('Êtes-vous sûr de vouloir terminer cette année académique ? Cette action est irréversible.')">
                                                Terminer
                                            </button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
