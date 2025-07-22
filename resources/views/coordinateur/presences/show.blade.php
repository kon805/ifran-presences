@extends('layouts.app')
@section('content')
<div class="max-w-4xl mx-auto py-8 px-3">
    <h2 class="text-3xl font-extrabold text-indigo-800 mb-2 flex items-center">
        <i class="fa-solid fa-user-check text-indigo-500 mr-3"></i>
        Présences – {{ $cours->matiere->nom ?? 'Matière non définie' }} ({{ $cours->classe->nom ?? 'Classe non définie' }})
    </h2>
    <div class="mb-6 text-sm text-gray-700 bg-indigo-50 rounded-xl px-4 py-3 flex flex-col md:flex-row md:items-center md:gap-6">
        <span>
            <i class="fa-regular fa-calendar text-indigo-400 mr-1"></i>
            {{ \Carbon\Carbon::parse($cours->date)->format('d/m/Y') }} –
            <i class="fa-regular fa-clock text-indigo-400 ml-3 mr-1"></i>
            {{ $cours->heure_debut }} à {{ $cours->heure_fin }}
        </span>
        <span class="mt-2 md:mt-0">
            <i class="fa-solid fa-user-tie text-indigo-400 mr-1"></i>
            Professeur : <span class="font-semibold">{{ $cours->professeur->name ?? 'Non défini' }}</span>
        </span>
    </div>

    <div class="bg-white rounded-2xl shadow-lg overflow-x-auto border border-gray-100">
        <table class="min-w-full text-sm divide-y divide-gray-200">
            <thead class="bg-indigo-50">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-indigo-700 uppercase tracking-wider">Étudiant</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-indigo-700 uppercase tracking-wider">Statut</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-100">
                @if($cours->classe && $cours->classe->etudiants)
                    @foreach($cours->classe->etudiants as $etudiant)
                        @php
                            $presence = $cours->presences->where('etudiant_id', $etudiant->id)->first();
                        @endphp
                        <tr class="hover:bg-indigo-50 transition">
                            <td class="px-4 py-4 font-medium text-gray-800 flex items-center">
                                <i class="fa-solid fa-user-graduate text-indigo-400 mr-2"></i>
                                {{ $etudiant->name }}
                            </td>
                            <td class="px-4 py-4">
                                @if($presence)
                                    @if($presence->statut === 'present')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-green-100 text-green-800 border border-green-200">
                                            <i class="fa-solid fa-check mr-1"></i> Présent
                                        </span>
                                    @elseif($presence->statut === 'absent')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-red-100 text-red-800 border border-red-200">
                                            <i class="fa-solid fa-xmark mr-1"></i> Absent
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-800 border border-yellow-200">
                                            <i class="fa-solid fa-question mr-1"></i> {{ ucfirst($presence->statut) }}
                                        </span>
                                    @endif
                                @else
                                    @if(auth()->user()->role === 'coordinateur')
                                        <a href="{{ route('coordinateur.presences.edit', $cours->id) }}"
                                           class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-blue-100 text-blue-700 hover:bg-blue-200 border border-blue-200 transition">
                                            <i class="fa-solid fa-plus mr-1"></i> Ajouter
                                        </a>
                                    @else
                                        <span class="text-gray-400 italic">Non renseigné</span>
                                    @endif
                                @endif
                            </td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="2" class="px-4 py-4 text-center text-gray-500 italic">
                            Aucun étudiant trouvé pour cette classe
                        </td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>
</div>
@endsection
