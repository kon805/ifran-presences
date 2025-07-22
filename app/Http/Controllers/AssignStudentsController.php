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

            // Delete existing assignments for these students
            ParentStudent::whereIn('etudiant_id', $request->student_ids)->delete();

            // Create new assignments
            foreach ($request->student_ids as $studentId) {
                ParentStudent::create([
                    'user_id' => $request->parent_id,
                    'etudiant_id' => $studentId
                ]);
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
            ParentStudent::where('user_id', $request->parent_id)
                ->where('etudiant_id', $request->student_id)
                ->delete();

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false], 500);
        }
    }
}
