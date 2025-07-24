@extends('layouts.app')
@section('content')
<div class="max-w-5xl mx-auto py-8 px-2">
    <div class="flex items-center justify-between mb-4">
        <h2 class="text-3xl font-extrabold text-red-700 flex items-center">
            <i class="fa-solid fa-calendar-alt text-red-500 mr-3"></i>
            Emploi du temps – Cours
        </h2>
        <a href="{{ route('emploi-du-temps.create') }}"
           class="inline-flex items-center bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg shadow transition font-semibold">
            <i class="fa-solid fa-plus mr-2"></i> Ajouter un cours
        </a>
    </div>

    @if(session('success'))
        <div class="mb-4 p-3 rounded-lg bg-red-50 text-red-800 border border-red-200 flex items-center shadow-sm">
            <i class="fa-solid fa-check-circle mr-2"></i> {{ session('success') }}
        </div>
    @endif

    <!-- Filtres -->
    <div class="bg-white p-4 rounded-xl shadow mb-6 border border-red-100">
        <form method="GET" action="{{ route('emploi-du-temps.index') }}" class="grid md:grid-cols-5 gap-4">
            <div>
                <label for="classe" class="block text-sm font-medium text-red-700 mb-1">Classe</label>
                <select id="classe" name="classe" class="w-full rounded-md border-red-200 shadow-sm focus:border-red-300 focus:ring focus:ring-red-200 focus:ring-opacity-50">
                    <option value="">Toutes les classes</option>
                    @foreach($classes ?? [] as $classeOption)
                        <option value="{{ $classeOption->id }}" {{ request('classe') == $classeOption->id ? 'selected' : '' }}>
                            {{ $classeOption->nom }} (S{{ $classeOption->semestre }})
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="matiere" class="block text-sm font-medium text-red-700 mb-1">Matière</label>
                <select id="matiere" name="matiere" class="w-full rounded-md border-red-200 shadow-sm focus:border-red-300 focus:ring focus:ring-red-200 focus:ring-opacity-50">
                    <option value="">Toutes les matières</option>
                    @foreach($matieres ?? [] as $matiereOption)
                        <option value="{{ $matiereOption->id }}" {{ request('matiere') == $matiereOption->id ? 'selected' : '' }}>
                            {{ $matiereOption->nom }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="professeur" class="block text-sm font-medium text-red-700 mb-1">Professeur</label>
                <select id="professeur" name="professeur" class="w-full rounded-md border-red-200 shadow-sm focus:border-red-300 focus:ring focus:ring-red-200 focus:ring-opacity-50">
                    <option value="">Tous les professeurs</option>
                    @foreach($professeurs ?? [] as $professeurOption)
                        <option value="{{ $professeurOption->id }}" {{ request('professeur') == $professeurOption->id ? 'selected' : '' }}>
                            {{ $professeurOption->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="date" class="block text-sm font-medium text-red-700 mb-1">Date</label>
                <input type="date" id="date" name="date" value="{{ request('date') }}" class="w-full rounded-md border-red-200 shadow-sm focus:border-red-300 focus:ring focus:ring-red-200 focus:ring-opacity-50">
            </div>
            <div class="flex items-end">
                <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg transition font-medium flex items-center">
                    <i class="fas fa-filter mr-2"></i> Filtrer
                </button>
                @if(request()->has('classe') || request()->has('matiere') || request()->has('professeur') || request()->has('date'))
                    <a href="{{ route('emploi-du-temps.index') }}" class="ml-2 bg-gray-200 hover:bg-gray-300 text-gray-800 px-4 py-2 rounded-lg transition font-medium">
                        <i class="fas fa-times"></i>
                    </a>
                @endif
            </div>
        </form>
    </div>

    <div class="bg-gradient-to-br from-red-50 to-white rounded-2xl shadow-lg overflow-x-auto border border-gray-100">
        <table class="min-w-full text-base divide-y divide-gray-200">
            <thead>
                <tr>
                    <th class="px-3 py-3 text-center font-bold text-red-700 bg-white">Classe</th>
                    <th class="px-3 py-3 text-center font-bold text-red-700 bg-white">Matière</th>
                    <th class="px-3 py-3 text-center font-bold text-red-700 bg-white">Prof</th>
                    <th class="px-3 py-3 text-center font-bold text-red-700 bg-white">Type</th>
                    <th class="px-3 py-3 text-center font-bold text-red-700 bg-white">Date</th>
                    <th class="px-3 py-3 text-center font-bold text-red-700 bg-white">Heure</th>
                    <th class="px-3 py-3 text-center font-bold text-red-700 bg-white">État</th>
                    <th class="px-3 py-3 text-center font-bold text-red-700 bg-white">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($cours as $c)
                    @if($c->classe && $c->classe->coordinateur_id === Auth::id())
                    <tr class="hover:bg-red-50 transition">
                        <td class="px-3 py-3 text-center font-semibold text-red-800">
                            {{ $c->classe ? $c->classe->nom : '-' }}
                        </td>
                        <td class="px-3 py-3 text-center text-red-600">
                            {{ $c->matiere && is_object($c->matiere) ? $c->matiere->nom : $c->matiere }}
                        </td>
                        <td class="px-3 py-3 text-center">
                            {{ $c->professeur ? $c->professeur->name : '-' }}
                        </td>
                        <td class="px-3 py-3 text-center">
                            @forelse($c->types as $type)
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-bold shadow-sm
                                    @if($type->code === 'workshop') bg-yellow-100 text-yellow-800
                                    @elseif($type->code === 'e-learning') bg-blue-100 text-blue-800
                                    @else bg-gray-100 text-gray-800
                                    @endif">
                                    {{ $type->nom }}
                                </span>
                            @empty
                                <span class="text-gray-400 text-xs">-</span>
                            @endforelse
                        </td>
                        <td class="px-3 py-3 text-center text-gray-700">
                            {{ \Carbon\Carbon::parse($c->date)->format('d/m/Y') }}
                        </td>
                        <td class="px-3 py-3 text-center text-gray-500">
                            {{ $c->heure_debut }} - {{ $c->heure_fin }}
                        </td>
                        <td class="px-3 py-3 text-center">
                            @php
                                $etatColor = $c->etat === 'validé' ? 'bg-green-100 text-green-700' : ($c->etat === 'en attente' ? 'bg-yellow-100 text-yellow-700' : 'bg-gray-100 text-gray-700');
                            @endphp
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-semibold {{ $etatColor }}">
                                {{ ucfirst($c->etat) }}
                            </span>
                        </td>
                        <td class="px-3 py-3 text-center whitespace-nowrap flex justify-center gap-2">
                            <a href="{{ route('emploi-du-temps.edit', $c->id) }}"
                               class="px-2 py-1 rounded text-xs font-semibold bg-red-100 text-red-700 hover:bg-red-200 transition shadow">
                                <i class="fa-solid fa-pen-to-square mr-1"></i> Modifier
                            </a>
                            <form action="{{ route('emploi-du-temps.destroy', $c->id) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        class="px-2 py-1 rounded text-xs font-semibold bg-gray-100 text-gray-600 hover:bg-gray-200 transition shadow"
                                        onclick="return confirm('Supprimer ce cours ?')">
                                    <i class="fa-solid fa-trash mr-1"></i> Supprimer
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endif
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
