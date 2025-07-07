@extends('layouts.app')
@section('content')
<div class="max-w-5xl mx-auto py-6">
    <h2 class="text-2xl font-bold mb-4">Mes séances programmées</h2>
    @if(session('success'))
        <div class="mb-4 p-2 bg-green-100 text-green-700">{{ session('success') }}</div>
    @endif
    <table class="w-full border">
        <thead class="bg-gray-100">
            <tr>
                <th class="border px-3 py-2">Classe</th>
                <th class="border px-3 py-2">Matière</th>
                <th class="border px-3 py-2">Date</th>
                <th class="border px-3 py-2">Heure</th>
                <th class="border px-3 py-2">Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach($cours as $c)
                <tr>
                    <td class="border px-3 py-2">{{ $c->classe->nom }}</td>
                    <td class="border px-3 py-2">{{ $c->matiere }}</td>
                    <td class="border px-3 py-2">{{ \Carbon\Carbon::parse($c->date)->format('d-m-Y') }}</td>
                    <td class="border px-3 py-2">{{ $c->heure_debut }} - {{ $c->heure_fin }}</td>
                    <td class="border px-3 py-2">
                        <a href="{{ route('presences.edit', $c->id) }}" class="text-indigo-600 underline">Saisir présences</a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
