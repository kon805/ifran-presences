@extends('layouts.app')
@section('content')
<div class="max-w-lg mx-auto py-8">
    <div class="bg-gradient-to-br from-red-50 to-white rounded-2xl shadow-lg p-8 border border-gray-100">
        <h2 class="text-3xl font-extrabold mb-8 text-red-700 flex items-center">
            <i class="fa-solid fa-file-circle-question text-red-500 mr-3"></i>
            Justifier une absence
        </h2>

        <div class="mb-8 text-base text-gray-700 space-y-1">
            <p><strong>Étudiant :</strong> {{ $presence->etudiant->name }}</p>
            <p><strong>Date :</strong> {{ \Carbon\Carbon::parse($presence->cours->date)->format('d/m/Y') }}</p>
            <p><strong>Matière :</strong> {{ $presence->cours->matiere->nom }}</p>
            <p><strong>Type d'absence :</strong> {{ ucfirst($presence->statut) }}</p>
        </div>

        <form method="POST" action="{{ route('coordinateur.justifications.store', $presence->id) }}" class="space-y-6">
            @csrf

            @if($errors->any())
                <div class="bg-red-100 text-red-800 p-4 rounded-lg mb-2 border border-red-200">
                    <ul class="list-disc list-inside space-y-1">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div>
                <label for="motif" class="block font-semibold text-red-700 mb-2">Motif de la justification</label>
                <textarea
                    id="motif"
                    name="motif"
                    rows="4"
                    class="w-full border border-red-200 rounded-lg shadow-sm focus:border-red-500 focus:ring-2 focus:ring-red-200"
                    required
                >{{ old('motif') }}</textarea>
            </div>

            <div>
                <label class="inline-flex items-center">
                    <input type="checkbox"
                           name="justifiee"
                           value="1"
                           class="form-checkbox h-5 w-5 text-red-600"
                           {{ old('justifiee', true) ? 'checked' : '' }}>
                    <span class="ml-2 text-red-700">Marquer comme justifiée</span>
                </label>
            </div>

            <div class="flex justify-end gap-3">
                <a
                    href="{{ url()->previous() }}"
                    class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition"
                >
                    Annuler
                </a>
                <button
                    type="submit"
                    class="px-5 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 font-semibold shadow transition"
                >
                    Justifier l'absence
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
