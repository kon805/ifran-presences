@extends('layouts.app')
@section('content')
<div class="max-w-3xl mx-auto py-10 px-4">
    <h2 class="text-3xl font-extrabold text-indigo-800 mb-8 flex items-center">
        <i class="fa-solid fa-user-check text-indigo-500 mr-3"></i>
        Saisie des présences <span class="ml-2 text-gray-500 font-medium">({{ $cours->matiere->nom }})</span>
    </h2>
    <form method="POST" action="{{ route('coordinateur.presences.update', $cours->id) }}" class="bg-white rounded-2xl shadow-lg p-8 border border-gray-100">
        @csrf
        @method('PUT')

        <table class="w-full mb-6 rounded-xl overflow-hidden shadow border border-gray-100">
            <thead class="bg-indigo-50">
                <tr>
                    <th class="px-4 py-3 text-left font-semibold text-indigo-700 text-sm"><i class="fa-solid fa-user"></i> Étudiant</th>
                    <th class="px-4 py-3 text-left font-semibold text-indigo-700 text-sm"><i class="fa-solid fa-list-check"></i> Présence</th>
                </tr>
            </thead>
            <tbody>
                @foreach($cours->classe->etudiants as $etudiant)
                    @php
                        $presence = $cours->presences->where('etudiant_id', $etudiant->id)->first();
                    @endphp
                    <tr class="hover:bg-indigo-50 transition">
                        <td class="px-4 py-3 text-gray-800 border-b border-gray-50 flex items-center space-x-2">
                            <i class="fa-solid fa-user-circle text-indigo-400"></i>
                            <span class="font-semibold">{{ $etudiant->name }}</span>
                        </td>
                        <td class="px-4 py-3 border-b border-gray-50">
                            <select name="presences[{{ $etudiant->id }}]" class="w-full p-2 border rounded-lg focus:ring-indigo-500 focus:border-indigo-500 font-semibold text-sm">
                                <option value="présent" {{ $presence?->statut == 'présent' ? 'selected' : '' }}>Présent</option>
                                <option value="retard" {{ $presence?->statut == 'retard' ? 'selected' : '' }}>Retard</option>
                                <option value="absent" {{ $presence?->statut == 'absent' ? 'selected' : '' }}>Absent</option>
                            </select>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="flex justify-end">
            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2 rounded-lg shadow font-bold transition flex items-center">
                <i class="fa-solid fa-floppy-disk mr-2"></i> Enregistrer
            </button>
        </div>
    </form>
</div>
@endsection
