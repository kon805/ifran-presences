@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto py-10 px-4">
    <h1 class="text-3xl font-extrabold text-cyan-800 mb-6 flex items-center">
        <i class="fa-solid fa-people-arrows text-cyan-500 mr-3"></i>
        Assigner des étudiants aux parents
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

    <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
        <form action="{{ route('admin.users.assign-students') }}" method="POST">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label for="parent_id" class="block text-sm font-semibold text-cyan-700 mb-2">
                        <i class="fa-solid fa-user-tie text-cyan-500 mr-2"></i> Sélectionner un parent
                    </label>
                    <select name="parent_id" id="parent_id" class="border rounded-lg w-full px-3 py-2 focus:ring-cyan-500 focus:border-cyan-500" required>
                        <option value="">Choisir un parent...</option>
                        @foreach($parents as $parent)
                            <option value="{{ $parent->id }}" {{ old('parent_id') == $parent->id ? 'selected' : '' }}>
                                {{ $parent->name }} ({{ $parent->email }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-cyan-700 mb-2">
                        <i class="fa-solid fa-user-graduate text-cyan-500 mr-2"></i> Sélectionner les étudiants
                    </label>
                    <div class="bg-gray-50 rounded-xl border border-gray-200 p-4">
                        <div class="grid grid-cols-1 gap-2">
                            @foreach($etudiants as $etudiant)
                                <div class="flex items-center">
                                    <input type="checkbox"
                                           name="student_ids[]"
                                           value="{{ $etudiant->id }}"
                                           id="etudiant_{{ $etudiant->id }}"
                                           {{ (is_array(old('student_ids')) && in_array($etudiant->id, old('student_ids'))) ? 'checked' : '' }}
                                           class="rounded border-gray-300 text-cyan-600 shadow-sm focus:border-cyan-300 focus:ring focus:ring-cyan-200 focus:ring-opacity-50">
                                    <label for="etudiant_{{ $etudiant->id }}" class="ml-2 flex items-center cursor-pointer">
                                        <img src="{{ $etudiant->profile_photo_url }}" alt="{{ $etudiant->name }}"
                                             class="h-8 w-8 rounded-full border-2 border-cyan-200 mr-2">
                                        <div>
                                            <span class="font-medium text-gray-900">{{ $etudiant->name }}</span>
                                            <span class="text-sm text-gray-500 ml-2">(Matricule: {{ $etudiant->matricule }})</span>
                                        </div>
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex justify-end">
                <button type="submit" class="bg-cyan-600 hover:bg-cyan-700 text-white px-6 py-2 rounded-lg shadow font-bold transition flex items-center">
                    <i class="fa-solid fa-link mr-2"></i> Assigner les étudiants au parent
                </button>
            </div>
        </form>

        <!-- Liste des assignations existantes -->
        <div class="mt-8">
            <h2 class="text-xl font-bold text-cyan-800 mb-4 flex items-center">
                <i class="fa-solid fa-list-check text-cyan-500 mr-2"></i> Assignations existantes
            </h2>
            <div class="bg-gray-50 rounded-xl border border-gray-200 divide-y divide-gray-200">
                @foreach($parents as $parent)
                    @if(isset($assignments[$parent->id]) && count($assignments[$parent->id]) > 0)
                        <div class="p-4">
                            <div class="flex items-center space-x-4 mb-2">
                                <img src="{{ $parent->profile_photo_url }}" alt="{{ $parent->name }}"
                                     class="h-10 w-10 rounded-full border-2 border-cyan-200">
                                <div>
                                    <h3 class="font-semibold text-gray-900">{{ $parent->name }}</h3>
                                    <p class="text-sm text-gray-500">{{ $parent->email }}</p>
                                </div>
                            </div>
                            <div class="ml-14">
                                <p class="text-sm font-medium text-cyan-700 mb-2">Étudiants assignés :</p>
                                <div class="flex flex-wrap gap-2">
                                    @foreach($etudiants as $etudiant)
                                        @if(in_array($etudiant->id, $assignments[$parent->id]))
                                            <div class="inline-flex items-center px-3 py-1 rounded-full text-sm bg-cyan-50 text-cyan-700 border border-cyan-200">
                                                <span>{{ $etudiant->name }}</span>
                                                <form action="{{ route('admin.users.unassign-student') }}" method="POST" class="ml-2">
                                                    @csrf
                                                    @method('DELETE')
                                                    <input type="hidden" name="parent_id" value="{{ $parent->id }}">
                                                    <input type="hidden" name="student_id" value="{{ $etudiant->id }}">
                                                    <button type="submit" class="text-cyan-600 hover:text-cyan-800">
                                                        <i class="fa-solid fa-xmark"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection
