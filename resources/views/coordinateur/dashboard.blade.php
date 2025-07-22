@extends('layouts.app')

@section('content')
<div class="container mx-auto py-8">
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-3xl font-extrabold text-gray-800">Dashboard Coordinateur</h1>
            <p class="text-md text-gray-500 mt-2">Bienvenue sur le tableau de bord du coordinateur.</p>
        </div>
        <div class="hidden md:flex items-center space-x-2">
            <span class="inline-block px-3 py-1 bg-blue-100 text-blue-700 rounded-full text-xs font-semibold shadow">Coordinateur</span>
        </div>
    </div>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(auth()->user()->role === 'coordinateur')
                <!-- Responsive grid: 2 columns on small screens, 2 on md, 3 on lg+ -->
                <div class="grid grid-cols-2 md:grid-cols-2 lg:grid-cols-3 gap-8 mb-6">
                    @php
                        $cards = [
                            [
                                'bg' => 'bg-red-100',
                                'iconBg' => 'bg-red-200',
                                'iconColor' => 'text-red-600',
                                'title' => "Absences non justifiées",
                                'value' => $stats['absences_non_justifiees'],
                                'valueColor' => 'text-red-700',
                                'svg' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />',
                            ],
                            [
                                'bg' => 'bg-green-100',
                                'iconBg' => 'bg-green-200',
                                'iconColor' => 'text-green-600',
                                'title' => "Absences justifiées",
                                'value' => $stats['absences_justifiees'],
                                'valueColor' => 'text-green-700',
                                'svg' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />',
                            ],
                            [
                                'bg' => 'bg-yellow-100',
                                'iconBg' => 'bg-yellow-200',
                                'iconColor' => 'text-yellow-600',
                                'title' => "Étudiants droppés",
                                'value' => $stats['etudiants_dropped'],
                                'valueColor' => 'text-yellow-700',
                                'svg' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />',
                            ],
                            [
                                'bg' => 'bg-blue-100',
                                'iconBg' => 'bg-blue-200',
                                'iconColor' => 'text-blue-600',
                                'title' => "Nombre de classes",
                                'value' => $stats['nombre_classes'],
                                'valueColor' => 'text-blue-700',
                                'svg' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />',
                            ],
                            [
                                'bg' => 'bg-purple-100',
                                'iconBg' => 'bg-purple-200',
                                'iconColor' => 'text-purple-600',
                                'title' => "Nombre de professeurs",
                                'value' => $stats['nombre_professeurs'],
                                'valueColor' => 'text-purple-700',
                                'svg' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />',
                            ],
                            [
                                'bg' => 'bg-gray-100',
                                'iconBg' => 'bg-gray-200',
                                'iconColor' => 'text-gray-600',
                                'title' => "Taux d'absentéisme",
                                'value' => $stats['taux_absenteisme'].'%',
                                'valueColor' => 'text-gray-700',
                                'subtitle' => "Sur {$stats['total_etudiants']} étudiants",
                                'svg' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z" />',
                            ],
                            [
                                'bg' => 'bg-indigo-100',
                                'iconBg' => 'bg-indigo-200',
                                'iconColor' => 'text-indigo-600',
                                'title' => "Total absences",
                                'value' => $stats['absences_total'],
                                'valueColor' => 'text-indigo-700',
                                'svg' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />',
                            ],
                        ];
                    @endphp

                    @foreach($cards as $card)
                    <div class="{{ $card['bg'] }} rounded-2xl p-6 shadow-lg hover:shadow-2xl transition-all duration-300 group relative animate-fade-in">
                        <div class="flex items-center">
                            <div class="p-3 {{ $card['iconBg'] }} rounded-xl shadow group-hover:scale-110 group-hover:ring-2 group-hover:ring-white transition duration-300">
                                <svg class="w-7 h-7 {{ $card['iconColor'] }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    {!! $card['svg'] !!}
                                </svg>
                            </div>
                            <div class="ml-4">
                                <h2 class="text-lg font-bold text-gray-800">{{ $card['title'] }}</h2>
                                <p class="mt-2 text-3xl font-extrabold {{ $card['valueColor'] }} drop-shadow-sm">{{ $card['value'] }}</p>
                                @if(isset($card['subtitle']))
                                    <p class="text-xs text-gray-500">{{ $card['subtitle'] }}</p>
                                @endif
                            </div>
                        </div>
                        <span class="absolute inset-0 rounded-2xl pointer-events-none group-hover:ring-4 group-hover:ring-blue-200 transition"></span>
                    </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
@keyframes fade-in {
  from { opacity: 0; transform: translateY(10px);}
  to   { opacity: 1; transform: translateY(0);}
}
.animate-fade-in {
  animation: fade-in .6s cubic-bezier(.39,.575,.565,1) both;
}
</style>
@endpush
