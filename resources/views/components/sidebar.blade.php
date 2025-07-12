  @php $role = Auth::user()->role ?? null; @endphp
  @if ($role === 'admin')
   <aside x-data="{ open: true }" class="bg-[#102542] text-white w-72 h-full fixed z-30">
    <!-- Titre du panneau -->
    <div class="text-white font-semibold text-[25px] leading-[39px] px-9 pt-7">
        Tableau de bord<br />administrateur
    </div>

    <!-- Séparateur -->
    <hr class="border-white mt-6 mx-8" />

    <!-- Liens pour ADMIN -->
    @php $role = Auth::user()->role ?? null; @endphp

    <nav class="mt-[50px] space-y-4">
        @if($role === 'admin')
            <a href="{{ route('admin.users.create') }}" class="flex items-center bg-[#BD2727] rounded-[13px]  h-[55px] ml-[15px] mr-[15px] hover:opacity-90">
                <div class="w-[30px] h-[30px] bg-white rounded-lg flex items-center justify-center ml-3">
                    <i class="fas fa-user-plus text-[#BD2727]"></i>
                </div>
                <span class="text-white font-bold text-[22px] ml-5">Créer un utilisateur</span>
            </a>

            <a href="{{ route('admin.users.index') }}" class="flex items-center bg-[#BD2727] rounded-[13px]  h-[55px] ml-[15px] mr-[5px]  hover:opacity-90">
                <div class="w-[30px] h-[30px] bg-white rounded-lg flex items-center justify-center ml-3">
                    <i class="fas fa-users text-[#BD2727]"></i>
                </div>
                <span class="text-white font-bold text-[22px] ml-5">Liste des utilisateurs</span>
            </a>

            <a href="{{ route('admin.matieres.index') }}" class="flex items-center bg-[#BD2727] rounded-[13px]  h-[55px] ml-[15px] mr-[5px]  hover:opacity-90">
                <div class="w-[30px] h-[30px] bg-white rounded-lg flex items-center justify-center ml-3">
                    <i class="fas fa-users text-[#BD2727]"></i>
                </div>
                <span class="text-white font-bold text-[22px] ml-5">Liste des matieres</span>
            </a>

             <a href="{{ route('admin.classes.index') }}" class="flex items-center bg-[#BD2727] rounded-[13px]  h-[55px] ml-[15px] mr-[5px]  hover:opacity-90">
                <div class="w-[30px] h-[30px] bg-white rounded-lg flex items-center justify-center ml-3">
                    <i class="fas fa-users text-[#BD2727]"></i>
                </div>
                <span class="text-white font-bold text-[22px] ml-5">Liste des classes</span>
            </a>

        @endif

        @if($role === 'coordinateur')
            <a href="{{ route('coordinateur.classes.index') }}" class="flex items-center bg-[#BD2727] rounded-[13px]  h-[55px] ml-[15px] hover:opacity-90">
                <div class="w-[30px] h-[30px] bg-white rounded-lg flex items-center justify-center ml-3">
                    <i class="fas fa-school text-[#BD2727]"></i>
                </div>
                <span class="text-white font-bold text-[22px] ml-5">Liste des classes</span>
            </a>

            <a href="{{ route('emploi-du-temps.index') }}" class="flex items-center bg-[#BD2727] rounded-[13px]  h-[67px] ml-[15px] hover:opacity-90">
                <div class="w-[30px] h-[30px] bg-white rounded-lg flex items-center justify-center ml-3">
                    <i class="fas fa-calendar-alt text-[#BD2727]"></i>
                </div>
                <span class="text-white font-bold text-[22px] ml-5">Emploi du temps</span>
            </a>

            <a href="{{ route('coordinateur.presences.index') }}" class="flex items-center bg-[#BD2727] rounded-[13px] w-[322px] h-[67px] ml-[15px] hover:opacity-90">
                <div class="w-[53px] h-[53px] bg-white rounded-lg flex items-center justify-center ml-3">
                    <i class="fas fa-user-check text-[#BD2727]"></i>
                </div>
                <span class="text-white font-bold text-[22px] ml-5">Présences</span>
            </a>
        @endif

        @if($role === 'professeur')
            <a href="/professeur" class="flex items-center bg-[#BD2727] rounded-[13px] w-[322px] h-[67px] ml-[15px] hover:opacity-90">
                <div class="w-[53px] h-[53px] bg-white rounded-lg flex items-center justify-center ml-3">
                    <i class="fas fa-chalkboard-teacher text-[#BD2727]"></i>
                </div>
                <span class="text-white font-bold text-[22px] ml-5">Dashboard</span>
            </a>

            <a href="{{ route('presences.index') }}" class="flex items-center bg-[#BD2727] rounded-[13px] w-[322px] h-[67px] ml-[15px] hover:opacity-90">
                <div class="w-[53px] h-[53px] bg-white rounded-lg flex items-center justify-center ml-3">
                    <i class="fas fa-calendar-check text-[#BD2727]"></i>
                </div>
                <span class="text-white font-bold text-[22px] ml-5">Emploi du temps</span>
            </a>
        @endif

        @if($role === 'etudiant')
            <a href="/etudiant" class="flex items-center bg-[#BD2727] rounded-[13px] w-[322px] h-[67px] ml-[15px] hover:opacity-90">
                <div class="w-[53px] h-[53px] bg-white rounded-lg flex items-center justify-center ml-3">
                    <i class="fas fa-user-graduate text-[#BD2727]"></i>
                </div>
                <span class="text-white font-bold text-[22px] ml-5">Dashboard Étudiant</span>
            </a>
        @endif

        <!-- Profile + Logout -->
        <div class="mt-6 ml-[15px]">
            <x-responsive-nav-link href="{{ route('profile.show') }}" :active="request()->routeIs('profile.show')">
                <i class="fas fa-user text-[#BD2727] mr-2"></i> Profil
            </x-responsive-nav-link>

            <form method="POST" action="{{ route('logout') }}" x-data>
                @csrf
                <x-responsive-nav-link href="{{ route('logout') }}" @click.prevent="$root.submit();">
                    <i class="fas fa-sign-out-alt text-[#BD2727] mr-2"></i> Déconnexion
                </x-responsive-nav-link>
            </form>
        </div>
    </nav>
   </aside>

  @endif


