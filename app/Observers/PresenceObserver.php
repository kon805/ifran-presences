<?php

namespace App\Observers;

use App\Models\Presence;
use App\Services\PresenceService;

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
    }

    /**
     * Handle the Presence "updated" event.
     */
    public function updated(Presence $presence): void
    {
        $this->updateDroppedStatus($presence);
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
}
