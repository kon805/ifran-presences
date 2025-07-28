<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\ParentStudent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AssignStudentsController extends Controller
{
    public function index()
    {
        $parents = User::where('role', 'parent')->orderBy('name')->get();
        $etudiants = User::where('role', 'etudiant')->orderBy('name')->get();

        // Get existing assignments
        $assignments = ParentStudent::with(['user', 'student'])->get()
            ->groupBy('user_id')
            ->map(function ($group) {
                return $group->pluck('student.id')->toArray();
            });

        return view('admin.assign-students', compact('parents', 'etudiants', 'assignments'));
    }

    public function assign(Request $request)
    {
        $request->validate([
            'parent_id' => 'required|exists:users,id',
            'student_ids' => 'required|array',
            'student_ids.*' => 'exists:users,id'
        ]);

        try {
            DB::beginTransaction();

            // Log des données pour déboguer
            Log::info('Données d\'assignation:', [
                'parent_id' => $request->parent_id,
                'student_ids' => $request->student_ids
            ]);

            // Vérifier que le parent est bien un parent
            $parent = User::findOrFail($request->parent_id);
            if ($parent->role !== 'parent') {
                return redirect()
                    ->route('admin.users.assign-students')
                    ->with('error', 'L\'utilisateur sélectionné n\'est pas un parent.');
            }

            // Delete existing assignments for these students
            // Nous ne supprimons pas les assignations existantes pour éviter de supprimer d'autres relations parent-étudiant
            ParentStudent::where('user_id', $request->parent_id)->delete();

            // Create new assignments
            foreach ($request->student_ids as $studentId) {
                // Vérifier que l'étudiant n'est pas déjà assigné à ce parent
                $existingAssignment = ParentStudent::where('user_id', $request->parent_id)
                    ->where('etudiant_id', $studentId)
                    ->first();
                
                if (!$existingAssignment) {
                    ParentStudent::create([
                        'user_id' => $request->parent_id,
                        'etudiant_id' => $studentId
                    ]);
                }
            }

            DB::commit();

            return redirect()
                ->route('admin.users.assign-students')
                ->with('success', 'Les étudiants ont été assignés au parent avec succès.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erreur d\'assignation: ' . $e->getMessage());
            return redirect()
                ->route('admin.users.assign-students')
                ->with('error', 'Une erreur est survenue: ' . $e->getMessage());
        }
    }

    public function unassign(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:users,id',
            'parent_id' => 'required|exists:users,id'
        ]);

        try {
            // Log des données pour déboguer
            Log::info('Données de désassignation:', [
                'parent_id' => $request->parent_id,
                'student_id' => $request->student_id
            ]);

            $deleted = ParentStudent::where('user_id', $request->parent_id)
                ->where('etudiant_id', $request->student_id)
                ->delete();

            Log::info('Résultat de suppression:', ['deleted' => $deleted]);

            if ($request->ajax()) {
                return response()->json(['success' => true]);
            } else {
                return redirect()
                    ->route('admin.users.assign-students')
                    ->with('success', 'L\'étudiant a été désassigné du parent avec succès.');
            }
        } catch (\Exception $e) {
            Log::error('Erreur de désassignation: ' . $e->getMessage());
            
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
            } else {
                return redirect()
                    ->route('admin.users.assign-students')
                    ->with('error', 'Une erreur est survenue: ' . $e->getMessage());
            }
        }
    }
}
