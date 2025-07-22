@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto py-8 px-2">
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-3xl font-extrabold text-gray-800 flex items-center">
            <i class="fa-solid fa-book-open text-indigo-500 mr-3 text-2xl"></i>
            {{ $matiere->nom }}
        </h2>
        <a href="{{ route('etudiant.matieres.index') }}" class="inline-flex items-center text-indigo-600 hover:text-indigo-900 text-sm font-semibold transition">
            <i class="fa-solid fa-arrow-left mr-1"></i>
            Retour aux matières
        </a>
    </div>

    <!-- Professeurs -->
    <div class="bg-white rounded-2xl shadow-lg overflow-hidden mb-6 border border-gray-100">
        <div class="p-6">
            <h3 class="text-lg font-bold mb-4 flex items-center">
                <i class="fa-solid fa-chalkboard-user text-indigo-400 mr-2"></i>
                Professeurs
            </h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                @forelse($matiere->professeurs as $professeur)
                    <div class="bg-indigo-50 rounded-xl p-4 flex flex-col shadow hover:shadow-md transition">
                        <div class="font-semibold text-indigo-800 flex items-center">
                            <i class="fa-solid fa-user-tie text-indigo-400 mr-2"></i>
                            {{ $professeur->name }}
                        </div>
                        @if($professeur->email)
                            <div class="text-sm text-gray-600 mt-1 flex items-center">
                                <i class="fa-solid fa-envelope text-gray-400 mr-1"></i>
                                {{ $professeur->email }}
                            </div>
                        @endif
                    </div>
                @empty
                    <div class="col-span-full text-gray-500 py-2 text-center">Aucun professeur pour cette matière</div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Séances programmées -->
    <div class="bg-white rounded-2xl shadow-lg overflow-hidden border border-gray-100">
        <div class="p-6">
            <h3 class="text-lg font-bold mb-4 flex items-center">
                <i class="fa-solid fa-calendar-days text-indigo-400 mr-2"></i>
                Séances programmées
            </h3>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 text-sm">
                    <thead class="bg-indigo-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-indigo-700 uppercase tracking-wider">Date</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-indigo-700 uppercase tracking-wider">Horaire</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-indigo-700 uppercase tracking-wider">Type</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-indigo-700 uppercase tracking-wider">Professeur</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-100">
                        @forelse($matiere->cours as $cours)
                            <tr class="hover:bg-indigo-50 transition">
                                <td class="px-4 py-4 whitespace-nowrap text-gray-700 font-semibold flex items-center">
                                    <i class="fa-regular fa-calendar text-indigo-400 mr-2"></i>
                                    {{ \Carbon\Carbon::parse($cours->date)->format('d/m/Y') }}
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap text-gray-600 flex items-center">
                                    <i class="fa-regular fa-clock text-indigo-400 mr-2"></i>
                                    {{ $cours->heure_debut }} - {{ $cours->heure_fin }}
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap">
                                    @foreach($cours->types as $type)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold shadow
                                            @if($type->code === 'workshop') bg-orange-100 text-orange-800 border border-orange-200
                                            @elseif($type->code === 'e-learning') bg-blue-100 text-blue-800 border border-blue-200
                                            @else bg-gray-100 text-gray-800 border border-gray-200
                                            @endif">
                                            <i class="fa-solid fa-tag mr-1 text-xs opacity-60"></i>
                                            {{ $type->nom }}
                                        </span>
                                    @endforeach
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap text-gray-700 flex items-center">
                                    <i class="fa-solid fa-user-tie text-indigo-400 mr-2"></i>
                                    {{ $cours->professeur->name }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-4 py-4 text-center text-gray-500 font-semibold">
                                    <i class="fa-regular fa-face-frown text-xl mr-2"></i>
                                    Aucune séance programmée pour le moment
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
