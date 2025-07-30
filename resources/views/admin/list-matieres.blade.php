@extends('layouts.app')
@section('content')
<div class="max-w-5xl mx-auto py-12 px-4">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-10 gap-6">
        <h2 class="text-3xl font-extrabold text-yellow-500 flex items-center drop-shadow">
            <span class="inline-flex justify-center items-center w-12 h-12 bg-gradient-to-br from-black via-gray-900 to-yellow-700 rounded-2xl shadow-lg mr-4">
                <i class="fa-solid fa-book-open text-white text-2xl"></i>
            </span>
            <span class="text-black">Liste des matières</span>
        </h2>
        <a href="{{ route('admin.matieres.create') }}"
           class="inline-flex items-center bg-black hover:from-gray-900 hover:to-yellow-800 text-white px-7 py-3 rounded-2xl shadow-xl font-bold transition text-lg border-2 border-yellow-600 hover:border-yellow-400 backdrop-blur-sm">
            <i class="fa-solid fa-plus mr-2"></i> Créer une matière
        </a>
    </div>
    @if(session('success'))
        <div class="mb-8 p-3 rounded-2xl bg-yellow-900/40 text-yellow-100 border border-yellow-700 shadow flex items-center backdrop-blur-md">
            <i class="fa-solid fa-check-circle mr-2"></i> {{ session('success') }}
        </div>
    @endif

    <div class="grid gap-8 sm:grid-cols-2 md:grid-cols-3">
        @forelse($matieres as $matiere)
        <div class="black-glass group relative rounded-3xl bg-black/60 border border-yellow-700/40 shadow-2xl p-6 flex flex-col items-center justify-between transition-all hover:scale-105 hover:shadow-3xl hover:bg-black/80 backdrop-blur-md">
            <div class="flex flex-col items-center gap-2">
                <span class="inline-flex justify-center items-center w-14 h-14 bg-gradient-to-br from-black via-gray-900 to-yellow-700 rounded-2xl shadow-lg mb-2">
                    <i class="fa-solid fa-book text-white text-2xl"></i>
                </span>
                <h3 class="text-xl font-extrabold text-yellow-100 text-center group-hover:text-yellow-400 transition-all group-hover:scale-105 drop-shadow-lg">{{ $matiere->nom }}</h3>
            </div>
            <div class="flex justify-center gap-4 mt-6 w-full">
                <a href="{{ route('admin.matieres.edit', $matiere->id) }}"
                   class="inline-flex items-center px-4 py-2 rounded-xl text-xs font-bold bg-yellow-800/80 text-yellow-100 hover:bg-yellow-900/90 shadow transition-all backdrop-blur-md">
                    <i class="fa-solid fa-pen-to-square mr-1"></i> Modifier
                </a>
                <form action="{{ route('admin.matieres.destroy', $matiere->id) }}" method="POST" class="inline"
                      onsubmit="return confirm('Supprimer cette matière ?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                            class="inline-flex items-center px-4 py-2 rounded-xl text-xs font-bold bg-black/80 text-yellow-100 hover:bg-black/90 shadow transition-all backdrop-blur-md">
                        <i class="fa-solid fa-trash mr-1"></i> Supprimer
                    </button>
                </form>
            </div>
            <div class="absolute -top-6 -right-6 w-16 h-16 bg-yellow-800/10 rounded-full blur-2xl z-0"></div>
        </div>
        @empty
        <div class="black-glass rounded-3xl bg-black/50 border border-yellow-700/40 shadow-xl p-8 text-center text-yellow-200 font-semibold col-span-full backdrop-blur-md">
            Aucune matière enregistrée.
        </div>
        @endforelse
    </div>
</div>

<style>
.black-glass {
    /* Black glassmorphism effect */
    background: rgba(22, 24, 33, 0.83);
    box-shadow: 0 8px 40px 0 rgba(0,0,0,0.16), 0 1.5px 8px 0 rgba(255,215,90,0.1);
    border: 2px solid rgba(255,215,90,0.19);
    backdrop-filter: blur(14px);
    transition: all 0.22s cubic-bezier(0.4,0.1,0.4,1);
}
</style>
@endsection
