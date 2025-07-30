<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Simple App - Gestion Scolaire</title>
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
                background: linear-gradient(135deg, #2563eb 0%, #38bdf8 60%, #a7f3d0 100%);
                background-attachment: fixed;
            }
            .main-card {
                background: rgba(255,255,255,0.98);
                border-radius: 2.5rem;
                box-shadow: 0 12px 38px 0 rgba(37,99,235,0.12);
                border: 2px solid #2563eb25;
                /* glassmorphism effect */
                backdrop-filter: blur(9px);
            }
            .dot {
                position: absolute;
                border-radius: 50%;
                opacity: 0.12;
                z-index: 0;
                filter: blur(1px);
            }
            .dot1 { width: 190px; height: 190px; background: #2563eb; top: -50px; left: -50px; }
            .dot2 { width: 140px; height: 140px; background: #38bdf8; bottom: -60px; right: 12vw; }
            .dot3 { width: 100px; height: 100px; background: #a7f3d0; bottom: 10vh; left: 10vw; }
            .feature-icon {
                background: linear-gradient(135deg, #38bdf8 0%, #2563eb 100%);
                color: white;
                box-shadow: 0 4px 16px 0 #2563eb55;
            }
            .edu-gradient {
                background: linear-gradient(90deg, #2563eb 0%, #22c55e 50%, #facc15 100%);
                -webkit-background-clip: text;
                -webkit-text-fill-color: transparent;
                background-clip: text;
                color: transparent;
            }
            .float-in {
                animation: float-in 1.1s cubic-bezier(.52,1.5,.66,1) 0.2s both;
            }
            @keyframes float-in {
                0% { opacity: 0; transform: translateY(60px) scale(0.97);}
                100% { opacity: 1; transform: none;}
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
                <div class="w-72">
                    <img src="{{ asset('img/logo2.png') }}" alt="Gestion école illustration"
                         class="w-52 h-20 md:h-24 object-contain md:object-cover rounded-lg shadow-lg transition-transform duration-500 hover:scale-105 hover:rotate-1 drop-shadow-2xl"
                         loading="lazy">
                </div>
            </div>
            <div class="flex gap-4">
                @auth
                @if (Auth::user()->role === 'admin')
                    <a href="{{ url('/admin/dashboard') }}"
                       class="rounded-full px-5 py-2 font-bold text-white bg-[#2563eb] hover:bg-[#1e40af] hover:scale-105 transition-all duration-200">
                        Dashboard Admin
                    </a>
                @elseif (Auth::user()->role === 'professeur')
                    <a href="{{ url('/professeur/dashboard') }}"
                       class="rounded-full px-5 py-2 font-bold text-white bg-[#22c55e] hover:bg-[#16a34a] hover:scale-105 transition-all duration-200">
                        Dashboard Enseignant
                    </a>
                @elseif (Auth::user()->role === 'etudiant')
                    <a href="{{ url('/etudiant/dashboard') }}"
                       class="rounded-full px-5 py-2 font-bold text-white bg-[#facc15] hover:bg-[#eab308] hover:scale-105 transition-all duration-200">
                        Dashboard Élève
                    </a>
                @elseif (Auth::user()->role === 'parent')
                    <a href="{{ url('/parent') }}"
                       class="rounded-full px-5 py-2 font-bold text-white bg-[#2563eb] hover:bg-[#1e40af] hover:scale-105 transition-all duration-200">
                        Dashboard Parent
                    </a>
                @endif
                @if (Auth::user()->role === 'coordinateur')
                    <a href="{{ url('/coordinateur/dashboard') }}"
                       class="rounded-full px-5 py-2 font-bold text-white bg-[#2563eb] hover:bg-[#1e40af] hover:scale-105 transition-all duration-200">
                        Dashboard Coordinateur
                    </a>
                @endif
                @else
                <a href="{{ route('login') }}"
                   class="rounded-full px-5 py-2 font-bold text-[#2563eb] bg-white border-2 border-[#2563eb] hover:bg-[#2563eb] hover:text-white hover:scale-105 transition-all duration-200">Connexion</a>
                @if (Route::has('register'))
                <a href="{{ route('register') }}"
                   class="rounded-full px-5 py-2 font-bold text-white bg-gradient-to-br from-[#22c55e] to-[#facc15] hover:scale-105 transition-all duration-200">Inscription</a>
                @endif
                @endauth
            </div>
        </nav>

        <!-- Main Card -->
        <main class="main-card w-full max-w-5xl mx-auto mt-16 mb-14 px-8 md:px-16 py-14 flex flex-col md:flex-row gap-12 items-center relative z-10">
            <div class="flex-1 flex flex-col gap-8">
                <h1 class="text-4xl md:text-5xl font-extrabold text-[#2563eb] leading-tight mb-3 drop-shadow-xl">
                    Pilotez votre établissement,<br>
                    <span class="edu-gradient font-black animate-pulse">l'éducation simplifiée</span>
                </h1>
                <p class="text-lg md:text-xl text-[#1b3046] font-medium mb-4 max-w-xl">
                    Gérez vos classes, vos présences et absences, et suivez les progrès de vos élèves en toute simplicité.<br>
                    <span class="text-[#22c55e] font-semibold">Simple App</span> vous accompagne dans la gestion quotidienne de votre établissement pour un enseignement moderne et efficace.
                </p>
                <ul class="space-y-3">
                    <li class="flex items-center gap-2">
                        <span class="feature-icon w-8 h-8 rounded-full flex items-center justify-center text-lg"><i class="fa-solid fa-bolt"></i></span>
                        <span class="font-semibold text-[#2563eb]">Interface intuitive & rapide</span>
                    </li>
                    <li class="flex items-center gap-2">
                        <span class="feature-icon w-8 h-8 rounded-full flex items-center justify-center text-lg"><i class="fa-solid fa-shield"></i></span>
                        <span class="font-semibold text-[#22c55e]">Sécurité des données scolaires</span>
                    </li>
                    <li class="flex items-center gap-2">
                        <span class="feature-icon w-8 h-8 rounded-full flex items-center justify-center text-lg"><i class="fa-solid fa-calendar-check"></i></span>
                        <span class="font-semibold text-[#facc15]">Suivi précis des présences</span>
                    </li>
                    <li class="flex items-center gap-2">
                        <span class="feature-icon w-8 h-8 rounded-full flex items-center justify-center text-lg"><i class="fa-solid fa-graduation-cap"></i></span>
                        <span class="font-semibold text-[#2563eb]">Outils adaptés à l'enseignement</span>
                    </li>
                </ul>
                <div class="mt-8 flex gap-5">
                    @auth
                        <a href="{{ url('/dashboard') }}"
                           class="px-8 py-3 text-lg font-bold rounded-full bg-gradient-to-r from-[#2563eb] via-[#22c55e] to-[#facc15] text-white shadow-lg hover:scale-105 transition-all duration-300">
                            Accéder au Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}"
                           class="px-8 py-3 text-lg font-bold rounded-full bg-gradient-to-r from-[#2563eb] via-[#22c55e] to-[#facc15] text-white shadow-lg hover:bg-[#2563eb]/90 hover:scale-105 transition-all duration-300">
                            Se connecter
                        </a>
                    @endauth
                </div>
            </div>
            <!-- Illustration -->
            <div class="flex-1 flex flex-col items-center justify-center">
                <img src="{{ asset('img/tout.jpg') }}" alt="Gestion école illustration"
                     class="w-96 max-w-xs md:max-w-md drop-shadow-2xl float-in" loading="lazy">
                <span class="mt-3 text-[#2563eb] text-xl font-bold animate-pulse">Simple, éducatif, efficace !</span>
            </div>
        </main>

        <footer class="w-full text-center text-base text-[#2563eb] font-medium mb-7">
            &copy; {{ date('Y') }} Simple App — Fait avec <span class="text-[#facc15]">❤</span> à IFRAN
        </footer>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css"/>
    </body>
</html>
