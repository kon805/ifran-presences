@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto py-12 px-4">
    <div class="relative bg-gradient-to-br from-black via-gray-900 to-neutral-800 rounded-3xl shadow-2xl border border-gray-800 overflow-hidden">
        <!-- Deco shapes -->
        <div class="absolute -top-16 -left-16 w-56 h-56 bg-gradient-to-tr from-pink-600/30 via-cyan-400/20 to-black/0 rounded-full blur-2xl z-0"></div>
        <div class="absolute -bottom-20 -right-14 w-64 h-64 bg-gradient-to-tl from-cyan-500/20 via-pink-400/20 to-black/0 rounded-full blur-2xl z-0"></div>
        <div class="relative z-10 px-8 py-12">
            <h1 class="text-4xl font-black text-white mb-8 flex items-center tracking-tight drop-shadow">
                <span class="inline-flex justify-center items-center w-12 h-12 bg-gradient-to-br from-cyan-500 via-pink-500 to-black rounded-full shadow-lg mr-4">
                    <i class="fa-solid fa-user-plus text-white text-2xl"></i>
                </span>
                Créer un utilisateur
            </h1>
            @if (session('success'))
                <div class="mb-5 p-3 rounded-xl bg-green-800/20 text-green-200 border border-green-700 shadow flex items-center">
                    <i class="fa-solid fa-check-circle mr-2"></i> {{ session('success') }}
                </div>
            @endif
            @if (session('error'))
                <div class="mb-5 p-3 rounded-xl bg-red-800/20 text-red-200 border border-red-700 shadow flex items-center">
                    <i class="fa-solid fa-circle-exclamation mr-2"></i> {{ session('error') }}
                </div>
            @endif
            <form action="{{ route('admin.users.store') }}" method="POST" enctype="multipart/form-data" class="space-y-7">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="name" class="block text-xs font-bold uppercase tracking-wide text-gray-300 mb-2">
                            <i class="fa-solid fa-user mr-1 text-cyan-400"></i> Nom
                        </label>
                        <input type="text" name="name" id="name"
                            class="border-0 ring-2 ring-gray-800 focus:ring-cyan-500 w-full px-4 py-2 rounded-xl bg-gray-900 text-white placeholder-gray-500 shadow"
                            required autocomplete="off">
                    </div>
                    <div>
                        <label for="email" class="block text-xs font-bold uppercase tracking-wide text-gray-300 mb-2">
                            <i class="fa-solid fa-envelope mr-1 text-cyan-400"></i> Email
                        </label>
                        <input type="email" name="email" id="email"
                            class="border-0 ring-2 ring-gray-800 focus:ring-cyan-500 w-full px-4 py-2 rounded-xl bg-gray-900 text-white placeholder-gray-500 shadow"
                            required autocomplete="off">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="password" class="block text-xs font-bold uppercase tracking-wide text-gray-300 mb-2">
                            <i class="fa-solid fa-lock mr-1 text-cyan-400"></i> Mot de passe
                        </label>
                        <input type="password" name="password" id="password"
                            class="border-0 ring-2 ring-gray-800 focus:ring-pink-500 w-full px-4 py-2 rounded-xl bg-gray-900 text-white placeholder-gray-500 shadow"
                            required autocomplete="new-password">
                    </div>
                    <div>
                        <label for="password_confirmation" class="block text-xs font-bold uppercase tracking-wide text-gray-300 mb-2">
                            <i class="fa-solid fa-lock mr-1 text-cyan-400"></i> Confirmer le mot de passe
                        </label>
                        <input type="password" name="password_confirmation" id="password_confirmation"
                            class="border-0 ring-2 ring-gray-800 focus:ring-pink-500 w-full px-4 py-2 rounded-xl bg-gray-900 text-white placeholder-gray-500 shadow"
                            required autocomplete="new-password">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="role" class="block text-xs font-bold uppercase tracking-wide text-gray-300 mb-2">
                            <i class="fa-solid fa-id-badge mr-1 text-cyan-400"></i> Rôle
                        </label>
                        <select name="role" id="role"
                            class="border-0 ring-2 ring-gray-800 focus:ring-cyan-500 w-full px-4 py-2 rounded-xl bg-gray-900 text-white shadow"
                            required>
                            <option value="admin">Admin</option>
                            <option value="professeur">Professeur</option>
                            <option value="coordinateur">Coordinateur</option>
                            <option value="etudiant">Étudiant</option>
                            <option value="parent">Parent</option>
                        </select>
                    </div>
                    <div>
                        <label for="matricule" class="block text-xs font-bold uppercase tracking-wide text-gray-300 mb-2">
                            <i class="fa-solid fa-hashtag mr-1 text-cyan-400"></i> Matricule
                        </label>
                        <input type="text" name="matricule" id="matricule"
                            class="border-0 ring-2 ring-gray-800 focus:ring-cyan-500 w-full px-4 py-2 rounded-xl bg-gray-900 text-white placeholder-gray-500 shadow"
                            required autocomplete="off">
                    </div>
                </div>

                <div>
                    <label for="photo" class="block text-xs font-bold uppercase tracking-wide text-gray-300 mb-2">
                        <i class="fa-solid fa-image mr-1 text-cyan-400"></i> Photo
                    </label>
                    <input type="file" name="photo" id="photo"
                        class="block border-0 ring-2 ring-gray-800 focus:ring-cyan-500 w-full px-4 py-2 rounded-xl bg-gray-900 text-white placeholder-gray-500 shadow file:bg-cyan-600 file:text-white file:rounded-lg file:border-0 file:px-4 file:py-2 file:mr-4 file:font-bold">
                </div>

                <button type="submit"
                    class="w-full bg-gradient-to-r from-cyan-500 via-gray-900 to-pink-500 hover:from-pink-500 hover:to-cyan-600 text-white px-8 py-3 rounded-xl shadow-lg font-extrabold uppercase tracking-widest transition-all flex items-center justify-center text-lg border-2 border-cyan-900 hover:border-pink-600">
                    <i class="fa-solid fa-user-plus mr-3"></i> Créer
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
