@php
    $role = Auth::user()->role ?? null;
    $configs = [
      'admin' => [
     'logo' => asset('img/if3.png'),
    'color' => ' bg-gradient-to-br from-black via-gray-900 to-neutral-800',
    'title' => 'Tableau de bord<br />Administrateur',
    'links' => [
        ['route' => 'admin.users.create', 'icon' => 'fa-user-plus', 'text' => 'Créer un utilisateur', 'code_couleur' => '#1E1E1E'],
        ['route' => 'admin.users.index', 'icon' => 'fa-users', 'text' => 'Liste des utilisateurs', 'code_couleur' => '#1E1E1E'],
        ['route' => 'admin.matieres.index', 'icon' => 'fa-book', 'text' => 'Liste des matières', 'code_couleur' => '#1E1E1E'],
        ['route' => 'admin.classes.index', 'icon' => 'fa-school', 'text' => 'Liste des classes', 'code_couleur' => '#1E1E1E'],
        ['route' => 'admin.users.assign-students', 'icon' => 'fa-user-friends', 'text' => 'Assigner étudiants aux parents', 'code_couleur' => '#1E1E1E'],
    ]
],
'coordinateur' => [
    'logo' => asset('img/if3.png'),
    'color' => 'bg-gradient-to-br from-red-900 via-red-700 to-red-500',
    'title' => 'Tableau de bord<br />Coordinateur',
    'links' => [
        ['route' => 'coordinateur.classes.index', 'icon' => 'fa-school', 'text' => 'Liste des classes', 'code_couleur' => '#BD2727'],
        ['route' => 'emploi-du-temps.index', 'icon' => 'fa-calendar-alt', 'text' => 'Emploi du temps', 'code_couleur' => '#BD2727'],
        ['route' => 'coordinateur.presences.index', 'icon' => 'fa-user-check', 'text' => 'Présences', 'code_couleur' => '#BD2727'],
        ['route' => 'coordinateur.justifications.index', 'icon' => 'fa-clipboard-check', 'text' => 'Absences', 'code_couleur' => '#BD2727'],
        ['route' => 'coordinateur.justifications.history', 'icon' => 'fa-history', 'text' => 'Historique des justifications', 'code_couleur' => '#BD2727'],
        ['route' => 'coordinateur.planing.index', 'icon' => 'fa-calendar-alt', 'text' => 'Planning hebdomadaire', 'code_couleur' => '#BD2727'],
    ]
],
'professeur' => [
    'logo' => asset('img/if3.png'),
    'color' => 'bg-gradient-to-br from-yellow-700 via-yellow-600 to-yellow-500',
    'title' => 'Tableau de bord<br />Professeur',
    'links' => [
        ['route' => 'professeur.dashboard', 'icon' => 'fa-chalkboard-teacher', 'text' => 'Dashboard', 'code_couleur' => '#F59E0B'],
        ['route' => 'presences.index', 'icon' => 'fa-calendar-check', 'text' => 'Emploi du temps', 'code_couleur' => '#F59E0B'],
    ]
],
'etudiant' => [
    'logo' => asset('img/if3.png'),
    'color' => 'bg-gradient-to-br from-blue-800 via-blue-600 to-blue-400',
    'title' => 'Tableau de bord<br />Étudiant',
    'links' => [
        ['route' => 'etudiant.dashboard', 'icon' => 'fa-user-graduate', 'text' => 'Dashboard Étudiant', 'code_couleur' => '#1E40AF'],
        ['route' => 'etudiant.emploi-du-temps.index', 'icon' => 'fa-calendar-check', 'text' => 'Emploi du temps', 'code_couleur' => '#1E40AF'],
        ['route' => 'etudiant.presences.index', 'icon' => 'fa-clipboard-check', 'text' => 'Justifications', 'code_couleur' => '#1E40AF'],
        ['route' => 'etudiant.matieres.index', 'icon' => 'fa-book', 'text' => 'Matières', 'code_couleur' => '#1E40AF'],
        ['route' => 'etudiant.status.dropped', 'icon' => 'fa-user-times', 'text' => 'Statut Dropped', 'code_couleur' => '#1E40AF'],
    ]
],
'parent' => [
    'logo' => asset('img/if3.png'),
    'color' => 'bg-gradient-to-br from-cyan-800 via-cyan-600 to-cyan-400',
    'title' => 'Tableau de bord<br />Parent',
    'links' => [
        ['route' => 'parent.dashboard', 'icon' => 'fa-user-friends', 'text' => 'Dashboard Parent', 'code_couleur' => '#00BFFF'],
        // Utilisez un ID spécial pour le lien des présences afin de pouvoir l'identifier avec JavaScript
        ['route' => 'parent.presences.index', 'icon' => 'fa-calendar-check', 'text' => 'Présences', 'code_couleur' => '#00BFFF', 'id' => 'parent-presences-link', 'has_children' => true],
    ]
],

    ];
    $current = $configs[$role] ?? null;
