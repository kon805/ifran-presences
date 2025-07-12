<?php

namespace App\Http\Controllers;

use App\Models\Presence;
use App\Models\Justification;
use App\Services\LoggingService;
use App\Notifications\StatusChangeNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class JustificationController extends Controller
{
    public function history(Request $request)
    {
        $query = Justification::with(['presence.etudiant', 'presence.cours.matiere', 'coordinateur']);

        // Filtre par date
        if ($request->filled('date_debut')) {
            $query->whereDate('created_at', '>=', $request->date_debut);
        }
        if ($request->filled('date_fin')) {
            $query->whereDate('created_at', '<=', $request->date_fin);
        }

        // Filtre par étudiant
        if ($request->filled('etudiant')) {
            $query->whereHas('presence.etudiant', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->etudiant . '%');
            });
        }

        // Filtre par matière
        if ($request->filled('matiere')) {
            $query->whereHas('presence.cours.matiere', function ($q) use ($request) {
                $q->where('nom', 'like', '%' . $request->matiere . '%');
            });
        }

        // Filtre par statut de justification
        if ($request->filled('statut')) {
            $query->where('justifiee', $request->statut === 'justifiee');
        }

        $justifications = $query->orderBy('created_at', 'desc')->paginate(15)
            ->withQueryString(); // Garde les paramètres de filtre dans la pagination

        return view('coordinateur.justifications.history', compact('justifications'));
    }
    public function index()
    {
        $absences = Presence::whereIn('statut', ['absent', 'retard'])
            ->whereDoesntHave('justification')
            ->whereHas('cours', function ($query) {
                $query->whereHas('classe', function ($q) {
                    $q->where('coordinateur_id', Auth::id());
                });
            })
            ->with(['etudiant', 'cours.classe', 'cours.matiere', 'justification'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('coordinateur.justifications.index', compact('absences'));
    }

    public function create($presence_id)
    {
        $presence = Presence::findOrFail($presence_id);

        if ($presence->cours->classe->coordinateur_id !== Auth::id()) {
            abort(403, "Vous n'êtes pas autorisé à justifier cette absence.");
        }

        return view('coordinateur.justifications.create', compact('presence'));
    }

    public function store(Request $request, $presence_id)
    {
        $request->validate([
            'motif' => 'required|string|max:1000',
            'justifiee' => 'boolean',
        ]);

        $presence = Presence::findOrFail($presence_id);

        if ($presence->cours->classe->coordinateur_id !== Auth::id()) {
            abort(403, "Vous n'êtes pas autorisé à justifier cette absence.");
        }        DB::transaction(function () use ($request, $presence_id) {
            $oldDroppedStatus = false;
            $presence = Presence::findOrFail($presence_id);

            $justification = Justification::create([
                'presence_id' => $presence_id,
                'coordinateur_id' => Auth::id(),
                'motif' => $request->motif,
                'justifiee' => $request->has('justifiee'),
            ]);

            // Si l'absence est justifiée, mettre à jour le statut de présence
            if ($request->has('justifiee')) {
                $presence->update([
                    'statut' => 'present'
                ]);

                // Recalculer le statut dropped pour cet étudiant dans cette matière
                $matiere = $presence->cours->matiere;
                $etudiant = $presence->etudiant;

                // Calculer le nouveau taux de présence
                $totalCours = $matiere->cours()
                    ->whereHas('classe.etudiants', function ($query) use ($etudiant) {
                        $query->where('users.id', $etudiant->id);
                    })
                    ->count();

                $presences = $matiere->cours()
                    ->whereHas('presences', function ($query) use ($etudiant) {
                        $query->where('etudiant_id', $etudiant->id)
                            ->where('statut', 'present');
                    })
                    ->count();

                if ($totalCours > 0) {
                    $tauxPresence = ($presences / $totalCours) * 100;

                    // Mettre à jour le statut dropped
                    DB::table('matiere_user')
                        ->where('user_id', $etudiant->id)
                        ->where('matiere_id', $matiere->id)
                        ->update([
                            'dropped' => $tauxPresence < 70,
                            'updated_at' => now()
                        ]);

                        // Logger le changement
                        LoggingService::logStatusChange(
                            $etudiant,
                            $matiere,
                            $oldDroppedStatus,
                            $tauxPresence < 70,
                            $tauxPresence
                        );

                        // Envoyer une notification si le statut a changé
                        if ($oldDroppedStatus !== ($tauxPresence < 70)) {
                            $etudiant->notify(new StatusChangeNotification(
                                $matiere,
                                $oldDroppedStatus,
                                $tauxPresence < 70,
                                $tauxPresence
                            ));
                        }
                }
            }
        });

        return redirect()->route('coordinateur.justifications.index')
            ->with('success', 'Absence justifiée avec succès.');
    }
}
