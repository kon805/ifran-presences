@extends('layouts.app')

@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <style>
        /* Style personnalisé pour Select2 pour s'adapter au design de Tailwind */
        .select2-container .select2-selection--single {
            height: 42px !important;
            border-color: #e5e7eb !important;
            border-radius: 0.5rem !important;
            padding: 0.375rem 0.75rem !important;
        }
        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 28px !important;
            padding-left: 0 !important;
            color: #1f2937 !important;
        }
        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 40px !important;
        }
        .select2-container--default .select2-results__option--highlighted[aria-selected] {
            background-color: #000 !important;
        }
        .select2-container--default .select2-selection--multiple {
            border-color: #e5e7eb !important;
            border-radius: 0.5rem !important;
        }
        .select2-container--default .select2-selection--multiple .select2-selection__choice {
            background-color: #f3f4f6 !important;
            border: 1px solid #000 !important;
            border-radius: 9999px !important;
            padding: 2px 8px !important;
        }
        .select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
            color: #000 !important;
            margin-right: 5px !important;
        }
    </style>
@endpush

@section('content')
<div class="max-w-7xl mx-auto py-10 px-4">
    <h1 class="text-3xl font-extrabold text-black mb-6 flex items-center">
        <i class="fa-solid fa-people-arrows text-black mr-3"></i>
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
                    <label for="parent_id" class="block text-sm font-semibold text-black mb-2">
                        <i class="fa-solid fa-user-tie text-black mr-2"></i> Sélectionner un parent
                    </label>
                    <select name="parent_id" id="parent_id" class="parent-select border rounded-lg w-full px-3 py-2 focus:ring-black focus:border-black" required>
                        <option value="">Choisir un parent...</option>
                        @foreach($parents as $parent)
                            <option value="{{ $parent->id }}" {{ old('parent_id') == $parent->id ? 'selected' : '' }}
                                data-image="{{ $parent->profile_photo_url }}">
                                {{ $parent->name }} ({{ $parent->email }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="student_ids" class="block text-sm font-semibold text-black mb-2">
                        <i class="fa-solid fa-user-graduate text-black mr-2"></i> Sélectionner les étudiants
                    </label>
                    <div class="bg-gray-50 rounded-xl border border-gray-200 p-4">
                        <select name="student_ids[]" id="student_ids" class="students-select w-full" multiple="multiple">
                            @foreach($etudiants as $etudiant)
                                <option value="{{ $etudiant->id }}"
                                    {{ (is_array(old('student_ids')) && in_array($etudiant->id, old('student_ids'))) ? 'selected' : '' }}
                                    data-image="{{ $etudiant->profile_photo_url }}"
                                    data-matricule="{{ $etudiant->matricule }}">
                                    {{ $etudiant->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <div class="flex justify-end">
                <button type="submit" class="bg-black hover:bg-gray-800 text-white px-6 py-2 rounded-lg shadow font-bold transition flex items-center">
                    <i class="fa-solid fa-link mr-2"></i> Assigner les étudiants au parent
                </button>
            </div>
        </form>

        <!-- Liste des assignations existantes -->
        <div class="mt-8">
            <h2 class="text-xl font-bold text-black mb-4 flex items-center">
                <i class="fa-solid fa-list-check text-black mr-2"></i> Assignations existantes
            </h2>
            <div class="bg-gray-50 rounded-xl border border-gray-200 divide-y divide-gray-200">
                @foreach($parents as $parent)
                    @if(isset($assignments[$parent->id]) && count($assignments[$parent->id]) > 0)
                        <div class="p-4">
                            <div class="flex items-center space-x-4 mb-2">
                                <img src="{{ $parent->profile_photo_url }}" alt="{{ $parent->name }}"
                                     class="h-10 w-10 rounded-full border-2 border-black">
                                <div>
                                    <h3 class="font-semibold text-gray-900">{{ $parent->name }}</h3>
                                    <p class="text-sm text-gray-500">{{ $parent->email }}</p>
                                </div>
                            </div>
                            <div class="ml-14">
                                <p class="text-sm font-medium text-black mb-2">Étudiants assignés :</p>
                                <div class="flex flex-wrap gap-2">
                                    @foreach($etudiants as $etudiant)
                                        @if(in_array($etudiant->id, $assignments[$parent->id]))
                                            <div class="inline-flex items-center px-3 py-1 rounded-full text-sm bg-black/10 text-black border border-black">
                                                <span>{{ $etudiant->name }}</span>
                                                <form action="{{ route('admin.users.unassign-student') }}" method="POST" class="ml-2">
                                                    @csrf
                                                    @method('DELETE')
                                                    <input type="hidden" name="parent_id" value="{{ $parent->id }}">
                                                    <input type="hidden" name="student_id" value="{{ $etudiant->id }}">
                                                    <button type="submit" class="text-black hover:text-red-600">
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

@push('scripts')
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $(document).ready(function() {
        // Initialisation du select pour les parents avec format personnalisé
        $('.parent-select').select2({
            placeholder: "Choisir un parent...",
            allowClear: true,
            templateResult: formatParent,
            templateSelection: formatParent
        });

        // Initialisation du select pour les étudiants avec format personnalisé
        $('.students-select').select2({
            placeholder: "Rechercher et sélectionner des étudiants...",
            allowClear: true,
            templateResult: formatStudent,
            templateSelection: formatStudentSelection
        });

        // Fonction pour formater l'affichage des parents dans la liste déroulante
        function formatParent(parent) {
            if (!parent.id) {
                return parent.text;
            }

            var $parent = $(
                '<div class="flex items-center">' +
                    '<img src="' + $(parent.element).data('image') + '" class="h-8 w-8 rounded-full mr-3 border border-gray-300" />' +
                    '<div>' + parent.text + '</div>' +
                '</div>'
            );

            return $parent;
        };

        // Fonction pour formater l'affichage des étudiants dans la liste déroulante
        function formatStudent(student) {
            if (!student.id) {
                return student.text;
            }

            var $student = $(
                '<div class="flex items-center py-1">' +
                    '<img src="' + $(student.element).data('image') + '" class="h-8 w-8 rounded-full mr-3 border-2 border-black" />' +
                    '<div>' +
                        '<div class="font-medium">' + student.text + '</div>' +
                        '<div class="text-xs text-gray-500">Matricule: ' + $(student.element).data('matricule') + '</div>' +
                    '</div>' +
                '</div>'
            );

            return $student;
        };

        // Fonction pour formater l'affichage des étudiants sélectionnés
        function formatStudentSelection(student) {
            if (!student.id) {
                return student.text;
            }
            return student.text + ' (Matricule: ' + $(student.element).data('matricule') + ')';
        };
    });
</script>
@endpush

@endsection
