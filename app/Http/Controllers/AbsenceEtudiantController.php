<?php

namespace App\Http\Controllers;

use App\Models\Presence;
use App\Models\Justification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AbsenceEtudiantController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        if ($user->role === 'parent') {
            $parent = \App\Models\Parents::where('user_id', $user->id)->first();
            $etudiantId = $parent ? $parent->etudiant_id : null;
        } else {
            $etudiantId = $user->id;
        }

        $absences = Presence::with(['cours.matiere', 'justification'])
            ->where('etudiant_id', $etudiantId)
            ->whereIn('statut', ['absent', 'retard'])
            ->orderByDesc('date')
            ->get();

        return view('etudiant.absences.index', compact('absences'));
    }
}
