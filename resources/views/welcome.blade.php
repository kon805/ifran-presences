<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Simple App - Laravel</title>
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @else
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@3.4.1/dist/tailwind.min.css">
        @endif
        <style>
            body {
                min-height: 100vh;
                background: linear-gradient(135deg, #f43f5e 0%, #eab308 50%, #f97316 100%);
                background-attachment: fixed;
            }
            .main-card {
                background: rgba(255,255,255,0.95);
                border-radius: 2.5rem;
                box-shadow: 0 12px 38px 0 rgba(244,63,94,0.11);
                border: 1.5px solid #f43f5e33;
                /* glassmorphism effect */
                backdrop-filter: blur(8px);
            }
            .dot {
                position: absolute;
                border-radius: 50%;
                opacity: 0.17;
                z-index: 0;
                filter: blur(0.5px);
            }
            .dot1 { width: 200px; height: 200px; background: #f43f5e; top: -60px; left: -60px; }
            .dot2 { width: 160px; height: 160px; background: #eab308; bottom: -60px; right: 10vw; }
            .dot3 { width: 120px; height: 120px; background: #f97316; bottom: 10vh; left: 8vw; }
            .feature-icon {
                background: linear-gradient(135deg, #f97316 0%, #f43f5e 100%);
                color: white;
                box-shadow: 0 4px 16px 0 #f43f5e44;
            }
        </style>
    </head>
    <body class="flex flex-col justify-center items-center font-sans relative overflow-x-hidden">
        <!-- Decorative Dots -->
        <div class="dot dot1 animate-pulse"></div>
        <div class="dot dot2 animate-pulse"></div>
        <div class="dot dot3 animate-pulse"></div>

        <!-- Navbar -->
        <nav class="w-full max-w-6xl mx-auto px-4 pt-10 flex justify-between items-center z-10">
            <div class="flex items-center gap-3">
                <div class=" w-72    ">
                    <img src="{{ asset('img/if3.png') }}" alt="Gestion école illustration" class="w-72 h-20 md:h-24 object-contain md:object-cover rounded-lg shadow-lg transform transition duration-500 hover:scale-105 hover:rotate-1  drop-shadow-2xl" loading="lazy">

                </div>
            </div>
            <div class="flex gap-4">
                @auth
                <a href="{{ url('/dashboard') }}"
                   class="rounded-full px-6 py-2 font-bold text-white bg-gradient-to-br from-[#f43f5e] to-[#f97316] shadow hover:scale-105 transition-all duration-200">Dashboard</a>
                @else
                <a href="{{ route('login') }}"
                   class="rounded-full px-5 py-2 font-bold text-[#f43f5e] bg-white border-2 border-[#f43f5e] hover:bg-[#f43f5e] hover:text-white hover:scale-105 transition-all duration-200">Connexion</a>
                @if (Route::has('register'))
                <a href="{{ route('register') }}"
                   class="rounded-full px-5 py-2 font-bold text-white bg-gradient-to-br from-[#eab308] to-[#f97316] hover:scale-105 transition-all duration-200">Inscription</a>
                @endif
                @endauth
            </div>
        </nav>

        <!-- Main Card -->
        <main class="main-card w-full max-w-5xl mx-auto mt-16 mb-12 px-8 md:px-16 py-12 flex flex-col md:flex-row gap-12 items-center relative z-10">
            <div class="flex-1 flex flex-col gap-7">
                <h1 class="text-4xl md:text-5xl font-extrabold text-[#f43f5e] leading-tight mb-2 drop-shadow-xl">
                    Gagnez du temps,<br>
                    <span class="bg-gradient-to-r from-[#f43f5e] via-[#eab308] to-[#f97316] bg-clip-text text-transparent animate-pulse">organisez votre établissement</span>
                </h1>
                <p class="text-lg md:text-xl text-[#1b1b18] font-medium mb-4 max-w-xl">
                    Gérez vos classes, vos présences et vos absences simplement et efficacement. Simple App vous accompagne dans la gestion quotidienne de votre établissement.
                </p>
                <ul class="space-y-3">
                    <li class="flex items-center gap-2">
                        <span class="feature-icon w-8 h-8 rounded-full flex items-center justify-center text-lg"><i class="fa-solid fa-bolt"></i></span>
                        <span class="font-semibold text-[#f43f5e]">Interface rapide et intuitive</span>
                    </li>
                    <li class="flex items-center gap-2">
                        <span class="feature-icon w-8 h-8 rounded-full flex items-center justify-center text-lg"><i class="fa-solid fa-shield"></i></span>
                        <span class="font-semibold text-[#eab308]">Gestion sécurisée des données</span>
                    </li>
                    <li class="flex items-center gap-2">
                        <span class="feature-icon w-8 h-8 rounded-full flex items-center justify-center text-lg"><i class="fa-solid fa-calendar-check"></i></span>
                        <span class="font-semibold text-[#f97316]">Suivi précis des présences</span>
                    </li>
                </ul>
                <div class="mt-7 flex gap-4">
                    @auth
                        <a href="{{ url('/dashboard') }}"
                           class="px-8 py-3 text-lg font-bold rounded-full bg-gradient-to-r from-[#f43f5e] to-[#eab308] text-white shadow-lg hover:scale-105 transition-all duration-300">
                            Accéder au Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}"
                           class="px-8 py-3 text-lg font-bold rounded-full bg-gradient-to-r from-[#f43f5e] to-[#f97316] text-white shadow-lg hover:bg-[#f43f5e]/90 hover:scale-105 transition-all duration-300">
                            Se connecter
                        </a>

                    @endauth
                </div>
            </div>
            <!-- Illustration -->
            <div class="flex-1 flex flex-col items-center justify-center">
                <img src="{{ asset('img/tout.jpg') }}" alt="Gestion école illustration" class="w-96 max-w-xs md:max-w-md drop-shadow-2xl float-in" loading="lazy">
                <span class="mt-3 text-[#f43f5e] text-xl font-bold animate-pulse">Simple, moderne et efficace !</span>
            </div>
        </main>

        <footer class="w-full text-center text-base text-[#f43f5e] font-medium mb-4">
            &copy; {{ date('Y') }} Simple App — Fait avec <span class="text-[#eab308]">❤</span> à IFRAN
        </footer>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css"/>
    </body>
</html>