@if ($role === 'coordinateur')
     <aside x-data="{ open: true }" class="bg-[#7a1414] text-white w-72 h-full fixed z-30">
    <!-- Titre du panneau -->
    <div class="text-white font-semibold text-[25px] leading-[39px] px-9 pt-7">
        Tableau de bord<br />administrateur
    </div>

    <!-- Séparateur -->
    <hr class="border-white mt-6 mx-8" />

    <!-- Liens pour ADMIN -->
    @php $role = Auth::user()->role ?? null; @endphp

    <nav class="mt-[50px] space-y-4">
        @if($role === 'admin')
            <a href="{{ route('admin.users.create') }}" class="flex items-center bg-[#BD2727] rounded-[13px]  h-[55px] ml-[15px] mr-[15px] hover:opacity-90">
                <div class="w-[30px] h-[30px] bg-white rounded-lg flex items-center justify-center ml-3">
                    <i class="fas fa-user-plus text-[#BD2727]"></i>
                </div>
                <span class="text-white font-bold text-[22px] ml-5">Créer un utilisateur</span>
            </a>

            <a href="{{ route('admin.users.index') }}" class="flex items-center bg-[#BD2727] rounded-[13px]  h-[55px] ml-[15px] mr-[5px]  hover:opacity-90">
                <div class="w-[30px] h-[30px] bg-white rounded-lg flex items-center justify-center ml-3">
                    <i class="fas fa-users text-[#BD2727]"></i>
                </div>
                <span class="text-white font-bold text-[22px] ml-5">Liste des utilisateurs</span>
            </a>
        @endif

        @if($role === 'coordinateur')
                  <a href="{{ route('coordinateur.classes.index') }}" class="flex items-center bg-[#BD2727] rounded-[13px]  h-[55px] ml-[15px] hover:opacity-90">
                <div class="w-[30px] h-[30px] bg-white rounded-lg flex items-center justify-center ml-3">
                    <i class="fas fa-school text-[#BD2727]"></i>
                </div>
                <span class="text-white font-bold text-[22px] ml-5">Liste des classes</span>
            </a>

            <a href="{{ route('emploi-du-temps.index') }}" class="flex items-center bg-[#BD2727] rounded-[13px]  h-[67px] ml-[15px] hover:opacity-90">
                <div class="w-[30px] h-[30px] bg-white rounded-lg flex items-center justify-center ml-3">
                    <i class="fas fa-calendar-alt text-[#BD2727]"></i>
                </div>
                <span class="text-white font-bold text-[22px] ml-5">Emploi du temps</span>
            </a>

            <a href="{{ route('coordinateur.presences.index') }}" class="flex items-center bg-[#BD2727] rounded-[13px]  h-[67px] ml-[15px] hover:opacity-90">
                <div class="w-[30px] h-[30px] bg-white rounded-lg flex items-center justify-center ml-3">
                    <i class="fas fa-user-check text-[#BD2727]"></i>
                </div>
                <span class="text-white font-bold text-[22px] ml-5">Présences</span>
            </a>

            <a href="{{ route('coordinateur.justifications.index') }}" class="flex items-center bg-[#BD2727] rounded-[13px]  h-[67px] ml-[15px] hover:opacity-90">
                <div class="w-[30px] h-[30px] bg-white rounded-lg flex items-center justify-center ml-3">
                    <i class="fas fa-clipboard-check text-[#BD2727]"></i>
                </div>
                <span class="text-white font-bold text-[22px] ml-5">Justifications</span>
            </a>


            <a href="{{ route('coordinateur.justifications.history') }}" class="flex items-center bg-[#BD2727] rounded-[13px]  h-[67px] ml-[15px] hover:opacity-90">
                <div class="w-[30px] h-[30px] bg-white rounded-lg flex items-center justify-center ml-3">
                    <i class="fas fa-history text-[#BD2727]"></i>
                </div>
                <span class="text-white font-bold text-[22px] ml-5">Historique des justifications</span>
            </a>


        @endif

        @if($role === 'professeur')
            <a href="/professeur" class="flex items-center bg-[#BD2727] rounded-[13px] w-[322px] h-[67px] ml-[15px] hover:opacity-90">
                <div class="w-[53px] h-[53px] bg-white rounded-lg flex items-center justify-center ml-3">
                    <i class="fas fa-chalkboard-teacher text-[#BD2727]"></i>
                </div>
                <span class="text-white font-bold text-[22px] ml-5">Dashboard</span>
            </a>

            <a href="{{ route('presences.index') }}" class="flex items-center bg-[#BD2727] rounded-[13px] w-[322px] h-[67px] ml-[15px] hover:opacity-90">
                <div class="w-[53px] h-[53px] bg-white rounded-lg flex items-center justify-center ml-3">
                    <i class="fas fa-calendar-check text-[#BD2727]"></i>
                </div>
                <span class="text-white font-bold text-[22px] ml-5">Emploi du temps</span>
            </a>
        @endif

        @if($role === 'etudiant')
            <a href="/etudiant" class="flex items-center bg-[#BD2727] rounded-[13px] w-[322px] h-[67px] ml-[15px] hover:opacity-90">
                <div class="w-[53px] h-[53px] bg-white rounded-lg flex items-center justify-center ml-3">
                    <i class="fas fa-user-graduate text-[#BD2727]"></i>
                </div>
                <span class="text-white font-bold text-[22px] ml-5">Dashboard Étudiant</span>
            </a>

               <a href="{{route('etu')}}" class="flex items-center bg-[#BD2727] rounded-[13px] w-[322px] h-[67px] ml-[15px] hover:opacity-90">
                <div class="w-[53px] h-[53px] bg-white rounded-lg flex items-center justify-center ml-3">
                    <i class="fas fa-user-graduate text-[#BD2727]"></i>
                </div>
                <span class="text-white font-bold text-[22px] ml-5">Dashboard Étudiant</span>
            </a>


        @endif

        <!-- Profile + Logout -->
        <div class="mt-6 ml-[15px]">
            <x-responsive-nav-link href="{{ route('profile.show') }}" :active="request()->routeIs('profile.show')">
                <i class="fas fa-user text-[#BD2727] mr-2"></i> Profil
            </x-responsive-nav-link>

            <form method="POST" action="{{ route('logout') }}" x-data>
                @csrf
                <x-responsive-nav-link href="{{ route('logout') }}" @click.prevent="$root.submit();">
                    <i class="fas fa-sign-out-alt text-[#BD2727] mr-2"></i> Déconnexion
                </x-responsive-nav-link>
            </form>
        </div>
    </nav>
     </aside>

 @endif


