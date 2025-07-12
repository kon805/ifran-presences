@extends('layouts.app')
@section('content')
<div class="max-w-4xl mx-auto py-10">
    <h2 class="text-2xl font-bold mb-6">Mon profil</h2>
    <div class="bg-white shadow rounded p-6">
        <p><strong>Nom :</strong> {{ Auth::user()->name }}</p>
        <p><strong>Email :</strong> {{ Auth::user()->email }}</p>
        <p><strong>Rôle :</strong> {{ Auth::user()->role }}</p>
        <p><strong>Matricule :</strong> {{ Auth::user()->matricule }}</p>
        <p><strong>Classe :</strong> {{ Auth::user()->classe ? Auth::user()->classe->nom : 'Aucune' }}</p>
        <p><strong>Date de création :</strong> {{ Auth::user()->created_at->format('d/m/Y') }}</p>
    </div>
</div>
@endsection
