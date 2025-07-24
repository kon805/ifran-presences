@extends('layouts.app')
@section('content')
<div class="max-w-5xl mx-auto py-10">
    <h2 class="text-4xl font-black mb-8 flex items-center text-red-700 tracking-tight bg-gradient-to-r from-red-400 to-red-700 bg-clip-text text-transparent drop-shadow-md">
        <i class="fa-solid fa-file-circle-question text-red-500 mr-4 text-3xl"></i>
        Justification des absences
    </h2>

    <!-- Filtres -->
    <div class="bg-white p-6 rounded-2xl shadow-xl mb-10 border-2 border-red-100 flex flex-col md:flex-row gap-4 items-end">
        <form method="GET" action="{{ route('coordinateur.justifications.index') }}" class="flex flex-col md:flex-row w-full gap-4">
            <div class="flex-1">
                <label for="etudiant" class="block text-sm font-semibold text-red-700 mb-2">Étudiant</label>
                <select id="etudiant" name="etudiant" class="w-full rounded-xl border-2 border-red-200 shadow focus:border-red-400 focus:ring focus:ring-red-100 focus:ring-opacity-40 px-4 py-2 transition">
                    <option value="">Tous les étudiants</option>
                    @foreach($etudiants ?? [] as $etudiantOption)
                        <option value="{{ $etudiantOption->id }}" {{ request('etudiant') == $etudiantOption->id ? 'selected' : '' }}>
                            {{ $etudiantOption->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="flex-1">
                <label for="classe" class="block text-sm font-semibold text-red-700 mb-2">Classe</label>
                <select id="classe" name="classe" class="w-full rounded-xl border-2 border-red-200 shadow focus:border-red-400 focus:ring focus:ring-red-100 focus:ring-opacity-40 px-4 py-2 transition">
                    <option value="">Toutes les classes</option>
                    @foreach($classes ?? [] as $classeOption)
                        <option value="{{ $classeOption->id }}" {{ request('classe') == $classeOption->id ? 'selected' : '' }}>
                            {{ $classeOption->nom }} (S{{ $classeOption->semestre }})
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="flex items-end gap-2">
                <button type="submit" class="bg-gradient-to-r from-red-500 to-red-700 hover:from-red-700 hover:to-red-900 text-white px-6 py-2 rounded-xl shadow border-2 border-red-400 font-bold flex items-center transition-all duration-150">
                    <i class="fas fa-filter mr-2"></i> Filtrer
                </button>
                @if(request()->has('etudiant') || request()->has('classe'))
                    <a href="{{ route('coordinateur.justifications.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-900 px-4 py-2 rounded-xl shadow border border-gray-300 font-bold flex items-center transition-all duration-150">
                        <i class="fas fa-times"></i>
                    </a>
                @endif
            </div>
        </form>
    </div>

    <div class="rounded-3xl shadow-2xl border-none overflow-hidden bg-gradient-to-tr from-red-100 via-white to-red-50 relative">
        <div class="absolute inset-0 pointer-events-none opacity-10 bg-[radial-gradient(circle,rgba(244,63,94,0.2)_30%,transparent_90%)]"></div>
        <table class="min-w-full text-base relative z-10">
            <thead>
                <tr>
                    <th class="px-6 py-4 text-left text-xs font-extrabold text-red-700 uppercase tracking-widest bg-gradient-to-r from-red-50 to-white">Étudiant</th>
                    <th class="px-6 py-4 text-left text-xs font-extrabold text-red-700 uppercase tracking-widest bg-gradient-to-r from-red-50 to-white">Classe</th>
                    <th class="px-6 py-4 text-left text-xs font-extrabold text-red-700 uppercase tracking-widest bg-gradient-to-r from-red-50 to-white">Matière</th>
                    <th class="px-6 py-4 text-left text-xs font-extrabold text-red-700 uppercase tracking-widest bg-gradient-to-r from-red-50 to-white">Date</th>
                    <th class="px-6 py-4 text-left text-xs font-extrabold text-red-700 uppercase tracking-widest bg-gradient-to-r from-red-50 to-white">Statut</th>
                    <th class="px-6 py-4 text-left text-xs font-extrabold text-red-700 uppercase tracking-widest bg-gradient-to-r from-red-50 to-white">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($absences as $absence)
                <tr class="hover:bg-gradient-to-r hover:from-red-50 hover:to-white transition">
                    <td class="px-6 py-4 font-semibold text-red-900">{{ $absence->etudiant->name }}</td>
                    <td class="px-6 py-4">{{ $absence->cours->classe->nom }}</td>
                    <td class="px-6 py-4">{{ $absence->cours->matiere->nom }}</td>
                    <td class="px-6 py-4">{{ \Carbon\Carbon::parse($absence->cours->date)->format('d/m/Y') }}</td>
                    <td class="px-6 py-4">
                        @if($absence->statut === 'absent')
                            <span class="inline-block px-3 py-1.5 rounded-full bg-gradient-to-r from-red-100 via-red-200 to-red-100 text-red-700 font-bold shadow">Absent</span>
                        @elseif($absence->statut === 'retard')
                            <span class="inline-block px-3 py-1.5 rounded-full bg-gradient-to-r from-orange-100 via-orange-200 to-orange-100 text-orange-700 font-bold shadow">Retard</span>
                        @else
                            <span class="inline-block px-3 py-1.5 rounded-full bg-gradient-to-r from-green-100 via-green-200 to-green-100 text-green-700 font-bold shadow">Justifiée</span>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        @if($absence->statut === 'absent')
                            <a href="{{ route('coordinateur.justifications.create', $absence->id) }}"
                               class="inline-block px-5 py-1.5 rounded-xl bg-gradient-to-r from-red-200 to-red-300 text-red-800 font-bold hover:from-red-400 hover:to-red-600 hover:text-white shadow transition-all duration-150">
                                <i class="fa-solid fa-pen-to-square mr-1"></i> Justifier
                            </a>
                        @else
                            <span class="text-green-700 font-extrabold"><i class="fa-solid fa-check-circle mr-1"></i>Justifiée</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-10 text-center text-lg text-red-400 font-semibold">Aucune absence à afficher.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
