@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto py-8 px-4">
    <h2 class="text-3xl font-extrabold text-red-700 mb-6 flex items-center">
        <i class="fa-solid fa-clock-rotate-left text-red-500 mr-3"></i>
        Historique des justifications
    </h2>

    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <form method="GET" action="{{ route('coordinateur.justifications.history') }}" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <div>
                <label for="date_debut" class="block text-sm font-semibold text-red-700">Date début</label>
                <input type="date" name="date_debut" id="date_debut" value="{{ request('date_debut') }}"
                       class="mt-1 block w-full rounded border-red-200 focus:border-red-500 focus:ring-red-500">
            </div>
            <div>
                <label for="date_fin" class="block text-sm font-semibold text-red-700">Date fin</label>
                <input type="date" name="date_fin" id="date_fin" value="{{ request('date_fin') }}"
                       class="mt-1 block w-full rounded border-red-200 focus:border-red-500 focus:ring-red-500">
            </div>
            <div>
                <label for="etudiant" class="block text-sm font-semibold text-red-700">Étudiant</label>
                <input type="text" name="etudiant" id="etudiant" value="{{ request('etudiant') }}"
                       placeholder="Nom de l'étudiant"
                       class="mt-1 block w-full rounded border-red-200 focus:border-red-500 focus:ring-red-500">
            </div>
            <div>
                <label for="matiere" class="block text-sm font-semibold text-red-700">Matière</label>
                <input type="text" name="matiere" id="matiere" value="{{ request('matiere') }}"
                       placeholder="Nom de la matière"
                       class="mt-1 block w-full rounded border-red-200 focus:border-red-500 focus:ring-red-500">
            </div>
            <div>
                <label for="statut" class="block text-sm font-semibold text-red-700">Statut</label>
                <select name="statut" id="statut"
                        class="mt-1 block w-full rounded border-red-200 focus:border-red-500 focus:ring-red-500">
                    <option value="">Tous</option>
                    <option value="justifiee" {{ request('statut') === 'justifiee' ? 'selected' : '' }}>Justifiée</option>
                    <option value="non_justifiee" {{ request('statut') === 'non_justifiee' ? 'selected' : '' }}>Non justifiée</option>
                </select>
            </div>
            <div class="md:col-span-2 lg:col-span-4 flex justify-end space-x-3 mt-4">
                <a href="{{ route('coordinateur.justifications.history') }}"
                   class="px-4 py-2 rounded text-sm font-semibold bg-red-50 text-red-700 border border-red-200 hover:bg-red-100 transition">Réinitialiser</a>
                <button type="submit"
                        class="px-4 py-2 rounded text-sm font-bold bg-red-600 text-white hover:bg-red-700 transition">Filtrer</button>
            </div>
        </form>
    </div>

    <div class="bg-white px-4 py-3 border-b border-gray-100 rounded-t-lg">
        <span class="text-sm text-red-700 font-semibold">
            Affichage de {{ $justifications->firstItem() ?? 0 }} à {{ $justifications->lastItem() ?? 0 }}
            sur {{ $justifications->total() }} justifications
        </span>
    </div>

    <div class="overflow-x-auto bg-white rounded-b-lg shadow">
        <table class="min-w-full divide-y divide-red-100">
            <thead>
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-bold text-red-700">Date</th>
                    <th class="px-4 py-3 text-left text-xs font-bold text-red-700">Étudiant</th>
                    <th class="px-4 py-3 text-left text-xs font-bold text-red-700">Matière</th>
                    <th class="px-4 py-3 text-left text-xs font-bold text-red-700">Motif</th>
                    <th class="px-4 py-3 text-left text-xs font-bold text-red-700">Statut</th>
                    <th class="px-4 py-3 text-left text-xs font-bold text-red-700">Coordinateur</th>
                </tr>
            </thead>
            <tbody>
                @foreach($justifications as $justification)
                <tr class="hover:bg-red-50 transition">
                    <td class="px-4 py-3">{{ $justification->created_at->format('d/m/Y H:i') }}</td>
                    <td class="px-4 py-3">{{ $justification->presence->etudiant->name }}</td>
                    <td class="px-4 py-3">{{ $justification->presence->cours->matiere->nom }}</td>
                    <td class="px-4 py-3">{{ $justification->motif }}</td>
                    <td class="px-4 py-3">
                        @if($justification->justifiee)
                            <span class="inline-block px-2 py-1 rounded bg-green-100 text-green-700 font-semibold text-xs">Justifiée</span>
                        @else
                            <span class="inline-block px-2 py-1 rounded bg-red-100 text-red-700 font-semibold text-xs">Non justifiée</span>
                        @endif
                    </td>
                    <td class="px-4 py-3">{{ $justification->coordinateur->name }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-6 flex justify-center">
        {{ $justifications->links() }}
    </div>
</div>
@endsection
