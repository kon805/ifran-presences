@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <h1 class="text-2xl font-bold mb-8 flex items-center text-blue-900">
            <span class="inline-flex items-center justify-center w-10 h-10 rounded-lg bg-blue-700 mr-3 shadow">
                <i class="fa-solid fa-clipboard-list text-white text-lg"></i>
            </span>
            Mon statut
        </h1>

        <div class="bg-gradient-to-tr from-blue-50 to-white shadow-xl rounded-2xl p-6 mb-8 border border-blue-100">
            <h2 class="text-xl font-semibold mb-4 flex items-center text-blue-800">
                <i class="fa-solid fa-book-open text-blue-500 mr-2"></i>
                Matières où vous êtes en risque d'échec
            </h2>
            @if($matieresDropped->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full bg-white rounded-xl shadow border border-blue-100">
                        <thead class="bg-blue-100">
                            <tr>
                                <th class="py-2 px-4 border-b text-left text-blue-800 font-bold">Matière</th>
                                <th class="py-2 px-4 border-b text-left text-blue-800 font-bold">Professeur</th>
                                <th class="py-2 px-4 border-b text-left text-blue-800 font-bold">Taux d'absence</th>
                                <th class="py-2 px-4 border-b text-left text-blue-800 font-bold">Statut</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($matieresDropped as $matiere)
                                <tr class="hover:bg-blue-50 transition">
                                    <td class="py-2 px-4 border-b font-semibold text-blue-900">{{ $matiere->nom }}</td>
                                    <td class="py-2 px-4 border-b">
                                        @if($matiere->professeurs->count() > 0)
                                            <span class="text-blue-700">{{ $matiere->professeurs->first()->name }}</span>
                                        @else
                                            <span class="text-gray-400">Non assigné</span>
                                        @endif
                                    </td>
                                    <td class="py-2 px-4 border-b">
                                        @php
                                            $totalCours = $matiere->cours->count();
                                            $absenceCount = 0;
                                            foreach ($matiere->cours as $cours) {
                                                foreach ($cours->presences as $presence) {
                                                    if ($presence->etudiant_id == $etudiant->id && $presence->statut == 'absent') {
                                                        $absenceCount++;
                                                        break;
                                                    }
                                                }
                                            }
                                            $tauxAbsence = $totalCours > 0 ? ($absenceCount / $totalCours) * 100 : 0;
                                        @endphp
                                        <span class="font-bold text-blue-700">{{ number_format($tauxAbsence, 2) }}%</span>
                                    </td>
                                    <td class="py-2 px-4 border-b">
                                        <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                            Dropped
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="text-blue-600">Aucune matière où vous êtes en risque d'échec.</p>
            @endif
        </div>


    </div>
</div>
@endsection
