@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="px-4 py-6 sm:px-0">
        <h2 class="text-2xl font-bold mb-4">Historique des justifications</h2>

        <div class="bg-white p-6 rounded-lg shadow mb-6">
            <form method="GET" action="{{ route('coordinateur.justifications.history') }}" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <div>
                    <label for="date_debut" class="block text-sm font-medium text-gray-700">Date début</label>
                    <input type="date"
                           name="date_debut"
                           id="date_debut"
                           value="{{ request('date_debut') }}"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                </div>

                <div>
                    <label for="date_fin" class="block text-sm font-medium text-gray-700">Date fin</label>
                    <input type="date"
                           name="date_fin"
                           id="date_fin"
                           value="{{ request('date_fin') }}"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                </div>

                <div>
                    <label for="etudiant" class="block text-sm font-medium text-gray-700">Étudiant</label>
                    <input type="text"
                           name="etudiant"
                           id="etudiant"
                           value="{{ request('etudiant') }}"
                           placeholder="Nom de l'étudiant"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                </div>

                <div>
                    <label for="matiere" class="block text-sm font-medium text-gray-700">Matière</label>
                    <input type="text"
                           name="matiere"
                           id="matiere"
                           value="{{ request('matiere') }}"
                           placeholder="Nom de la matière"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                </div>

                <div>
                    <label for="statut" class="block text-sm font-medium text-gray-700">Statut</label>
                    <select name="statut"
                            id="statut"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        <option value="">Tous</option>
                        <option value="justifiee" {{ request('statut') === 'justifiee' ? 'selected' : '' }}>Justifiée</option>
                        <option value="non_justifiee" {{ request('statut') === 'non_justifiee' ? 'selected' : '' }}>Non justifiée</option>
                    </select>
                </div>

                <div class="md:col-span-2 lg:col-span-4 flex justify-end space-x-3">
                    <a href="{{ route('coordinateur.justifications.history') }}"
                       class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                        Réinitialiser
                    </a>
                    <button type="submit"
                            class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700">
                        Filtrer
                    </button>
                </div>
            </form>
        </div>

        <div class="bg-white px-4 py-3 border-b border-gray-200 sm:px-6">
            <div class="text-sm text-gray-700">
                Affichage de <span class="font-medium">{{ $justifications->firstItem() ?? 0 }}</span> à
                <span class="font-medium">{{ $justifications->lastItem() ?? 0 }}</span> sur
                <span class="font-medium">{{ $justifications->total() }}</span> justifications
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead>
                    <tr>
                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Date
                        </th>
                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Étudiant
                        </th>
                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Matière
                        </th>
                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Motif
                        </th>
                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Statut
                        </th>
                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Coordinateur
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($justifications as $justification)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $justification->created_at->format('d/m/Y H:i') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $justification->presence->etudiant->name }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $justification->presence->cours->matiere->nom }}
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-900">
                            {{ $justification->motif }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            @if($justification->justifiee)
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                    Justifiée
                                </span>
                            @else
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                    Non justifiée
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $justification->coordinateur->name }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $justifications->links() }}
        </div>
    </div>
</div>
@endsection
