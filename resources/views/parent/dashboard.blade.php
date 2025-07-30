
@extends('layouts.app')
@section('content')
<div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
    <h2 class="text-3xl font-extrabold text-blue-800 mb-6 flex items-center">
        <i class="fa-solid fa-house-user text-blue-500 mr-3"></i>
        Tableau de bord parent
    </h2>

    <div class="bg-white shadow-lg rounded-2xl p-6 border border-blue-200 mb-8 bg-gradient-to-br from-blue-50 via-white to-blue-100">
        <div class="flex items-center mb-4">
            <div class="bg-blue-100 p-3 rounded-full mr-4">
                <i class="fa-solid fa-child text-blue-600 text-xl"></i>
            </div>
            <h3 class="text-xl font-bold text-gray-800">Bienvenue dans votre espace parent</h3>
        </div>
        <p class="text-gray-700">
            Surveillez facilement les présences et absences de vos enfants. Consultez ci-dessous les informations
            récentes et accédez aux détails complets en un seul clic.
        </p>
    </div>

    <!-- Section Notifications Email -->
    <div class="bg-white shadow-lg rounded-2xl p-6 border border-blue-200 mb-8">
        <div class="flex items-center mb-6">
            <div class="bg-blue-100 p-3 rounded-full mr-4">
                <i class="fas fa-envelope text-blue-600 text-xl"></i>
            </div>
            <h3 class="text-xl font-bold text-gray-800">Notifications par Email</h3>
        </div>

        <form action="{{ route('parent.update-email') }}" method="POST" class="space-y-4">
            @csrf
            @method('PUT')

            <div class="max-w-md">
                <label for="email" class="block text-sm font-medium text-gray-700 mb-1">
                    Adresse email pour les notifications
                </label>
                <div class="flex gap-4">
                    <input type="email"
                           name="email"
                           id="email"
                           value="{{ auth()->user()->email }}"
                           class="flex-1 mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                           required>
                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                        Mettre à jour
                    </button>
                </div>
                @error('email')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="mt-4 text-sm text-gray-600">
                <p><i class="fas fa-info-circle mr-2"></i> Vous recevrez des notifications par email pour :</p>
                <ul class="list-disc ml-6 mt-2">
                    <li>Les absences de vos enfants</li>
                    <li>Les changements de statut dans les matières</li>
                    <li>Les justifications d'absence validées</li>
                </ul>
            </div>
        </form>
    </div>


</div>
@endsection
