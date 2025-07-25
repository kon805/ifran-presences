

<x-guest-layout>
    <div class="h-screen md:flex">
         <!-- Section formulaire (droite) -->
    <div class="flex md:w-1/2 justify-center py-12 items-center bg-white">
        <form method="POST" action="{{ route('login') }}" class="bg-white px-10 py-8 rounded-3xl border shadow-lg w-full max-w-md">
            @csrf
            <div class="flex justify-center mb-6">
                <img src="{{ asset('img/if3.png') }}" alt="Logo"
                    class="w-24 h-24 object-cover rounded-full shadow-lg border-4 border-pink-200 hover:scale-105 transition-transform duration-300 bg-gradient-to-r from-pink-600 to-red-500">
            </div>
            <h1 class="text-gray-800 font-bold text-2xl mb-1 text-center">Heureux de vous revoir !</h1>
            <p class="text-sm font-normal text-gray-600 mb-7 text-center">Connectez-vous à votre espace</p>
            <div class="flex items-center border-2 py-2 px-3 rounded-2xl mb-4">
                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" />
                </svg>
                <input class="pl-2 outline-none border-none w-full bg-transparent"
                       type="email"
                       name="email"
                       placeholder="Adresse e-mail"
                       required
                       autofocus
                       autocomplete="username" />
            </div>
            <div class="flex items-center border-2 py-2 px-3 rounded-2xl mb-4">
                <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 20 20" stroke="currentColor">
                    <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"/>
                </svg>
                <input class="pl-2 outline-none border-none w-full bg-transparent"
                       type="password"
                       name="password"
                       placeholder="Mot de passe"
                       required
                       autocomplete="current-password" />
            </div>
            <div class="flex justify-between items-center mb-4">
                <label class="flex items-center text-xs">
                    <input type="checkbox" name="remember" class="accent-pink-600">
                    <span class="ml-2 text-gray-700">Se souvenir de moi</span>
                </label>
                @if (Route::has('password.request'))
                <a class="text-pink-600 hover:text-pink-800 font-semibold text-xs" href="{{ route('password.request') }}">Mot de passe oublié ?</a>
                @endif
            </div>
            <button type="submit" class="block w-full bg-gradient-to-r from-pink-600 to-red-500 mt-4 py-2 rounded-2xl text-white font-semibold mb-2 shadow hover:brightness-110 transition">Se connecter</button>
            <div class="text-center mt-2">
                <a href="#" class="text-pink-600 hover:text-pink-800 font-medium text-sm">Pas encore de compte ?</a>
            </div>
        </form>
    </div>
    <!-- Section visuelle (gauche) -->
    <div class="relative overflow-hidden md:flex w-1/2 bg-gradient-to-tr from-red-700 to-pink-500 justify-around items-center hidden">
        <div class="z-10">
            <h1 class="text-white font-bold text-4xl font-sans">MonEcole</h1>
            <p class="text-white mt-1">La gestion scolaire digitalisée, simple et efficace</p>
            <button type="button" class="block w-32 bg-white text-red-700 mt-4 py-2 rounded-2xl font-bold mb-2 shadow hover:bg-red-100 transition">En savoir plus</button>
        </div>
        <!-- Cercles décoratifs -->
        <div class="absolute -bottom-32 -left-40 w-80 h-80 border-4 rounded-full border-white/30 border-t-8"></div>
        <div class="absolute -bottom-40 -left-20 w-80 h-80 border-4 rounded-full border-white/30 border-t-8"></div>
        <div class="absolute -top-40 -right-0 w-80 h-80 border-4 rounded-full border-white/30 border-t-8"></div>
        <div class="absolute -top-20 -right-20 w-80 h-80 border-4 rounded-full border-white/30 border-t-8"></div>
    </div>


</div>
</x-guest-layout>
