<?php

namespace App\Http\Controllers;

use App\Models\Presence;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EtudiantPresenceController extends Controller
{
    public function index()
    {
        $etudiant = Auth::user();
        $presences = Presence::where('etudiant_id', $etudiant->id)
            ->with(['cours.matiere', 'cours.classe', 'justification'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('etudiant.presences.index', compact('presences'));
    }

    public function show($presence)
    {
        $presence = Presence::with(['cours.matiere', 'cours.classe', 'justification'])
            ->where('etudiant_id', Auth::id())
            ->findOrFail($presence);

        return view('etudiant.presences.show', compact('presence'));
    }
}
