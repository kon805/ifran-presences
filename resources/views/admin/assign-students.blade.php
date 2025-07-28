@extends('layouts.app')

@push('styles')
    <style>
        /* Style général de la page */
        .page-container {
            background: linear-gradient(135deg, #f6f8fb 0%, #f0f4f8 100%);
        }

        /* Style pour Select2 */
        .select2-container--default .select2-selection--multiple {
            border: 2px solid #e2e8f0 !important;
            border-radius: 1rem !important;
            padding: 0.5rem !important;
            min-height: 45px !important;
        }

        .select2-container--default.select2-container--focus .select2-selection--multiple {
            border-color: #3b82f6 !important;
            box-shadow: 0 0 0 3px rgba(59,130,246,0.1) !important;
        }

        .select2-container--default .select2-selection--multiple .select2-selection__choice {
            background: #3b82f6 !important;
            color: white !important;
            border: none !important;
            border-radius: 0.75rem !important;
            padding: 4px 8px !important;
            margin: 2px !important;
        }

        .select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
            color: white !important;
            margin-right: 5px !important;
            border: none !important;
            background: rgba(255,255,255,0.2) !important;
            border-radius: 50% !important;
            padding: 0 5px !important;
        }

        .select2-container--default .select2-selection--multiple .select2-selection__choice__remove:hover {
            background: rgba(255,255,255,0.3) !important;
        }

        .select2-dropdown {
            border: none !important;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06) !important;
            border-radius: 1rem !important;
            margin-top: 4px !important;
        }

        .select2-container--default .select2-results__option--highlighted[aria-selected] {
            background-color: #f0f9ff !important;
            color: #1f2937 !important;
        }

        .select2-results__option {
            padding: 8px !important;
        }
    </style>
@endpush

