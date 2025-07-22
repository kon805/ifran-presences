@extends('layouts.app')
@section('content')
<div class="max-w-4xl mx-auto py-8 px-2">
    <h2 class="text-3xl font-extrabold text-gray-800 mb-6 flex items-center">
        <i class="fa-solid fa-book-open text-indigo-500 mr-3 text-2xl"></i>
        Mes matières
    </h2>
    <div class="bg-white rounded-2xl shadow-lg overflow-x-auto border border-gray-100">
        <table class="min-w-full divide-y divide-gray-200 text-sm">
            <thead class="bg-indigo-50">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-indigo-700 uppercase tracking-wider">Matière</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-indigo-700 uppercase tracking-wider">Statut</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-indigo-700 uppercase tracking-wider">Action</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-100">
                @foreach($matieres as $matiere)
                <tr class="hover:bg-indigo-50 transition">
                    <td class="px-4 py-4 whitespace-nowrap font-bold text-indigo-800 flex items-center">
                        <i class="fa-solid fa-book text-indigo-400 mr-2"></i>
                        {{ $matiere->nom }}
                    </td>
                    <td class="px-4 py-4 whitespace-nowrap">
                        @if($matiere->etudiants->isNotEmpty())
                            @php $pivot = $matiere->etudiants->first()->pivot; @endphp
                            @if($pivot->dropped)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-red-100 text-red-700 border border-red-200">
                                    <i class="fa-solid fa-times-circle mr-1"></i>
                                    Droppé (présence < 70%)
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-green-100 text-green-700 border border-green-200">
                                    <i class="fa-solid fa-check-circle mr-1"></i>
                                    Autorisé
                                </span>
                            @endif
                        @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-gray-100 text-gray-600 border border-gray-200">
                                <i class="fa-solid fa-question-circle mr-1"></i>
                                Statut non disponible
                            </span>
                        @endif
                    </td>
                    <td class="px-4 py-4 whitespace-nowrap">
                        @if($matiere->etudiants->isNotEmpty())
                            @php $pivot = $matiere->etudiants->first()->pivot; @endphp
                            @if($pivot->dropped)
                                <span class="text-xs text-gray-500">Vous devrez reprendre ce module.</span>
                            @else
                                <a href="{{ route('etudiant.matieres.show', $matiere) }}" class="inline-block px-3 py-1 rounded-lg text-xs font-semibold bg-indigo-100 text-indigo-700 hover:bg-indigo-200 transition">
                                    <i class="fa-solid fa-eye mr-1"></i>
                                    Voir les détails
                                </a>
                            @endif
                        @else
                            <span class="text-xs text-gray-400">-</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
