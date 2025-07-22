@extends('layouts.app')
@section('content')
<div class="max-w-4xl mx-auto py-8">
    <h2 class="text-3xl font-extrabold mb-8 flex items-center text-red-700">
        <i class="fa-solid fa-file-circle-question text-red-500 mr-3"></i>
        Justification des absences
    </h2>
    <div class="bg-gradient-to-br from-red-50 to-white rounded-2xl shadow-lg border border-gray-100 overflow-x-auto">
        <table class="min-w-full text-base">
            <thead class="bg-red-50">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-bold text-red-700">Étudiant</th>
                    <th class="px-4 py-3 text-left text-xs font-bold text-red-700">Classe</th>
                    <th class="px-4 py-3 text-left text-xs font-bold text-red-700">Matière</th>
                    <th class="px-4 py-3 text-left text-xs font-bold text-red-700">Date</th>
                    <th class="px-4 py-3 text-left text-xs font-bold text-red-700">Statut</th>
                    <th class="px-4 py-3 text-left text-xs font-bold text-red-700">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($absences as $absence)
                <tr class="hover:bg-red-50 transition">
                    <td class="px-4 py-3">{{ $absence->etudiant->name }}</td>
                    <td class="px-4 py-3">{{ $absence->cours->classe->nom }}</td>
                    <td class="px-4 py-3">{{ $absence->cours->matiere->nom }}</td>
                    <td class="px-4 py-3">{{ \Carbon\Carbon::parse($absence->cours->date)->format('d/m/Y') }}</td>
                    <td class="px-4 py-3">
                        @if($absence->statut === 'absent')
                            <span class="inline-block px-2 py-0.5 rounded bg-red-100 text-red-700 font-semibold">Absent</span>
                        @elseif($absence->statut === 'retard')
                            <span class="inline-block px-2 py-0.5 rounded bg-orange-100 text-orange-700 font-semibold">Retard</span>
                        @else
                            <span class="inline-block px-2 py-0.5 rounded bg-green-100 text-green-700 font-semibold">Justifiée</span>
                        @endif
                    </td>
                    <td class="px-4 py-3">
                        @if($absence->statut === 'absent')
                            <a href="{{ route('coordinateur.justifications.create', $absence->id) }}"
                               class="inline-block px-3 py-1 rounded bg-red-100 text-red-700 font-medium hover:bg-red-200 transition">
                                Justifier
                            </a>
                        @else
                            <span class="text-green-700 font-semibold">Justifiée</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
