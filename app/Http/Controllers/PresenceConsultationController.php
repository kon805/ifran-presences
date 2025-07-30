<?php

namespace App\Http\Controllers;

use App\Traits\NotificationTrait;
use App\Models\Cours;
use Illuminate\Http\Request;
use App\Models\Presence;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Notifications\StatusChangeNotification;

class PresenceConsultationController extends Controller
{
    use NotificationTrait;
    public function index()
    {
        $cours = Cours::with(['classe', 'professeur', 'types', 'matiere'])
            ->orderBy('date', 'desc')
            ->paginate(15); // 15 cours par page

        return view('coordinateur.presences.index', compact('cours'));
    }

    public function show(Cours $cours)
    {
        // S'assurer que le cours existe et charger ses relations
        $cours->load(['classe.etudiants', 'professeur', 'presences', 'matiere']);

        if (!$cours->classe) {
            return redirect()->back()->withErrors(['error' => 'Aucune classe n\'est associée à ce cours.']);
        }

        return view('coordinateur.presences.show', compact('cours'));
    }

    public function edit(Cours $cours)
    {
        $cours->load(['classe.etudiants', 'presences']);

        // Vérifier si le cours est de type Workshop ou E-learning
        $typesAutorises = $cours->types->pluck('code')->toArray();
        if (!in_array('workshop', $typesAutorises) && !in_array('e-learning', $typesAutorises)) {
            abort(403, 'Seuls les cours Workshop ou E-learning sont modifiables par le coordinateur.');
        }

        return view('coordinateur.presences.edit', compact('cours'));
    }

    public function update(Request $request, Cours $cours)
    {
        $request->validate([
            'presences' => 'required|array'
        ]);

        Log::info('Début de la mise à jour des présences', [
            'cours_id' => $cours->id,
            'matiere' => $cours->matiere->nom,
            'nb_presences' => count($request->presences)
        ]);

        DB::transaction(function () use ($request, $cours) {
            // Mettre à jour les présences
            foreach ($request->presences as $etudiant_id => $statut) {
                Presence::updateOrCreate(
                    [
                        'cours_id' => $cours->id,
                        'etudiant_id' => $etudiant_id
                    ],
                    [
                        'statut' => $statut
                    ]
                );
            }

            // Calculer et mettre à jour le statut dropped pour chaque étudiant
            $matiere = $cours->matiere;
            $etudiants = $cours->classe->etudiants;

            foreach ($etudiants as $etudiant) {
                // Calculer le taux de présence pour cet étudiant dans cette matière
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

                // Calculer le pourcentage de présence
                if ($totalCours > 0) {
                    $tauxPresence = ($presences / $totalCours) * 100;

                    // Récupérer l'ancien statut
                    $oldStatus = DB::table('matiere_user')
                        ->where('user_id', $etudiant->id)
                        ->where('matiere_id', $matiere->id)
                        ->value('dropped');

                    $newStatus = $tauxPresence >= 70; // true si non dropped, false si dropped

                    // Mettre à jour le statut dropped si le taux est inférieur à 70%
                    DB::table('matiere_user')
                        ->where('user_id', $etudiant->id)
                        ->where('matiere_id', $matiere->id)
                        ->update(['dropped' => !$newStatus]);

                    // Si le statut a changé, envoyer une notification à l'étudiant et ses parents
                    if ($oldStatus !== !$newStatus) {
                        $notification = new StatusChangeNotification(
                            $matiere,
                            $oldStatus,
                            $newStatus,
                            $tauxPresence
                        );

                        try {
                            // Notifier l'étudiant s'il existe
                            if ($etudiant) {
                                Log::info('Tentative de notification à l\'étudiant', [
                                    'etudiant_id' => $etudiant->id,
                                    'matiere' => $matiere->nom,
                                    'role' => $etudiant->role
                                ]);
                                $etudiant->notify($notification);
                                Log::info("Notification envoyée avec succès à l'étudiant", [
                                    'etudiant_id' => $etudiant->id
                                ]);
                            }

                            // Charger et notifier les parents
                            $parents = $etudiant->parents()->get();
                            foreach ($parents as $parent) {
                                if ($parent) {
                                    Log::info('Tentative de notification au parent', [
                                        'parent_id' => $parent->id,
                                        'role' => $parent->role
                                    ]);
                                    $parent->notify($notification);
                                    Log::info("Notification envoyée avec succès au parent", [
                                        'parent_id' => $parent->id
                                    ]);
                                }
                            }

                            // Notifier les coordinateurs
                            $coordinateurs = User::where('role', 'coordinateur')->get();
                            Log::info('Recherche des coordinateurs', [
                                'nombre_trouve' => $coordinateurs->count()
                            ]);

                            foreach ($coordinateurs as $coordinateur) {
                                Log::info('Tentative de notification au coordinateur', [
                                    'coordinateur_id' => $coordinateur->id,
                                    'role' => $coordinateur->role
                                ]);
                                $coordinateur->notify($notification);
                                Log::info("Notification envoyée avec succès au coordinateur", [
                                    'coordinateur_id' => $coordinateur->id
                                ]);
                            }
                        } catch (\Exception $e) {
                            Log::error("Erreur lors de l'envoi des notifications", [
                                'message' => $e->getMessage(),
                                'trace' => $e->getTraceAsString()
                            ]);
                        }
                    }
                }
            }
        });

        return redirect()->route('coordinateur.presences.index')->with('success', 'Présences mises à jour et statuts dropped recalculés.');
    }
}
