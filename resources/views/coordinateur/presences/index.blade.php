@extends('layouts.app')
@section('content')
<div class="max-w-6xl mx-auto py-6">
    <h2 class="text-2xl font-bold mb-4">Consultation des présences</h2>
    <table class="w-full border text-sm">
        <thead class="bg-gray-100">
            <tr>
                <th class="border px-2 py-2">Classe</th>
                <th class="border px-2 py-2">Matière</th>
                <th class="border px-2 py-2">Professeur</th>
                <th class="border px-2 py-2">Date</th>
                <th class="border px-2 py-2">Type</th>
                <th class="border px-2 py-2">Présences</th>
            </tr>
        </thead>
        <tbody>
            @foreach($cours as $c)
                <tr>
                   <td class="border px-2 py-2">
                        {{ $c->classe ? $c->classe->nom : 'Non attribuée' }}
                    </td>
                    <td class="border px-2 py-2">{{ $c->matiere ? $c->matiere->nom : 'Non attribuée' }}</td>
                    <td class="border px-2 py-2">{{ $c->professeur->name }}</td>
                    <td class="border px-2 py-2">{{ \Carbon\Carbon::parse($c->date)->format('d-m-Y') }}</td>
                    <td class="border px-2 py-2">
                        @forelse($c->types as $type)
                            @if($type->code === 'workshop')
                                <span class="text-orange-600 font-semibold">{{ $type->nom }}</span>
                            @elseif($type->code === 'e-learning')
                                <span class="text-blue-600 font-semibold">{{ $type->nom }}</span>
                            @else
                                <span class="text-gray-600">{{ $type->nom }}</span>
                            @endif
                        @empty
                            <span class="text-gray-400">Non défini</span>
                        @endforelse
                    </td>
                    <td class="border px-2 py-2">
                        <a href="{{ route('coordinateur.presences.show', $c->id) }}" class="text-indigo-600 underline">Voir</a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="mt-4">
        {{ $cours->links() }}
    </div>
</div>
@endsection
