<aside x-data="{ open: true }" class="bg-white border-r border-gray-200 min-h-screen w-64 fixed z-30">
    <div class="h-16 flex items-center justify-between px-6 border-b border-gray-100">
        <a href="{{ route('dashboard') }}" class="text-xl font-bold text-gray-800">
            <x-application-mark class="h-9 w-auto" />
        </a>
        <button @click="open = !open" class="sm:hidden text-gray-500 hover:text-gray-700">
            <i class="fas fa-bars text-lg"></i>
        </button>
    </div>

    <nav class="px-6 py-4 space-y-2 text-gray-700">
        @php $role = Auth::user()->role ?? null; @endphp

        @if($role === 'admin')
            <x-nav-link href="{{ route('admin.dashboard') }}" :active="request()->routeIs('dashboard')">
                <i class="fas fa-chart-line mr-2 text-red-600"></i> Dashboard
            </x-nav-link>
            <x-nav-link href="{{ route('admin.users.create') }}" :active="request()->routeIs('admin.users.create')">
                <i class="fas fa-user-plus mr-2 text-red-600"></i> Créer un utilisateur
            </x-nav-link>
        @elseif($role === 'coordinateur')
            <x-nav-link href="/coordinateur" :active="request()->is('coordinateur')">
                <i class="fas fa-tachometer-alt mr-2 text-blue-600"></i> Dashboard Coordinateur
            </x-nav-link>
            <x-nav-link href="{{ route('coordinateur.classes.index') }}" :active="request()->routeIs('coordinateur.classes.index')">
                <i class="fas fa-school mr-2 text-blue-600"></i> Liste des classes
            </x-nav-link>
            <x-nav-link href="{{ route('emploi-du-temps.index') }}" :active="request()->routeIs('emploi-du-temps.index')">
                <i class="fas fa-calendar-alt mr-2 text-blue-600"></i> Emploi du temps
            </x-nav-link>
            <x-nav-link href="{{ route('coordinateur.presences.index') }}" :active="request()->routeIs('coordinateur.presences.index')">
                <i class="fas fa-user-check mr-2 text-blue-600"></i> Consulter les présences
            </x-nav-link>
        @elseif($role === 'professeur')
            <x-nav-link href="/professeur" :active="request()->is('professeur')">
                <i class="fas fa-chalkboard-teacher mr-2 text-green-600"></i> Dashboard Professeur
            </x-nav-link>
            <x-nav-link href="{{ route('presences.index') }}" :active="request()->routeIs('emploi-du-temps.index')">
                <i class="fas fa-calendar-check mr-2 text-green-600"></i> Emploi du temps
            </x-nav-link>
        @elseif($role === 'etudiant')
            <x-nav-link href="/etudiant" :active="request()->is('etudiant')">
                <i class="fas fa-user-graduate mr-2 text-purple-600"></i> Dashboard Étudiant
            </x-nav-link>
        @endif


            <div class="mt-3 space-y-1">
                <!-- Account Management -->
                <x-responsive-nav-link href="{{ route('profile.show') }}" :active="request()->routeIs('profile.show')">
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                @if (Laravel\Jetstream\Jetstream::hasApiFeatures())
                    <x-responsive-nav-link href="{{ route('api-tokens.index') }}" :active="request()->routeIs('api-tokens.index')">
                        {{ __('API Tokens') }}
                    </x-responsive-nav-link>
                @endif

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}" x-data>
                    @csrf

                    <x-responsive-nav-link href="{{ route('logout') }}"
                                   @click.prevent="$root.submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>

                <!-- Team Management -->
                @if (Laravel\Jetstream\Jetstream::hasTeamFeatures())
                    <div class="border-t border-gray-200"></div>

                    <div class="block px-4 py-2 text-xs text-gray-400">
                        {{ __('Manage Team') }}
                    </div>

                    <!-- Team Settings -->
                    <x-responsive-nav-link href="{{ route('teams.show', Auth::user()->currentTeam->id) }}" :active="request()->routeIs('teams.show')">
                        {{ __('Team Settings') }}
                    </x-responsive-nav-link>

                    @can('create', Laravel\Jetstream\Jetstream::newTeamModel())
                        <x-responsive-nav-link href="{{ route('teams.create') }}" :active="request()->routeIs('teams.create')">
                            {{ __('Create New Team') }}
                        </x-responsive-nav-link>
                    @endcan

                    <!-- Team Switcher -->
                    @if (Auth::user()->allTeams()->count() > 1)
                        <div class="border-t border-gray-200"></div>

                        <div class="block px-4 py-2 text-xs text-gray-400">
                            {{ __('Switch Teams') }}
                        </div>

                        @foreach (Auth::user()->allTeams() as $team)
                            <x-switchable-team :team="$team" component="responsive-nav-link" />
                        @endforeach
                    @endif
                @endif
            </div>


    </nav>
</aside>
