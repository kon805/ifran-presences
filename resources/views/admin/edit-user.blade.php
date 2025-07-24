@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto py-10 px-4">
    <h2 class="text-3xl font-extrabold text-black mb-8 flex items-center">
        <span class="inline-flex justify-center items-center w-10 h-10 bg-black rounded-lg shadow mr-3">
            <i class="fa-solid fa-user-pen text-white text-xl"></i>
        </span>
        Modifier l'utilisateur
    </h2>
    <form action="{{ route('admin.users.update', $user->id) }}" method="POST" enctype="multipart/form-data" class="bg-white rounded-2xl shadow-lg p-8 border border-gray-100 space-y-6">
        @csrf
        @method('PUT')
        <div>
            <label for="name" class="block text-sm font-semibold text-black mb-2">
                <i class="fa-solid fa-user mr-1 text-black"></i> Nom
            </label>
            <input type="text" class="border rounded-lg w-full px-3 py-2 focus:ring-black focus:border-black" id="name" name="name" value="{{ old('name', $user->name) }}" required>
        </div>
        <div>
            <label for="email" class="block text-sm font-semibold text-black mb-2">
                <i class="fa-solid fa-envelope mr-1 text-black"></i> Email
            </label>
            <input type="email" class="border rounded-lg w-full px-3 py-2 focus:ring-black focus:border-black" id="email" name="email" value="{{ old('email', $user->email) }}" required>
        </div>
        <div>
            <label for="matricule" class="block text-sm font-semibold text-black mb-2">
                <i class="fa-solid fa-hashtag mr-1 text-black"></i> Matricule
            </label>
            <input type="text" class="border rounded-lg w-full px-3 py-2 focus:ring-black focus:border-black" id="matricule" name="matricule" value="{{ old('matricule', $user->matricule) }}" required>
        </div>
        <div>
            <label for="role" class="block text-sm font-semibold text-black mb-2">
                <i class="fa-solid fa-id-badge mr-1 text-black"></i> Rôle
            </label>
            <select class="border rounded-lg w-full px-3 py-2 focus:ring-black focus:border-black" id="role" name="role" required>
                <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>Admin</option>
                <option value="coordinateur" {{ old('role', $user->role) == 'coordinateur' ? 'selected' : '' }}>Coordinateur</option>
                <option value="professeur" {{ old('role', $user->role) == 'professeur' ? 'selected' : '' }}>Professeur</option>
                <option value="etudiant" {{ old('role', $user->role) == 'etudiant' ? 'selected' : '' }}>Étudiant</option>
            </select>
        </div>
        <div>
            <label for="photo" class="block text-sm font-semibold text-black mb-2">
                <i class="fa-solid fa-image mr-1 text-black"></i> Photo
            </label>
            <input type="file" class="border rounded-lg w-full px-3 py-2 focus:ring-black focus:border-black" id="photo" name="photo">
            @if($user->photo)
                <img src="{{ asset('storage/' . $user->photo) }}" alt="Photo" width="80" class="mt-2 rounded-lg border border-black/30 shadow">
            @endif
        </div>
        <div>
            <label for="password" class="block text-sm font-semibold text-black mb-2">
                <i class="fa-solid fa-lock mr-1 text-black"></i> Nouveau mot de passe (laisser vide pour ne pas changer)
            </label>
            <input type="password" class="border rounded-lg w-full px-3 py-2 focus:ring-black focus:border-black" id="password" name="password">
        </div>
        <div>
            <label for="password_confirmation" class="block text-sm font-semibold text-black mb-2">
                <i class="fa-solid fa-lock mr-1 text-black"></i> Confirmer le mot de passe
            </label>
            <input type="password" class="border rounded-lg w-full px-3 py-2 focus:ring-black focus:border-black" id="password_confirmation" name="password_confirmation">
        </div>
        <div class="flex justify-end">
            <button type="submit" class="bg-black hover:bg-gray-800 text-white px-6 py-2 rounded-lg shadow font-bold transition flex items-center">
                <i class="fa-solid fa-floppy-disk mr-2"></i> Mettre à jour
            </button>
        </div>
    </form>
</div>
@endsection