@if ($role === 'professeur')
<aside x-data="{ open: true }" class="bg-[#de8d22] text-white w-72 h-full fixed z-30">
    <!-- Titre du panneau -->
    <div class="text-white font-semibold text-[25px] leading-[39px] px-9 pt-7">
        Tableau de bord<br />administrateur
    </div>

    <!-- Séparateur -->
    <hr class="border-white mt-6 mx-8" />

    <!-- Liens pour ADMIN -->
    @php $role = Auth::user()->role ?? null; @endphp

    <nav class="mt-[50px] space-y-4">
        @if($role === 'admin')
            <a href="{{ route('admin.users.create') }}" class="flex items-center bg-[#BD2727] rounded-[13px]  h-[55px] ml-[15px] mr-[15px] hover:opacity-90">
                <div class="w-[30px] h-[30px] bg-white rounded-lg flex items-center justify-center ml-3">
                    <i class="fas fa-user-plus text-[#BD2727]"></i>
                </div>
                <span class="text-white font-bold text-[22px] ml-5">Créer un utilisateur</span>
            </a>

            <a href="{{ route('admin.users.index') }}" class="flex items-center bg-[#BD2727] rounded-[13px]  h-[55px] ml-[15px] mr-[5px]  hover:opacity-90">
                <div class="w-[30px] h-[30px] bg-white rounded-lg flex items-center justify-center ml-3">
                    <i class="fas fa-users text-[#BD2727]"></i>
                </div>
                <span class="text-white font-bold text-[22px] ml-5">Liste des utilisateurs</span>
            </a>
        @endif

        @if($role === 'coordinateur')
            <a href="{{ route('coordinateur.classes.index') }}" class="flex items-center bg-[#BD2727] rounded-[13px] w-[322px] h-[67px] ml-[15px] hover:opacity-90">
                <div class="w-[53px] h-[53px] bg-white rounded-lg flex items-center justify-center ml-3">
                    <i class="fas fa-school text-[#BD2727]"></i>
                </div>
                <span class="text-white font-bold text-[22px] ml-5">Liste des classes</span>
            </a>

            <a href="{{ route('emploi-du-temps.index') }}" class="flex items-center bg-[#BD2727] rounded-[13px] w-[322px] h-[67px] ml-[15px] hover:opacity-90">
                <div class="w-[53px] h-[53px] bg-white rounded-lg flex items-center justify-center ml-3">
                    <i class="fas fa-calendar-alt text-[#BD2727]"></i>
                </div>
                <span class="text-white font-bold text-[22px] ml-5">Emploi du temps</span>
            </a>

            <a href="{{ route('coordinateur.presences.index') }}" class="flex items-center bg-[#BD2727] rounded-[13px] w-[322px] h-[67px] ml-[15px] hover:opacity-90">
                <div class="w-[53px] h-[53px] bg-white rounded-lg flex items-center justify-center ml-3">
                    <i class="fas fa-user-check text-[#BD2727]"></i>
                </div>
                <span class="text-white font-bold text-[22px] ml-5">Présences</span>
            </a>
        @endif

        @if($role === 'professeur')
            <a href="/professeur" class="flex items-center bg-[#BD2727] rounded-[13px] w-[322px] h-[67px] ml-[15px] hover:opacity-90">
                <div class="w-[53px] h-[53px] bg-white rounded-lg flex items-center justify-center ml-3">
                    <i class="fas fa-chalkboard-teacher text-[#BD2727]"></i>
                </div>
                <span class="text-white font-bold text-[22px] ml-5">Dashboard</span>
            </a>

            <a href="{{ route('presences.index') }}" class="flex items-center bg-[#BD2727] rounded-[13px] w-[322px] h-[67px] ml-[15px] hover:opacity-90">
                <div class="w-[53px] h-[53px] bg-white rounded-lg flex items-center justify-center ml-3">
                    <i class="fas fa-calendar-check text-[#BD2727]"></i>
                </div>
                <span class="text-white font-bold text-[22px] ml-5">Emploi du temps</span>
            </a>
        @endif

        @if($role === 'etudiant')
            <a href="/etudiant" class="flex items-center bg-[#BD2727] rounded-[13px] w-[322px] h-[67px] ml-[15px] hover:opacity-90">
                <div class="w-[53px] h-[53px] bg-white rounded-lg flex items-center justify-center ml-3">
                    <i class="fas fa-user-graduate text-[#BD2727]"></i>
                </div>
                <span class="text-white font-bold text-[22px] ml-5">Dashboard Étudiant</span>
            </a>
        @endif

        <!-- Profile + Logout -->
        <div class="mt-6 ml-[15px]">
            <x-responsive-nav-link href="{{ route('profile.show') }}" :active="request()->routeIs('profile.show')">
                <i class="fas fa-user text-[#BD2727] mr-2"></i> Profil
            </x-responsive-nav-link>

            <form method="POST" action="{{ route('logout') }}" x-data>
                @csrf
                <x-responsive-nav-link href="{{ route('logout') }}" @click.prevent="$root.submit();">
                    <i class="fas fa-sign-out-alt text-[#BD2727] mr-2"></i> Déconnexion
                </x-responsive-nav-link>
            </form>
        </div>
    </nav>
   </aside>

