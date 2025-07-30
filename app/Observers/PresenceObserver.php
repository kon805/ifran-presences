<?php

namespace App\Observers;

use App\Models\Presence;
use App\Services\PresenceService;
use App\Notifications\SimpleAbsenceNotification;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;

class PresenceObserver
{
    protected $presenceService;

    public function __construct(PresenceService $presenceService)
    {
        $this->presenceService = $presenceService;
    }

    /**
     * Handle the Presence "created" event.
     */
    public function created(Presence $presence): void
    {
        $this->updateDroppedStatus($presence);
        if ($presence->statut === 'absent') {
            $this->notifyParents($presence);
            Log::info('Notification d\'absence envoyée lors de la création', [
                'presence_id' => $presence->id,
                'etudiant_id' => $presence->etudiant_id,
                'statut' => $presence->statut
            ]);
        }
    }

    /**
     * Handle the Presence "updated" event.
     */
    public function updated(Presence $presence): void
    {
        $this->updateDroppedStatus($presence);

        // Si le statut a été changé à 'absent'
        if ($presence->isDirty('statut') && $presence->statut === 'absent') {
            $this->notifyParents($presence);
            Log::info('Notification d\'absence envoyée lors de la mise à jour', [
                'presence_id' => $presence->id,
                'etudiant_id' => $presence->etudiant_id,
                'statut' => $presence->statut
            ]);
        }
    }

    /**
     * Handle the Presence "deleted" event.
     */
    public function deleted(Presence $presence): void
    {
        $this->updateDroppedStatus($presence);
    }

    /**
     * Mettre à jour le statut dropped pour l'étudiant dans la matière
     */
    private function updateDroppedStatus(Presence $presence): void
    {
        $cours = $presence->cours;
        if ($cours && $cours->matiere) {
            $this->presenceService->updateEtudiantDroppedStatus($presence->etudiant, $cours->matiere);
        }
    }



private function notifyParents(Presence $presence): void
{
    try {
        $etudiant = $presence->etudiant;
        if (!$etudiant) {
            Log::warning('Étudiant non trouvé pour la présence', ['presence_id' => $presence->id]);
            return;
        }

        // Créer la notification avec les données nécessaires
        $notification = new SimpleAbsenceNotification(
            $presence,
            $etudiant,
            $presence->cours->matiere->nom,
            $presence->cours->date_cours
        );

        // Envoyer la notification à l'étudiant s'il a un compte utilisateur
        if ($etudiant->user) {
            $etudiant->user->notify($notification);
            Log::info('Notification envoyée à l\'étudiant', ['etudiant_id' => $etudiant->id]);
        }

        // Envoyer la notification aux coordinateurs
        User::where('role', 'coordinateur')->chunk(100, function ($coordinateurs) use ($notification) {
            foreach ($coordinateurs as $coordinateur) {
                $coordinateur->notify($notification);
                Log::info('Notification envoyée au coordinateur', ['coordinateur_id' => $coordinateur->id]);
            }
        });

        // Envoyer aux parents
        $parents = $etudiant->parents;
        foreach ($parents as $parent) {
            if ($parent->user) {
                $parent->user->notify($notification);
                Log::info('Notification envoyée au parent', [
                    'parent_id' => $parent->id,
                    'etudiant_id' => $etudiant->id
                ]);
            }
        }
    } catch (\Exception $e) {
        Log::error('Erreur lors de l\'envoi des notifications', [
            'error' => $e->getMessage(),
            'presence_id' => $presence->id
        ]);
    }
}
}
