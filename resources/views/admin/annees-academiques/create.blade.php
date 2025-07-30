@extends('layouts.app')
@section('content')
    <div class="py-16  min-h-screen">
        <div class="max-w-3xl mx-auto sm:px-8 lg:px-12">
            <div class="main-card bg-white/95 overflow-hidden shadow-2xl rounded-3xl p-10 border border-blue-200 relative">
                <div class="absolute -top-10 -left-10 w-36 h-36 bg-blue-200 rounded-full opacity-20 z-0"></div>
                <div class="absolute -bottom-10 -right-10 w-28 h-28 bg-green-200 rounded-full opacity-20 z-0"></div>
                <div class="mb-10 relative z-10">
                    <h2 class="text-3xl font-extrabold text-blue-900 flex items-center gap-2">
                        <i class="fa-solid fa-calendar-days text-green-500"></i>
                        Nouvelle Année Académique
                    </h2>
                    <p class="text-gray-500 mt-2">Créez une nouvelle année scolaire pour planifier vos cours et classes.</p>
                </div>

                <form action="{{ route('admin.annees-academiques.store') }}" method="POST" class="space-y-7 relative z-10">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                        <div>
                            <label for="annee" class="block text-base font-bold text-blue-800 mb-2">
                                Année <span class="text-yellow-500">*</span>
                            </label>
                            <input type="text"
                                   name="annee"
                                   id="annee"
                                   required
                                   class="mt-1 block w-full rounded-xl border-2 border-blue-200 shadow focus:border-green-400 focus:ring-2 focus:ring-blue-300 text-lg"
                                   placeholder="2025-2026">
                            @error('annee')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="date_debut" class="block text-base font-bold text-green-800 mb-2">
                                Date de début <span class="text-yellow-500">*</span>
                            </label>
                            <input type="date"
                                   name="date_debut"
                                   id="date_debut"
                                   required
                                   class="mt-1 block w-full rounded-xl border-2 border-green-200 shadow focus:border-blue-400 focus:ring-2 focus:ring-green-300 text-lg">
                            @error('date_debut')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="date_fin" class="block text-base font-bold text-yellow-800 mb-2">
                                Date de fin <span class="text-yellow-500">*</span>
                            </label>
                            <input type="date"
                                   name="date_fin"
                                   id="date_fin"
                                   required
                                   class="mt-1 block w-full rounded-xl border-2 border-yellow-200 shadow focus:border-blue-400 focus:ring-2 focus:ring-yellow-300 text-lg">
                            @error('date_fin')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="mt-8 flex gap-4">
                        <button type="submit"
                                class="bg-black text-white px-8 py-3 rounded-2xl font-bold shadow hover:scale-105 transition-all duration-150">
                            <i class="fa-solid fa-plus mr-2"></i>
                            Créer l'année académique
                        </button>
                        <a href="{{ route('admin.annees-academiques.index') }}"
                           class="ml-2 text-blue-700 hover:text-green-600 font-semibold flex items-center transition">
                            <i class="fa-solid fa-arrow-rotate-left mr-1"></i>
                            Annuler
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