@endif


@if ($role === 'etudiant')
   <aside x-data="{ open: true }" class="bg-[#2b538c] text-white w-72 h-full fixed z-30">
    <!-- Titre du panneau -->
    <div class="text-white font-semibold text-[25px] leading-[39px] px-9 pt-7">
        Tableau de bord<br />administrateur
    </div>

    <!-- Séparateur -->
    <hr class="border-white mt-6 mx-8" />

    <!-- Liens pour ADMIN -->
    @php $role = Auth::user()->role ?? null; @endphp

    <nav class="mt-[50px] space-y-4">
        @if($role === 'admin')
            <a href="{{ route('admin.users.create') }}" class="flex items-center bg-[#BD2727] rounded-[13px]  h-[55px] ml-[15px] mr-[15px] hover:opacity-90">
                <div class="w-[30px] h-[30px] bg-white rounded-lg flex items-center justify-center ml-3">
                    <i class="fas fa-user-plus text-[#BD2727]"></i>
                </div>
                <span class="text-white font-bold text-[22px] ml-5">Créer un utilisateur</span>
            </a>

            <a href="{{ route('admin.users.index') }}" class="flex items-center bg-[#BD2727] rounded-[13px]  h-[55px] ml-[15px] mr-[5px]  hover:opacity-90">
                <div class="w-[30px] h-[30px] bg-white rounded-lg flex items-center justify-center ml-3">
                    <i class="fas fa-users text-[#BD2727]"></i>
                </div>
                <span class="text-white font-bold text-[22px] ml-5">Liste des utilisateurs</span>
            </a>
        @endif

        @if($role === 'coordinateur')
            <a href="{{ route('coordinateur.classes.index') }}" class="flex items-center bg-[#BD2727] rounded-[13px] w-[322px] h-[67px] ml-[15px] hover:opacity-90">
                <div class="w-[53px] h-[53px] bg-white rounded-lg flex items-center justify-center ml-3">
                    <i class="fas fa-school text-[#BD2727]"></i>
                </div>
                <span class="text-white font-bold text-[22px] ml-5">Liste des classes</span>
            </a>

            <a href="{{ route('emploi-du-temps.index') }}" class="flex items-center bg-[#BD2727] rounded-[13px] w-[322px] h-[67px] ml-[15px] hover:opacity-90">
                <div class="w-[53px] h-[53px] bg-white rounded-lg flex items-center justify-center ml-3">
                    <i class="fas fa-calendar-alt text-[#BD2727]"></i>
                </div>
                <span class="text-white font-bold text-[22px] ml-5">Emploi du temps</span>
            </a>

            <a href="{{ route('coordinateur.presences.index') }}" class="flex items-center bg-[#BD2727] rounded-[13px] w-[322px] h-[67px] ml-[15px] hover:opacity-90">
                <div class="w-[53px] h-[53px] bg-white rounded-lg flex items-center justify-center ml-3">
                    <i class="fas fa-user-check text-[#BD2727]"></i>
                </div>
                <span class="text-white font-bold text-[22px] ml-5">Présences</span>
            </a>
        @endif

        @if($role === 'professeur')
            <a href="/professeur" class="flex items-center bg-[#BD2727] rounded-[13px] w-[322px] h-[67px] ml-[15px] hover:opacity-90">
                <div class="w-[53px] h-[53px] bg-white rounded-lg flex items-center justify-center ml-3">
                    <i class="fas fa-chalkboard-teacher text-[#BD2727]"></i>
                </div>
                <span class="text-white font-bold text-[22px] ml-5">Dashboard</span>
            </a>

            <a href="{{ route('presences.index') }}" class="flex items-center bg-[#BD2727] rounded-[13px] w-[322px] h-[67px] ml-[15px] hover:opacity-90">
                <div class="w-[53px] h-[53px] bg-white rounded-lg flex items-center justify-center ml-3">
                    <i class="fas fa-calendar-check text-[#BD2727]"></i>
                </div>
                <span class="text-white font-bold text-[22px] ml-5">Emploi du temps</span>
            </a>
        @endif

        @if($role === 'etudiant')
            <a href="/etudiant" class="flex items-center bg-[#BD2727] rounded-[13px]  h-[67px] ml-[15px] hover:opacity-90">
                <div class="w-[30px] h-[30px] bg-white rounded-lg flex items-center justify-center ml-3">
                    <i class="fas fa-user-graduate text-[#BD2727]"></i>
                </div>
                <span class="text-white font-bold text-[22px] ml-5">Dashboard Étudiant</span>
            </a>

            <a href="{{ route('etudiant.emploi-du-temps.index') }}" class="flex items-center bg-[#BD2727] rounded-[13px]  h-[67px] ml-[15px] hover:opacity-90">
                <div class="w-[30px] h-[30px] bg-white rounded-lg flex items-center justify-center ml-3">
                    <i class="fas fa-calendar-check text-[#BD2727]"></i>
                </div>
                <span class="text-white font-bold text-[22px] ml-5">Emploi du temps</span>
            </a>

            <a href="{{ route('etudiant.presences.index') }}" class="flex items-center bg-[#BD2727] rounded-[13px]  h-[67px] ml-[15px] hover:opacity-90">
                <div class="w-[30px] h-[30px] bg-white rounded-lg flex items-center justify-center ml-3">
                    <i class="fas fa-clipboard-check text-[#BD2727]"></i>
                </div>
                <span class="text-white font-bold text-[22px] ml-5">Justifications</span>
            </a>

             <a href="{{ route('etudiant.matieres.index') }}" class="flex items-center bg-[#BD2727] rounded-[13px]  h-[67px] ml-[15px] hover:opacity-90">
                <div class="w-[30px] h-[30px] bg-white rounded-lg flex items-center justify-center ml-3">
                    <i class="fas fa-clipboard-check text-[#BD2727]"></i>
                </div>
                <span class="text-white font-bold text-[22px] ml-5">matiere</span>
            </a>



        @endif

        <!-- Profile + Logout -->
        <div class="mt-6 ml-[15px]">
            <x-responsive-nav-link href="{{ route('profile.show') }}" :active="request()->routeIs('profile.show')">
                <i class="fas fa-user text-[#BD2727] mr-2"></i> Profil
            </x-responsive-nav-link>

            <form method="POST" action="{{ route('logout') }}" x-data>
                @csrf
                <x-responsive-nav-link href="{{ route('logout') }}" @click.prevent="$root.submit();">
                    <i class="fas fa-sign-out-alt text-[#BD2727] mr-2"></i> Déconnexion
                </x-responsive-nav-link>
            </form>
        </div>
    </nav>
   </aside>

  @endif

