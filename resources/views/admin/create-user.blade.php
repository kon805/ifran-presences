@extends('layouts.app')

@section('content')
<div class="container mx-auto py-8">
    <h1 class="text-2xl font-bold mb-4">Créer un utilisateur</h1>
     @if (session('success'))
                <div class="text-green-600 mb-2">{{ session('success') }}</div>
            @endif
            @if (session('error'))
                <div class="text-red-600 mb-2">{{ session('error') }}</div>
            @endif
    <form action="{{ route('admin.users.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
        @csrf
        <div>
            <label for="name" class="block font-medium">Nom</label>
            <input type="text" name="name" id="name" class="border rounded w-full p-2" required>
        </div>
        <div>
            <label for="email" class="block font-medium">Email</label>
            <input type="email" name="email" id="email" class="border rounded w-full p-2" required>
        </div>
        <div>
            <label for="password" class="block font-medium">Mot de passe</label>
            <input type="password" name="password" id="password" class="border rounded w-full p-2" required>
        </div>
        <div>
            <label for="password_confirmation" class="block font-medium">Confirmer le mot de passe</label>
            <input type="password" name="password_confirmation" id="password_confirmation" class="border rounded w-full p-2" required>
        </div>
        <div>
            <label for="role" class="block font-medium">Rôle</label>
            <select name="role" id="role" class="border rounded w-full p-2" required>
                <option value="admin">Admin</option>
                <option value="professeur">Professeur</option>
                <option value="coordinateur">Coordinateur</option>
                <option value="etudiant">Étudiant</option>
            </select>
        </div>
        <div>
            <label for="matricule" class="block font-medium">Matricule</label>
            <input type="text" name="matricule" id="matricule" class="border rounded w-full p-2" required>
        </div>
        <div>
            <label for="photo" class="block font-medium">Photo</label>
            <input type="file" name="photo" id="photo" class="border rounded w-full p-2">
        </div>
        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Créer</button>
    </form>
</div>
@endsection
