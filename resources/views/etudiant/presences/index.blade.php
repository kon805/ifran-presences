@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto py-6 px-2">
    <h2 class="text-3xl font-extrabold text-gray-800 mb-6 flex items-center">
        <i class="fa-solid fa-user-check text-indigo-500 mr-3 text-2xl"></i>
        Mes présences
    </h2>

    <!-- Table desktop -->
    <div class="bg-gradient-to-br from-indigo-50 to-white rounded-2xl shadow-lg overflow-hidden border border-gray-100 hidden sm:block">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-indigo-100">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-indigo-700 tracking-wider uppercase">Date</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-indigo-700 tracking-wider uppercase">Matière</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-indigo-700 tracking-wider uppercase">Statut</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-indigo-700 tracking-wider uppercase">Justification</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-100">
                @forelse($presences as $presence)
                    <tr class="hover:bg-indigo-50 transition">
                        <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-700 font-semibold">
                            <span class="inline-flex items-center">
                                <i class="fa-regular fa-calendar text-indigo-400 mr-2"></i>
                                {{ \Carbon\Carbon::parse($presence->cours->date)->format('d/m/Y') }}
                                <span class="text-gray-500 ml-2">
                                    ({{ $presence->cours->heure_debut }} - {{ $presence->cours->heure_fin }})
                                </span>
                            </span>
                        </td>
                        <td class="px-4 py-4 whitespace-nowrap text-sm font-bold text-indigo-600">
                            <i class="fa-solid fa-book-open mr-2 text-indigo-300"></i>
                            {{ $presence->cours->matiere->nom ?? 'Matière non définie' }}
                        </td>
                        <td class="px-4 py-4 whitespace-nowrap">
                            @if($presence->statut === 'present')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    <i class="fa-solid fa-check mr-1"></i>
                                    Présent
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                    <i class="fa-solid fa-xmark mr-1"></i>
                                    Absent
                                </span>
                            @endif
                        </td>
                        <td class="px-4 py-4 whitespace-nowrap text-sm">
                            @if(!$presence->present)
                                @if($presence->justification)
                                    @if($presence->justification->justifiee == true)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            <i class="fa-solid fa-check mr-1"></i>
                                            Justifiée
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                            <i class="fa-solid fa-clock mr-1"></i>
                                            En attente
                                        </span>
                                    @endif
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                        <i class="fa-solid fa-xmark mr-1"></i>
                                        Non justifiée
                                    </span>
                                @endif
                            @else
                                <span class="text-gray-400">-</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-4 py-4 text-center text-gray-500 font-semibold">
                            <i class="fa-regular fa-face-frown text-xl mr-2"></i>
                            Aucun cours programmé
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Cartes mobile -->
    <div class="sm:hidden">
        @forelse($presences as $presence)
            <div class="mb-4 rounded-xl shadow bg-white p-4 border border-indigo-50">
                <div class="flex items-center mb-2">
                    <i class="fa-regular fa-calendar text-indigo-400 mr-2"></i>
                    <span class="text-sm font-semibold text-gray-700">{{ \Carbon\Carbon::parse($presence->cours->date)->format('d/m/Y') }}</span>
                    <span class="ml-auto text-xs text-gray-500">
                        <i class="fa-regular fa-clock mr-1"></i>
                        {{ $presence->cours->heure_debut }} - {{ $presence->cours->heure_fin }}
                    </span>
                </div>
                <div class="flex items-center mb-2">
                    <i class="fa-solid fa-book-open text-indigo-300 mr-2"></i>
                    <span class="font-bold text-indigo-600">{{ $presence->cours->matiere->nom ?? 'Matière non définie' }}</span>
                </div>
                <div class="flex items-center mb-2">
                    @if($presence->statut === 'present')
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 mr-2">
                            <i class="fa-solid fa-check mr-1"></i>
                            Présent
                        </span>
                    @else
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 mr-2">
                            <i class="fa-solid fa-xmark mr-1"></i>
                            Absent
                        </span>
                    @endif
                    @if(!$presence->present)
                        @if($presence->justification)
                            @if($presence->justification->justifiee == 1)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    <i class="fa-solid fa-check mr-1"></i>
                                    Justifiée
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                    <i class="fa-solid fa-clock mr-1"></i>
                                    En attente
                                </span>
                            @endif
                        @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                <i class="fa-solid fa-xmark mr-1"></i>
                                Non justifiée
                            </span>
                        @endif
                    @else
                        <span class="text-gray-400 ml-2">-</span>
                    @endif
                </div>
            </div>
        @empty
            <div class="text-center text-gray-500 py-8">
                <i class="fa-regular fa-face-frown text-xl mr-2"></i>
                Aucun cours programmé
            </div>
        @endforelse
    </div>
</div>
@endsection
