@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto py-6 px-2">
    <h2 class="text-3xl font-extrabold text-gray-800 mb-6 flex items-center">
        <i class="fa-solid fa-calendar-days text-indigo-500 mr-3 text-2xl"></i>
        Mon emploi du temps
    </h2>

    <div class="bg-gradient-to-br from-indigo-50 to-white rounded-2xl shadow-lg overflow-hidden border border-gray-100">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-indigo-100">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-indigo-700 tracking-wider uppercase">Date</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-indigo-700 tracking-wider uppercase">Horaire</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-indigo-700 tracking-wider uppercase">Matière</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-indigo-700 tracking-wider uppercase">Type</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-indigo-700 tracking-wider uppercase">Professeur</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-100">
                @forelse($cours as $c)
                    <tr class="hover:bg-indigo-50 transition">
                        <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-700 font-semibold">
                            <span class="inline-flex items-center">
                                <i class="fa-regular fa-calendar text-indigo-400 mr-2"></i>
                                {{ \Carbon\Carbon::parse($c->date)->format('d/m/Y') }}
                            </span>
                        </td>
                        <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-600">
                            <i class="fa-regular fa-clock text-indigo-400 mr-2"></i>
                            {{ $c->heure_debut }} - {{ $c->heure_fin }}
                        </td>
                        <td class="px-4 py-4 whitespace-nowrap text-sm font-bold text-indigo-600">
                            <i class="fa-solid fa-book-open mr-2 text-indigo-300"></i>
                            {{ $c->matiere->nom }}
                        </td>
                        <td class="px-4 py-4 whitespace-nowrap text-sm">
                            @foreach($c->types as $type)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold shadow
                                    @if($type->code === 'workshop') bg-orange-100 text-orange-800 border border-orange-200
                                    @elseif($type->code === 'e-learning') bg-blue-100 text-blue-800 border border-blue-200
                                    @else bg-gray-100 text-gray-800 border border-gray-200
                                    @endif">
                                    <i class="fa-solid fa-tag mr-1 text-xs opacity-60"></i>
                                    {{ $type->nom }}
                                </span>
                            @endforeach
                        </td>
                        <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-700">
                            <span class="inline-flex items-center">
                                <i class="fa-solid fa-user-tie text-indigo-400 mr-2"></i>
                                {{ $c->professeur->name }}
                            </span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-4 py-4 text-center text-gray-500 font-semibold">
                            <i class="fa-regular fa-face-frown text-xl mr-2"></i>
                            Aucun cours programmé
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6 flex justify-center">
        {{ $cours->links() }}
    </div>
</div>
@endsection
