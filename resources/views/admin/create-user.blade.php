@extends('layouts.app')

@section('content')
<div class="max-w-xl mx-auto py-10 px-4">
    <h1 class="text-3xl font-extrabold text-cyan-800 mb-6 flex items-center">
        <i class="fa-solid fa-user-plus text-cyan-500 mr-3"></i>
        Créer un utilisateur
    </h1>
    @if (session('success'))
        <div class="mb-4 p-3 rounded-xl bg-green-50 text-green-800 border border-green-200 shadow flex items-center">
            <i class="fa-solid fa-check-circle mr-2"></i> {{ session('success') }}
        </div>
    @endif
    @if (session('error'))
        <div class="mb-4 p-3 rounded-xl bg-red-50 text-red-800 border border-red-200 shadow flex items-center">
            <i class="fa-solid fa-circle-exclamation mr-2"></i> {{ session('error') }}
        </div>
    @endif
    <form action="{{ route('admin.users.store') }}" method="POST" enctype="multipart/form-data" class="bg-white rounded-2xl shadow-lg p-8 border border-gray-100 space-y-6">
        @csrf
        <div>
            <label for="name" class="block text-sm font-semibold text-cyan-700 mb-1">
                <i class="fa-solid fa-user mr-1 text-cyan-400"></i> Nom
            </label>
            <input type="text" name="name" id="name" class="border rounded-lg w-full px-3 py-2 focus:ring-cyan-500 focus:border-cyan-500" required>
        </div>
        <div>
            <label for="email" class="block text-sm font-semibold text-cyan-700 mb-1">
                <i class="fa-solid fa-envelope mr-1 text-cyan-400"></i> Email
            </label>
            <input type="email" name="email" id="email" class="border rounded-lg w-full px-3 py-2 focus:ring-cyan-500 focus:border-cyan-500" required>
        </div>
        <div>
            <label for="password" class="block text-sm font-semibold text-cyan-700 mb-1">
                <i class="fa-solid fa-lock mr-1 text-cyan-400"></i> Mot de passe
            </label>
            <input type="password" name="password" id="password" class="border rounded-lg w-full px-3 py-2 focus:ring-cyan-500 focus:border-cyan-500" required>
        </div>
        <div>
            <label for="password_confirmation" class="block text-sm font-semibold text-cyan-700 mb-1">
                <i class="fa-solid fa-lock mr-1 text-cyan-400"></i> Confirmer le mot de passe
            </label>
            <input type="password" name="password_confirmation" id="password_confirmation" class="border rounded-lg w-full px-3 py-2 focus:ring-cyan-500 focus:border-cyan-500" required>
        </div>
        <div>
            <label for="role" class="block text-sm font-semibold text-cyan-700 mb-1">
                <i class="fa-solid fa-id-badge mr-1 text-cyan-400"></i> Rôle
            </label>
            <select name="role" id="role" class="border rounded-lg w-full px-3 py-2 focus:ring-cyan-500 focus:border-cyan-500" required>
                <option value="admin">Admin</option>
                <option value="professeur">Professeur</option>
                <option value="coordinateur">Coordinateur</option>
                <option value="etudiant">Étudiant</option>
                <option value="parent">
                    Parent
                </option>
            </select>
        </div>
        <div>
            <label for="matricule" class="block text-sm font-semibold text-cyan-700 mb-1">
                <i class="fa-solid fa-hashtag mr-1 text-cyan-400"></i> Matricule
            </label>
            <input type="text" name="matricule" id="matricule" class="border rounded-lg w-full px-3 py-2 focus:ring-cyan-500 focus:border-cyan-500" required>
        </div>
        <div>
            <label for="photo" class="block text-sm font-semibold text-cyan-700 mb-1">
                <i class="fa-solid fa-image mr-1 text-cyan-400"></i> Photo
            </label>
            <input type="file" name="photo" id="photo" class="border rounded-lg w-full px-3 py-2 focus:ring-cyan-500 focus:border-cyan-500">
        </div>
        <button type="submit"
                class="w-full bg-cyan-600 hover:bg-cyan-700 text-white px-6 py-2 rounded-lg shadow font-bold transition flex items-center justify-center">
            <i class="fa-solid fa-user-plus mr-2"></i> Créer
        </button>
    </form>
</div>
@endsection
