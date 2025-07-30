<?php

namespace App\Notifications;

use App\Models\Presence;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class SimpleAbsenceNotification extends Notification
{
    use Queueable;

    public function __construct(
        protected Presence $presence,
        protected $etudiant,
        protected $coursNom,
        protected $dateFormattee
    ) {
        $this->coursNom = $presence->cours->matiere->nom;
        $this->dateFormattee = $presence->cours->date_cours->format('d/m/Y H:i');
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        $baseMessage = "L'étudiant {$this->etudiant->nom} {$this->etudiant->prenom} était absent";
        $message = $baseMessage . " au cours de {$this->coursNom} le {$this->dateFormattee}";

        // Ajouter des détails supplémentaires pour les coordinateurs
        if ($notifiable->role === 'coordinateur') {
            $message .= " (Classe: {$this->etudiant->classe->nom})";
        }

        return [
            'type' => 'absence',
            'message' => $message,
            'presence_id' => $this->presence->id,
            'etudiant_id' => $this->etudiant->id,
            'cours_nom' => $this->coursNom,
            'date' => $this->dateFormattee,
            'classe_nom' => $this->etudiant->classe->nom ?? 'Non défini',
            'icon' => '🚫'
        ];
    }
}
