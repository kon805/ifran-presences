@extends('layouts.app')
@section('content')
<div class="max-w-md mx-auto py-10 px-4">
    <h2 class="text-3xl font-extrabold text-cyan-800 mb-6 flex items-center">
        <i class="fa-solid fa-book-open text-cyan-500 mr-3"></i>
        Créer une matière
    </h2>
    <form action="{{ route('admin.matieres.store') }}" method="POST" class="bg-white rounded-2xl shadow-lg p-8 border border-gray-100 space-y-6">
        @csrf
        <div>
            <label for="nom" class="block text-sm font-semibold text-cyan-700 mb-1">
                <i class="fa-solid fa-book text-cyan-400 mr-1"></i> Nom de la matière
            </label>
            <input type="text" name="nom" id="nom" class="border rounded-lg w-full px-3 py-2 focus:ring-cyan-500 focus:border-cyan-500" required>
        </div>
        <button type="submit"
                class="w-full bg-cyan-600 hover:bg-cyan-700 text-white px-6 py-2 rounded-lg shadow font-bold transition flex items-center justify-center">
            <i class="fa-solid fa-plus mr-2"></i> Créer
        </button>
    </form>
</div>
@endsection
