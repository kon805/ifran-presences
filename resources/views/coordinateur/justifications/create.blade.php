@extends('layouts.app')
@section('content')
<div class="max-w-lg mx-auto py-8">
    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-2xl font-bold mb-6">Justifier une absence</h2>

        <div class="mb-6">
            <div class="text-gray-700">
                <p><strong>Étudiant :</strong> {{ $presence->etudiant->name }}</p>
                <p><strong>Date :</strong> {{ \Carbon\Carbon::parse($presence->cours->date)->format('d/m/Y') }}</p>
                <p><strong>Matière :</strong> {{ $presence->cours->matiere->nom }}</p>
                <p><strong>Type d'absence :</strong> {{ ucfirst($presence->statut) }}</p>
            </div>
        </div>

        <form method="POST" action="{{ route('coordinateur.justifications.store', $presence->id) }}" class="space-y-4">
            @csrf

            @if($errors->any())
                <div class="bg-red-50 text-red-500 p-4 rounded mb-4">
                    <ul class="list-disc list-inside">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div>
                <label for="motif" class="block font-medium text-gray-700 mb-2">Motif de la justification</label>
                <textarea
                    id="motif"
                    name="motif"
                    rows="4"
                    class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500"
                    required
                >{{ old('motif') }}</textarea>
            </div>

            <div class="mb-4">
                <label class="inline-flex items-center">
                    <input type="checkbox"
                           name="justifiee"
                           value="1"
                           class="form-checkbox h-5 w-5 text-indigo-600"
                           {{ old('justifiee', true) ? 'checked' : '' }}>
                    <span class="ml-2">Marquer comme justifiée</span>
                </label>
            </div>

            <div class="flex justify-end space-x-3">
                <a
                    href="{{ url()->previous() }}"
                    class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50"
                >
                    Annuler
                </a>
                <button
                    type="submit"
                    class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                >
                    Justifier l'absence
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
