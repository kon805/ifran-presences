@extends('layouts.app')
@section('content')
<div class="max-w-6xl mx-auto py-6">
    <h2 class="text-2xl font-bold mb-4">Justification des absences</h2>
    <div class="bg-white shadow-sm rounded-lg">
        <table class="w-full border text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-2 text-left">Étudiant</th>
                    <th class="px-4 py-2 text-left">Classe</th>
                    <th class="px-4 py-2 text-left">Matière</th>
                    <th class="px-4 py-2 text-left">Date</th>
                    <th class="px-4 py-2 text-left">Statut</th>
                    <th class="px-4 py-2 text-left">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @foreach($absences as $absence)
                <tr>
                    <td class="px-4 py-3">{{ $absence->etudiant->name }}</td>
                    <td class="px-4 py-3">{{ $absence->cours->classe->nom }}</td>
                    <td class="px-4 py-3">{{ $absence->cours->matiere->nom }}</td>
                    <td class="px-4 py-3">{{ \Carbon\Carbon::parse($absence->cours->date)->format('d/m/Y') }}</td>
                    <td class="px-4 py-3">
                        @if($absence->statut === 'absent')
                            <span class="text-red-600 font-medium">Absent</span>
                        @elseif($absence->statut === 'retard')
                            <span class="text-orange-600 font-medium">Retard</span>
                        @endif
                    </td>
                    <td class="px-4 py-3">
                        @if($absence->statut ==='absent')
                                <a
                                href="{{ route('coordinateur.justifications.create', $absence->id) }}"
                                class="text-indigo-600 hover:text-indigo-900 font-medium"
                            >
                                Justifier
                            </a>
                        @else

                            <span class="text-green-600 font-medium">Justifiée</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