@section('content')
<div class="page-container min-h-screen py-12 px-4">
    <div class="max-w-6xl mx-auto">
        <h1 class="text-4xl font-black text-gray-800 mb-10 flex items-center">
            <span class="bg-blue-500 text-white p-3 rounded-2xl shadow-lg mr-4">
                <i class="fa-solid fa-people-arrows"></i>
            </span>
            <span>Assigner des étudiants aux parents</span>
        </h1>

        @if (session('success'))
            <div class="mb-6 p-4 rounded-xl bg-emerald-50 border-2 border-emerald-200 shadow-sm flex items-center animate-fade-in">
                <div class="bg-emerald-500 p-2 rounded-lg mr-3">
                    <i class="fa-solid fa-check text-white"></i>
                </div>
                <span class="text-emerald-700 font-medium">{{ session('success') }}</span>
            </div>
        @endif

        @if (session('error'))
            <div class="mb-6 p-4 rounded-xl bg-red-50 border-2 border-red-200 shadow-sm flex items-center animate-fade-in">
                <div class="bg-red-500 p-2 rounded-lg mr-3">
                    <i class="fa-solid fa-triangle-exclamation text-white"></i>
                </div>
                <span class="text-red-700 font-medium">{{ session('error') }}</span>
            </div>
        @endif

    <div class="bg-white rounded-2xl shadow-lg p-8 border-2 border-gray-100">
        <form action="{{ route('admin.users.assign-students') }}" method="POST">
            @csrf

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
                <div class="space-y-4">
                    <label for="select-parent" class="flex items-center space-x-2 text-gray-700 font-semibold">
                        <span class="bg-blue-100 p-2 rounded-lg">
                            <i class="fa-solid fa-user-tie text-blue-600"></i>
                        </span>
                        <span>Sélectionner un parent</span>
                    </label>
                    
                    <div class="relative">
                        <select id="select-parent" name="parent_id" required
                            class="w-full p-3 bg-gray-50 border-2 border-gray-200 rounded-xl focus:border-blue-500 focus:ring focus:ring-blue-200 transition-all duration-200">
                            <option value="">Choisir un parent...</option>
                            @foreach($parents as $parent)
                                <option value="{{ $parent->id }}" {{ old('parent_id') == $parent->id ? 'selected' : '' }}
                                    data-image="{{ $parent->profile_photo_url }}">
                                    {{ $parent->name }} ({{ $parent->email }})
                                </option>
                            @endforeach
                        </select>
                        <div class="absolute inset-y-0 right-0 flex items-center px-3 pointer-events-none text-gray-500">
                            <i class="fa-solid fa-chevron-down"></i>
                        </div>
                    </div>
                </div>

                <div class="space-y-4">
                    <label class="flex items-center space-x-2 text-gray-700 font-semibold">
                        <span class="bg-blue-100 p-2 rounded-lg">
                            <i class="fa-solid fa-user-graduate text-blue-600"></i>
                        </span>
                        <span>Sélectionner des étudiants</span>
                    </label>

                    <select id="select-students" name="student_ids[]" multiple class="w-full">
                        @foreach($etudiants as $etudiant)
                            <option value="{{ $etudiant->id }}" 
                                    data-image="{{ $etudiant->profile_photo_url }}"
                                    data-matricule="{{ $etudiant->matricule }}">
                                {{ $etudiant->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="flex justify-end pt-6 border-t border-gray-100">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-3 rounded-xl shadow-lg font-semibold text-lg flex items-center space-x-2 transition-all duration-200 transform hover:translate-y-[-2px]">
                    <i class="fa-solid fa-link"></i>
                    <span>Assigner</span>
                </button>
            </div>
        </form>

        <!-- Liste des assignations existantes -->
        <div class="mt-16">
            <h2 class="text-2xl font-bold text-gray-800 mb-8 flex items-center">
                <span class="bg-blue-100 p-3 rounded-xl mr-4">
                    <i class="fa-solid fa-list-check text-blue-600"></i>
                </span>
                Assignations existantes
            </h2>
            
            <div class="bg-white rounded-2xl border-2 border-gray-100 shadow-lg overflow-hidden">
                @forelse($parents as $parent)
                    @if(isset($assignments[$parent->id]) && count($assignments[$parent->id]) > 0)
                        <div class="p-6 hover:bg-gray-50 transition-all duration-200 border-b-2 border-gray-100 last:border-b-0">
                            <div class="flex items-center space-x-4 mb-4">
                                <div class="relative">
                                    <img src="{{ $parent->profile_photo_url }}" alt="{{ $parent->name }}"
                                         class="h-16 w-16 rounded-xl border-4 border-blue-100 object-cover">
                                    <div class="absolute -bottom-2 -right-2 bg-blue-500 text-white p-2 rounded-lg">
                                        <i class="fa-solid fa-user-tie text-sm"></i>
                                    </div>
                                </div>
                                <div>
                                    <h3 class="font-bold text-gray-900 text-xl mb-1">{{ $parent->name }}</h3>
                                    <p class="text-gray-500 flex items-center">
                                        <i class="fa-solid fa-envelope text-blue-400 mr-2"></i>
                                        {{ $parent->email }}
                                    </p>
                                </div>
                            </div>
                            
                            <div class="ml-20">
                                <div class="flex items-center text-sm font-medium text-gray-600 mb-3">
                                    <i class="fa-solid fa-user-graduate text-blue-400 mr-2"></i>
                                    Étudiants assignés
                                </div>
                                <div class="flex flex-wrap gap-3">
                                    @foreach($etudiants as $etudiant)
                                        @if(in_array($etudiant->id, $assignments[$parent->id]))
                                            <div class="group relative bg-white border-2 border-gray-200 rounded-xl p-2 pr-12 flex items-center space-x-3 hover:border-blue-400 hover:shadow-md transition-all duration-200">
                                                <img src="{{ $etudiant->profile_photo_url }}" 
                                                     alt="{{ $etudiant->name }}" 
                                                     class="h-10 w-10 rounded-lg border-2 border-blue-100 object-cover">
                                                <div class="flex flex-col">
                                                    <span class="font-medium text-gray-900">{{ $etudiant->name }}</span>
                                                    <span class="text-sm text-gray-500">Matricule: {{ $etudiant->matricule }}</span>
                                                </div>
                                                <form action="{{ route('admin.users.unassign-student') }}" 
                                                      method="POST" 
                                                      class="absolute right-2 top-1/2 transform -translate-y-1/2">
                                                    @csrf
                                                    @method('DELETE')
                                                    <input type="hidden" name="parent_id" value="{{ $parent->id }}">
                                                    <input type="hidden" name="student_id" value="{{ $etudiant->id }}">
                                                    <button type="submit" 
                                                            class="p-2 text-gray-400 hover:text-red-500 hover:bg-red-50 rounded-lg transition-all duration-200"
                                                            title="Retirer l'étudiant">
                                                        <i class="fa-solid fa-user-minus"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endif
                @empty
                    <div class="p-8 text-center">
                        <div class="bg-gray-50 rounded-2xl p-6 inline-block mb-4">
                            <i class="fa-solid fa-users-slash text-4xl text-gray-400"></i>
                        </div>
                        <p class="text-gray-500 font-medium">Aucune assignation trouvée</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

@push('scripts')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
$(document).ready(function() {
    $('#select-students').select2({
        placeholder: 'Cliquez pour sélectionner des étudiants',
        multiple: true,
        templateResult: formatStudent,
        templateSelection: formatStudentSelection,
        escapeMarkup: function(markup) {
            return markup;
        }
    });

    function formatStudent(student) {
        if (!student.id) return student.text;
        return `
            <div class="flex items-center gap-3">
                <img src="${student.element.dataset.image}" 
                     alt="${student.text}"
                     class="h-10 w-10 rounded-lg object-cover border-2 border-gray-200">
                <div>
                    <div class="font-medium">${student.text}</div>
                    <div class="text-sm text-gray-500">Matricule: ${student.element.dataset.matricule}</div>
                </div>
            </div>
        `;
    }

    function formatStudentSelection(student) {
        if (!student.id) return student.text;
        return `
            <div class="flex items-center gap-2">
                <img src="${student.element.dataset.image}" 
                     alt="${student.text}"
                     class="h-6 w-6 rounded-md object-cover border-2 border-gray-200">
                <span>${student.text}</span>
            </div>
        `;
    }
});
</script>
@endpush

@endsection