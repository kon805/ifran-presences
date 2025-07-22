@extends('layouts.app')
@section('content')
<div class="max-w-xl mx-auto py-10 px-4">
    <h2 class="text-3xl font-extrabold text-cyan-800 mb-8 flex items-center">
        <i class="fa-solid fa-user-circle text-cyan-500 mr-3"></i>
        Mon profil
    </h2>
    <div class="bg-white shadow-lg rounded-2xl p-8 flex flex-col items-center">
        <div class="mb-6">
            @if(Auth::user()->photo)
                <img src="{{ asset('storage/' . Auth::user()->photo) }}"
                     alt="Photo de profil"
                     class="w-32 h-32 object-cover rounded-full border-4 border-cyan-300 shadow">
            @else
                <div class="w-32 h-32 flex items-center justify-center bg-cyan-100 rounded-full border-4 border-cyan-300 shadow">
                    <i class="fa-solid fa-user text-6xl text-cyan-400"></i>
                </div>
            @endif
        </div>
        <div class="w-full space-y-4">
            <div class="flex items-center text-lg">
                <i class="fa-solid fa-user-tag text-cyan-400 mr-2"></i>
                <span class="font-semibold">Nom :</span>
                <span class="ml-2 text-gray-700">{{ Auth::user()->name }}</span>
            </div>
            <div class="flex items-center text-lg">
                <i class="fa-solid fa-envelope text-cyan-400 mr-2"></i>
                <span class="font-semibold">Email :</span>
                <span class="ml-2 text-gray-700">{{ Auth::user()->email }}</span>
            </div>
            <div class="flex items-center text-lg">
                <i class="fa-solid fa-id-badge text-cyan-400 mr-2"></i>
                <span class="font-semibold">Rôle :</span>
                <span class="ml-2 text-gray-700">{{ ucfirst(Auth::user()->role) }}</span>
            </div>
            <div class="flex items-center text-lg">
                <i class="fa-solid fa-hashtag text-cyan-400 mr-2"></i>
                <span class="font-semibold">Matricule :</span>
                <span class="ml-2 text-gray-700">{{ Auth::user()->matricule }}</span>
            </div>
            <div class="flex items-center text-lg">
                <i class="fa-solid fa-school text-cyan-400 mr-2"></i>
                <span class="font-semibold">Classe :</span>
                <span class="ml-2 text-gray-700">
                    {{ Auth::user()->classe ? Auth::user()->classe->nom : 'Aucune' }}
                </span>
            </div>
            <div class="flex items-center text-lg">
                <i class="fa-solid fa-calendar-days text-cyan-400 mr-2"></i>
                <span class="font-semibold">Date de création :</span>
                <span class="ml-2 text-gray-700">{{ Auth::user()->created_at->format('d/m/Y') }}</span>
            </div>
        </div>
    </div>
</div>
@endsection
