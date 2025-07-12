@extends('layouts.app')
@section('content')
<div class="max-w-4xl mx-auto py-8">
    <h2 class="text-2xl font-bold mb-4">Liste de mes absences</h2>
    <table class="w-full border text-sm">
        <thead>
            <tr>
                <th>Matière</th>
                <th>Date</th>
                <th>Type</th>
                <th>Statut</th>
                <th>Justification</th>
            </tr>
        </thead>
        <tbody>
            @foreach($absences as $absence)
            <tr>
                <td>{{ $absence->cours->matiere->nom ?? 'Matière inconnue' }}</td>
                <td>{{ \Carbon\Carbon::parse($absence->cours->date)->format('d-m-Y') }}</td>
                <td>
                    @php
                        $matiere = strtolower($absence->cours->matiere->nom ?? '');
                    @endphp
                    @if(Str::contains($matiere, 'workshop'))
                        <span class="text-orange-600 font-semibold">WORKSHOP</span>
                    @elseif(Str::contains($matiere, 'e-learning'))
                        <span class="text-blue-600 font-semibold">E-LEARNING</span>
                    @else
                        <span class="text-gray-600">Présentiel</span>
                    @endif
                </td>
                <td>{{ ucfirst($absence->statut) }}</td>
                <td>
                    @if($absence->justification)
                        <span class="text-green-600 font-semibold">Justifiée</span>
                        <div class="text-xs text-gray-500">{{ $absence->justification->motif }}</div>
                    @else
                        <span class="text-red-600 font-semibold">Non justifiée</span>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
