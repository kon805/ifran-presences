@extends('layouts.app')
@section('content')
<div class="max-w-4xl mx-auto py-6">
    <h2 class="text-2xl font-bold mb-4">Saisie des présences – {{ $cours->matiere }} – {{ \Carbon\Carbon::parse($cours->date)->format('d-m-Y') }}</h2>
    <form method="POST" action="{{ route('presences.store', $cours->id) }}">
        @csrf
        <table class="w-full border mb-4">
            <thead class="bg-gray-100">
                <tr>
                    <th class="border px-3 py-2">Étudiant</th>
                    <th class="border px-3 py-2">Présence</th>
                </tr>
            </thead>
            <tbody>
                @foreach($etudiants as $etudiant)
                    <tr>
                        <td class="border px-3 py-2">{{ $etudiant->name }}</td>
                        <td class="border px-3 py-2">
                            <select name="presences[{{ $etudiant->id }}]" class="w-full p-1 border rounded">
                                <option value="présent">Présent</option>
                                <option value="retard">Retard</option>
                                <option value="absent">Absent</option>
                            </select>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded">Enregistrer</button>
    </form>
</div>
@endsection
