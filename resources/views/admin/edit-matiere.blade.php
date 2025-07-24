@extends('layouts.app')
@section('content')
<div class="max-w-lg mx-auto py-12 px-4">
    <div class="bg-white/80 backdrop-blur-md border border-black/10 rounded-3xl shadow-2xl p-8 relative">
        <!-- Decorative shape -->
        <div class="absolute -top-8 -right-8 w-32 h-32 bg-gradient-to-br from-black/30 via-gray-500/20 to-white/0 rounded-full blur-2xl z-0"></div>
        <div class="absolute -bottom-10 -left-10 w-40 h-40 bg-gradient-to-tl from-black/10 via-gray-800/20 to-white/0 rounded-full blur-2xl z-0"></div>

        <div class="relative z-10">
            <h2 class="text-3xl font-extrabold text-black flex items-center mb-8">
                <span class="inline-flex items-center justify-center w-12 h-12 bg-black rounded-2xl shadow-lg mr-4">
                    <i class="fa-solid fa-book text-white text-2xl"></i>
                </span>
                Modifier une matière
            </h2>
            <form action="{{ route('admin.matieres.update', $matiere->id) }}" method="POST" class="space-y-8">
                @csrf
                @method('PUT')
                <div>
                    <label for="nom" class="block text-sm font-bold text-black mb-2">
                        <i class="fa-solid fa-pen-nib mr-1 text-black"></i> Nom de la matière
                    </label>
                    <input
                        type="text"
                        name="nom"
                        id="nom"
                        class="border-2 border-black/30 rounded-xl w-full px-4 py-3 focus:ring-2 focus:ring-black focus:border-black bg-white text-black font-semibold text-lg shadow transition"
                        value="{{ $matiere->nom }}" required
                        autocomplete="off"
                    >
                </div>
                <div class="flex justify-end">
                    <button type="submit" class="inline-flex items-center bg-black hover:bg-gray-900 text-white font-bold px-7 py-3 rounded-xl shadow-lg transition text-lg">
                        <i class="fa-solid fa-floppy-disk mr-2"></i> Mettre à jour
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
