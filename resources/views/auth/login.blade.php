<x-guest-layout>
    <div class="h-screen flex">
        <!-- Image/branding section (left) -->
        <div class="hidden lg:flex w-full lg:w-1/2 login_img_section justify-around items-center relative bg-gradient-to-br from-red-700 via-red-400 to-pink-400">
            <div class="absolute bg-black opacity-30 inset-0 z-0"></div>
            <div class="relative w-full mx-auto px-20 flex flex-col items-center space-y-6 z-10">
                <h1 class="text-white font-extrabold text-4xl font-sans drop-shadow-lg">Simple App</h1>
                <p class="text-white mt-1 text-lg">La solution la plus simple à utiliser !</p>
                <div class="flex justify-center lg:justify-start mt-6">
                    <a href="#" class="hover:bg-red-600 hover:text-white hover:-translate-y-1 transition-all duration-500 bg-white text-red-700 mt-4 px-6 py-2 rounded-2xl font-bold text-lg shadow-lg">Commencer</a>
                </div>
            </div>
        </div>
        <!-- Login form section (right) -->
        <div class="flex w-full lg:w-1/2 justify-center items-center bg-white">
            <div class="w-full max-w-xl px-8 md:px-16 lg:px-8">
                <x-authentication-card>
                    <x-slot name="logo">
                        <div class="flex justify-center mb-6">
                            <div class="bg-white rounded-full shadow-lg p-4 border-4 border-red-200">
                                <x-authentication-card-logo class="w-20 h-20" />
                            </div>
                        </div>
                    </x-slot>

                    <x-validation-errors class="mb-6" />

                    @session('status')
                        <div class="mb-6 font-semibold text-lg text-green-700">
                            {{ $value }}
                        </div>
                    @endsession

                    <form method="POST" action="{{ route('login') }}" class="bg-white rounded-2xl shadow-2xl p-8">
                        @csrf

                        <h1 class="text-gray-800 font-extrabold text-3xl mb-2">Heureux de vous revoir !</h1>
                        <p class="text-base font-normal text-gray-600 mb-8">Connectez-vous à votre espace</p>

                        <div class="flex items-center border-2 mb-8 py-3 px-4 rounded-2xl">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" />
                            </svg>
                            <input id="email" class="pl-3 w-full outline-none border-none bg-transparent text-lg" type="email" name="email" :value="old('email')" placeholder="Adresse e-mail" required autofocus autocomplete="username" />
                        </div>

                        <div class="flex items-center border-2 mb-6 py-3 px-4 rounded-2xl">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd" />
                            </svg>
                            <input class="pl-3 w-full outline-none border-none bg-transparent text-lg" type="password" name="password" id="password" placeholder="Mot de passe" required autocomplete="current-password" />
                        </div>

                        <div class="flex items-center justify-between mb-8">
                            <label for="remember_me" class="flex items-center text-base">
                                <x-checkbox id="remember_me" name="remember" class="text-red-600 focus:ring-red-300 scale-110" />
                                <span class="ml-2 text-gray-700">Se souvenir de moi</span>
                            </label>
                            @if (Route::has('password.request'))
                                <a class="text-sm text-red-700 hover:text-red-900 font-semibold" href="{{ route('password.request') }}">
                                    Mot de passe oublié ?
                                </a>
                            @endif
                        </div>

                        <button type="submit" class="block w-full bg-gradient-to-r from-red-600 via-pink-500 to-red-400 mt-4 py-3 rounded-2xl hover:bg-red-700 hover:-translate-y-1 transition-all duration-500 text-white font-bold text-lg shadow-lg mb-2">
                            Se connecter
                        </button>
                        <div class="flex justify-center mt-4">
                            <a href="#" class="text-base text-red-600 hover:text-red-800 transition-all duration-300 font-semibold">Pas encore de compte ?</a>
                        </div>
                    </form>
                </x-authentication-card>
            </div>
        </div>
    </div>
</x-guest-layout>
