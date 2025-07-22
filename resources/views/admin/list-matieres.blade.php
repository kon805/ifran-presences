@extends('layouts.app')
@section('content')
<div class="max-w-xl mx-auto py-10 px-4">
    <div class="flex items-center justify-between mb-8">
        <h2 class="text-3xl font-extrabold text-cyan-800 flex items-center">
            <i class="fa-solid fa-book-open text-cyan-500 mr-3"></i>
            Liste des matières
        </h2>
        <a href="{{ route('admin.matieres.create') }}"
           class="inline-flex items-center bg-cyan-600 hover:bg-cyan-700 text-white px-5 py-2 rounded-lg shadow font-semibold transition">
            <i class="fa-solid fa-plus mr-2"></i> Créer une matière
        </a>
    </div>
    @if(session('success'))
        <div class="mb-4 p-3 rounded-xl bg-green-50 text-green-800 border border-green-200 shadow flex items-center">
            <i class="fa-solid fa-check-circle mr-2"></i> {{ session('success') }}
        </div>
    @endif
    <div class="bg-gradient-to-br from-cyan-50 to-white rounded-xl shadow-lg overflow-x-auto border border-gray-100">
        <table class="min-w-full text-sm divide-y divide-gray-200">
            <thead>
                <tr>
                    <th class="px-3 py-3 text-left font-bold text-cyan-700 bg-white border-b border-gray-200">Nom</th>
                    <th class="px-3 py-3 text-center font-bold text-cyan-700 bg-white border-b border-gray-200">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($matieres as $matiere)
                    <tr class="hover:bg-cyan-50 transition">
                        <td class="px-3 py-3 font-bold text-cyan-800 flex items-center">
                            <i class="fa-solid fa-book text-cyan-400 mr-2"></i>
                            {{ $matiere->nom }}
                        </td>
                        <td class="px-3 py-3 text-center">
                            <a href="{{ route('admin.matieres.edit', $matiere->id) }}"
                               class="inline-flex items-center px-3 py-1 rounded-lg text-xs font-semibold bg-blue-100 text-blue-700 hover:bg-blue-200 mr-2 shadow">
                                <i class="fa-solid fa-pen-to-square mr-1"></i> Modifier
                            </a>
                            <form action="{{ route('admin.matieres.destroy', $matiere->id) }}" method="POST" class="inline"
                                  onsubmit="return confirm('Supprimer cette matière ?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        class="inline-flex items-center px-3 py-1 rounded-lg text-xs font-semibold bg-red-100 text-red-700 hover:bg-red-200 shadow ml-2">
                                    <i class="fa-solid fa-trash mr-1"></i> Supprimer
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
