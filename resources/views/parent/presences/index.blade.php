@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto py-12 px-4">
    <h2 class="text-4xl font-black text-transparent bg-gradient-to-r from-blue-900 via-blue-600 to-blue-400 bg-clip-text mb-10 flex items-center drop-shadow">
        <i class="fa-solid fa-children text-blue-600 mr-4"></i>
        Présences de mes enfants
    </h2>

    @forelse($enfants as $enfant)
    <div
        class="relative bg-gradient-to-br from-blue-50 via-white to-blue-100 rounded-3xl shadow-2xl mb-12 border border-blue-200 overflow-hidden"
        x-data="{ open: false }"
    >
        <!-- Bandeau flottant décoratif -->
        <div class="absolute right-0 top-0 h-20 w-32 bg-gradient-to-l from-blue-300 to-blue-50 opacity-20 rounded-bl-3xl z-0"></div>
        <!-- En-tête avec infos de base et bouton -->
        <div class="relative z-10 bg-gradient-to-r from-blue-50 to-white p-6 flex items-center justify-between cursor-pointer group"
             @click="open = !open">
            <div class="flex items-center space-x-7">
                <div class="relative">
                    <img class="h-16 w-16 rounded-full border-4 border-blue-400 shadow-xl transition-transform group-hover:scale-105"
                         src="{{ $enfant->profile_photo_url }}" alt="{{ $enfant->name }}">
                    <span class="absolute -bottom-1 -right-1 block h-4 w-4 rounded-full border-2 border-white bg-gradient-to-r from-green-400 to-green-600"></span>
                </div>
                <div>
                    <h3 class="text-2xl font-black text-gray-900">{{ $enfant->name }}</h3>
                    <p class="text-sm text-gray-500">{{ $enfant->email }}</p>
                </div>
            </div>
            <button class="text-blue-500 hover:text-blue-700 focus:outline-none transition-transform"
                :class="open ? 'rotate-180' : ''">
                <i class="fa-solid fa-chevron-down text-2xl"></i>
            </button>
        </div>

        <!-- Contenu détaillé (dépliable) -->
        <div x-show="open" x-transition class="bg-gradient-to-tl from-blue-50 to-white p-8 space-y-10 relative z-10">
            <!-- Statistiques générales -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-red-100/80 rounded-2xl p-6 border-2 border-red-300 shadow group-hover:shadow-lg">
                    <h4 class="text-sm font-bold text-red-700 mb-3 flex items-center gap-2 uppercase tracking-wider">
                        <i class="fa-solid fa-triangle-exclamation"></i>
                        Absences non justifiées
                    </h4>
                    <p class="text-4xl font-black text-red-500 drop-shadow">
                        {{ $statistiques[$enfant->id]['absences_non_justifiees'] ?? 0 }}
                    </p>
                </div>
                <div class="bg-yellow-100/80 rounded-2xl p-6 border-2 border-yellow-300 shadow group-hover:shadow-lg">
                    <h4 class="text-sm font-bold text-yellow-700 mb-3 flex items-center gap-2 uppercase tracking-wider">
                        <i class="fa-solid fa-file-circle-check"></i>
                        Absences justifiées
                    </h4>
                    <p class="text-4xl font-black text-yellow-500 drop-shadow">
                        {{ $statistiques[$enfant->id]['absences_justifiees'] ?? 0 }}
                    </p>
                </div>
                <div class="bg-green-100/80 rounded-2xl p-6 border-2 border-green-300 shadow group-hover:shadow-lg">
                    <h4 class="text-sm font-bold text-green-700 mb-3 flex items-center gap-2 uppercase tracking-wider">
                        <i class="fa-solid fa-chart-line"></i>
                        Taux de présence global
                    </h4>
                    <div class="flex items-end gap-2">
                        <p class="text-4xl font-black text-green-600 drop-shadow">
                            {{ $statistiques[$enfant->id]['taux_presence_global'] ?? 0 }}%
                        </p>
                        <span class="text-xs text-green-800 font-semibold">(sur {{ $statistiques[$enfant->id]['total_presences'] ?? 0 }} cours)</span>
                    </div>
                    <p class="text-sm text-green-700 mt-1 font-semibold">
                        {{ $statistiques[$enfant->id]['presences_effectives'] ?? 0 }}/{{ $statistiques[$enfant->id]['total_presences'] ?? 0 }} présents
                    </p>
                </div>
            </div>

            <!-- Taux par matière -->
            <div class="bg-white/90 rounded-2xl p-6 border border-gray-200 shadow-lg">
                <h4 class="text-lg font-extrabold text-blue-800 mb-5 flex items-center gap-2">
                    <i class="fa-solid fa-book text-blue-500"></i>
                    Taux de présence par matière
                </h4>
                <div class="space-y-4">
                    @forelse($statistiques[$enfant->id]['presences_par_matiere'] ?? [] as $matiere_id => $stats)
                        <div>
                            <div class="flex items-center justify-between">
                                <span class="text-base text-gray-700 font-semibold">
                                    {{ $stats['nom'] }}
                                </span>
                                <span class="text-base font-bold text-blue-800">
                                    {{ $stats['taux'] }}% <span class="text-gray-400">({{ $stats['presents'] }}/{{ $stats['total'] }})</span>
                                </span>
                            </div>
                            <div class="w-full bg-gray-200/60 rounded-full h-3 mt-1">
                                <div class="{{ $stats['couleur'] }} h-3 rounded-full transition-all duration-300 shadow"
                                    style="width: {{ $stats['taux'] }}%"></div>
                            </div>
                        </div>
                    @empty
                        <p class="text-gray-400 italic">Aucune donnée de matière disponible.</p>
                    @endforelse
                </div>
            </div>

            <!-- Taux par semaine -->
            <div class="bg-white/90 rounded-2xl p-6 border border-gray-200 shadow-lg">
                <h4 class="text-lg font-extrabold text-blue-800 mb-5 flex items-center gap-2">
                    <i class="fa-solid fa-chart-column text-blue-500"></i>
                    Taux de présence par semaine
                </h4>
                <div class="space-y-4">
                    @forelse($statistiques[$enfant->id]['presences_par_semaine'] ?? [] as $semaine => $stats)
                        <div>
                            <div class="flex items-center justify-between">
                                <span class="text-base text-gray-700 font-semibold">
                                    Semaine du {{ \Carbon\Carbon::parse($semaine)->format('d/m/Y') }}
                                </span>
                                <span class="text-base font-bold text-blue-800">
                                    {{ $stats['taux'] }}% <span class="text-gray-400">({{ $stats['presents'] }}/{{ $stats['total'] }})</span>
                                </span>
                            </div>
                            <div class="w-full bg-gray-200/60 rounded-full h-3 mt-1">
                                <div class="bg-blue-500 h-3 rounded-full transition-all duration-300 shadow"
                                    style="width: {{ $stats['taux'] }}%"></div>
                            </div>
                        </div>
                    @empty
                        <p class="text-gray-400 italic">Aucune donnée hebdomadaire disponible.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
    @empty
        <div class="bg-white rounded-2xl p-10 shadow-lg text-center text-gray-400 text-lg font-semibold mt-20">
            <i class="fa-solid fa-face-sad-tear text-4xl text-blue-300 mb-4"></i><br>
            Aucun enfant à afficher pour votre compte.
        </div>
    @endforelse
</div>
@endsection