@endphp

@if($current)
<div x-data="{ open: window.innerWidth >= 768 }" x-init="
    window.addEventListener('resize', () => { open = window.innerWidth >= 768 });
" class="relative z-50">
    <!-- Mobile Burger Button -->
    <button
        @click="open = !open"
        class="md:hidden fixed top-4 left-4 z-60 bg-white/80 backdrop-blur border border-gray-200 shadow-xl p-2 transition-all duration-200"
        aria-label="Ouvrir le menu"
        :class="{'hidden': open}"
        x-show="!open"
    >
        <svg width="26" height="26" fill="none" xmlns="http://www.w3.org/2000/svg">
            <rect y="6" width="26" height="3" rx="1.5" fill="#BD2727"/>
            <rect y="12" width="26" height="3" rx="1.5" fill="#BD2727"/>
            <rect y="18" width="26" height="3" rx="1.5" fill="#BD2727"/>
        </svg>
    </button>

    <!-- Sidebar -->
    <aside
        x-show="open"
        @click.away="if(window.innerWidth < 768){open = false}"
        class="{{ $current['color'] }} text-white w-72 md:w-64 sm:w-full h-full fixed top-0 left-0 z-50 shadow-2xl transition-all duration-300"
        style="max-width:100vw; min-height:100vh;"
        aria-label="Sidebar"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 -translate-x-full"
        x-transition:enter-end="opacity-100 translate-x-0"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 translate-x-0"
        x-transition:leave-end="opacity-0 -translate-x-full"
    >
        <!-- Close button mobile -->
        <button
            @click="open = false"
            class="md:hidden absolute top-4 right-4 bg-white/80 backdrop-blur border border-gray-200 shadow-xl p-2 transition-all duration-200"
            aria-label="Fermer le menu"
        >
            <svg width="24" height="24" fill="none"><line x1="4" y1="4" x2="20" y2="20" stroke="#BD2727" stroke-width="2"/><line x1="20" y1="4" x2="4" y2="20" stroke="#BD2727" stroke-width="2"/></svg>
        </button>
        <!-- Logo -->
        <div class="flex items-center justify-center h-24 backdrop-blur-md rounded-b-lg shadow-lg">
            <img src="{{ $current['logo'] }}" alt="IF3 logo featuringr" class="w-44 h-24 object-contain md:object-cover rounded-lg shadow-lg transform transition duration-500 hover:scale-105 hover:rotate-1 drop-shadow-2xl" loading="lazy">
        </div>
        <!-- Titre -->
        <div class="font-semibold text-[20px] leading-[30px] px-7 pt-5 drop-shadow-lg text-center ">
            {!! $current['title'] !!}
        </div>
        <hr class="border-white mt-4 mx-8 opacity-60" />

        <!-- Navigation -->
        <nav class="mt-[40px] space-y-4">
            @foreach($current['links'] as $link)
                @if(isset($link['has_children']) && $link['has_children'] && $role === 'parent')
                    <div x-data="{ open: false }" @mouseleave="open = false" class="relative">
                        <a href="{{ route($link['route']) }}"
                           @mouseenter="open = true"
                           id="{{ $link['id'] ?? '' }}"
                           class="flex items-center justify-between bg-white bg-opacity-10 rounded-xl shadow-md h-[55px] mx-4 hover:scale-[1.07] hover:bg-opacity-20 hover:shadow-xl transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-white"
                           aria-label="{{ $link['text'] }}">
                            <div class="flex items-center">
                                <div class="w-[36px] h-[36px] bg-white rounded-lg flex items-center justify-center ml-3 shadow">
                                    <i class="fas {{ $link['icon'] }} text-[22px]" style="color: {{ $link['code_couleur'] }};"></i>
                                </div>
                                <span class="text-white font-bold text-[19px] ml-5">{{ $link['text'] }}</span>
                            </div>
                            <div class="mr-4">
                                <i class="fas fa-chevron-right text-white transition-transform" :class="{'rotate-90': open}"></i>
                            </div>
                        </a>

                        <!-- Sous-menu avec les étudiants assignés au parent -->
                        <div x-show="open"
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 scale-95"
                             x-transition:enter-end="opacity-100 scale-100"
                             x-transition:leave="transition ease-in duration-150"
                             x-transition:leave-start="opacity-100 scale-100"
                             x-transition:leave-end="opacity-0 scale-95"
                             class="absolute z-50 left-4 right-4 mt-2 py-2 bg-white/20 backdrop-blur-md rounded-xl shadow-xl"
                             id="parent-students-menu"
                             style="display: none;">
                            <!-- Chargement direct des étudiants -->
                            <div class="py-1">
                                @if(Auth::user()->role === 'parent')
                                    @php
                                        $etudiants = \App\Models\User::whereHas('parents', function($query) {
                                            $query->where('user_id', Auth::id());
                                        })
                                        ->where('role', 'etudiant')
                                        ->get();
                                    @endphp

                                    @if($etudiants->count() > 0)
                                        @foreach($etudiants as $etudiant)
                                            <a href="{{ route('parent.presences.etudiant', ['etudiant' => $etudiant->id]) }}" class="flex items-center px-4 py-2 hover:bg-white/20 transition-colors duration-200">
                                                <img src="{{ $etudiant->profile_photo_url }}" class="w-7 h-7 rounded-full mr-2 border border-white" alt="{{ $etudiant->name }}">
                                                <div>
                                                    <div class="text-white text-sm font-medium">{{ $etudiant->name }}</div>
                                                    @if($etudiant->matricule)
                                                        <div class="text-white/80 text-xs">Matricule: {{ $etudiant->matricule }}</div>
                                                    @endif
                                                </div>
                                            </a>
                                        @endforeach
                                    @else
                                        <div class="px-4 py-3 text-center text-white text-sm">
                                            <i class="fas fa-exclamation-circle mr-2"></i>
                                            Aucun étudiant assigné
                                        </div>
                                    @endif
                                @else
                                    <div class="px-4 py-3 text-center text-white text-sm">
                                        <i class="fas fa-exclamation-circle mr-2"></i>
                                        Accès non autorisé
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @else
                    <a href="{{ route($link['route']) }}" class="flex items-center bg-white bg-opacity-10 rounded-xl shadow-md h-[55px] mx-4 hover:scale-[1.07] hover:bg-opacity-20 hover:shadow-xl transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-white" aria-label="{{ $link['text'] }}">
                        <div class="w-[36px] h-[36px] bg-white rounded-lg flex items-center justify-center ml-3 shadow">
                            <i class="fas {{ $link['icon'] }} text-[22px]" style="color: {{ $link['code_couleur'] }};"></i>
                        </div>
                        <span class="text-white font-bold text-[19px] ml-5">{{ $link['text'] }}</span>
                    </a>
                @endif
            @endforeach

            <!-- Profile + Logout -->
            <div class="mt-8 ml-4">
                <a href="{{ route('profile.show') }}" class="flex items-center px-4 py-3 rounded-lg bg-white bg-opacity-10 hover:bg-opacity-20 hover:scale-105 hover:shadow-lg transition-all duration-200 shadow-md mb-2 font-semibold text-white group">
                    <i class="fas fa-user text-white mr-3 group-hover:text-yellow-300 transition-colors duration-200"></i>
                    <span class="group-hover:text-yellow-300 transition-colors duration-200">Profil</span>
                </a>
                <form method="POST" action="{{ route('logout') }}" x-data>
                    @csrf
                    <button type="submit" class="flex items-center w-full px-4 py-3 rounded-lg bg-white bg-opacity-10 hover:bg-opacity-20 hover:scale-105 hover:shadow-lg transition-all duration-200 shadow-md font-semibold text-white group">
                        <i class="fas fa-sign-out-alt text-white mr-3 group-hover:text-red-400 transition-colors duration-200"></i>
                        <span class="group-hover:text-red-400 transition-colors duration-200">Déconnexion</span>
                    </button>
                </form>
            </div>
        </nav>
        <!-- Footer (optionnel) -->
        <div class="absolute bottom-4 left-0 w-full text-center text-xs text-white opacity-60">
            &copy; {{ date('Y') }} - Mon Dashboard Ifran
        </div>
    </aside>
</div>
@endif
