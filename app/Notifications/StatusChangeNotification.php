<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class StatusChangeNotification extends Notification
{
    use Queueable;

    protected $matiere;
    protected $oldStatus;
    protected $newStatus;
    protected $tauxPresence;

    public function __construct($matiere, $oldStatus, $newStatus, $tauxPresence)
    {
        $this->matiere = $matiere;
        $this->oldStatus = $oldStatus;
        $this->newStatus = $newStatus;
        $this->tauxPresence = $tauxPresence;
    }

    public function via($notifiable)
    {
        return ['database', 'mail'];
    }

    public function toMail($notifiable)
    {
        $status = $this->newStatus ? 'autorisé' : 'droppé';
        $message = "Votre statut dans la matière {$this->matiere->nom} a changé.";
        $message .= "\nTaux de présence actuel : " . number_format($this->tauxPresence, 2) . "%";
        $message .= "\nNouveau statut : " . $status;

        return (new MailMessage)
            ->subject("Changement de statut - {$this->matiere->nom}")
            ->line($message)
            ->action('Voir mes matières', route('etudiant.matieres.index'))
            ->line("Si vous avez des questions, contactez votre coordinateur.");
    }

    public function toArray($notifiable)
    {
        return [
            'matiere_id' => $this->matiere->id,
            'matiere_nom' => $this->matiere->nom,
            'old_status' => $this->oldStatus,
            'new_status' => $this->newStatus,
            'taux_presence' => $this->tauxPresence,
        ];
    }
}
