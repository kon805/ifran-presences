<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class StatusChangeNotification extends Notification implements ShouldQueue
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
        return ['database'];
    }

    public function toArray($notifiable)
    {
        $status = $this->newStatus ? 'autorisé' : 'droppé';
        $role = $notifiable->role;

        $message = match($role) {
            'coordinateur' => $status === 'droppé' ?
                "⚠️ Un étudiant a été droppé de la matière {$this->matiere->nom}" :
                "✅ Un étudiant a été réintégré dans la matière {$this->matiere->nom}",

            'parent' => $status === 'droppé' ?
                "⚠️ Votre enfant a été droppé de la matière {$this->matiere->nom} (Taux de présence insuffisant)" :
                "✅ Votre enfant est à nouveau autorisé à suivre la matière {$this->matiere->nom}",

            'etudiant' => $status === 'droppé' ?
                "⚠️ Vous avez été droppé de la matière {$this->matiere->nom} (Taux de présence : {$this->tauxPresence}%)" :
                "✅ Vous êtes à nouveau autorisé à suivre la matière {$this->matiere->nom} (Taux de présence : {$this->tauxPresence}%)",

            default => "Changement de statut dans la matière {$this->matiere->nom}"
        };

        return [
            'matiere_id' => $this->matiere->id,
            'matiere_nom' => $this->matiere->nom,
            'old_status' => $this->oldStatus,
            'new_status' => $this->newStatus,
            'taux_presence' => $this->tauxPresence,
            'message' => $message,
            'created_at' => now()->toIso8601String(),
            'icon' => $this->newStatus ? '✅' : '⚠️',
            'type' => 'status_change'
        ];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Changement de statut dans une matière')
            ->line($this->toArray($notifiable)['message'])
            ->line('Taux de présence actuel : ' . number_format($this->tauxPresence, 2) . '%');
    }
}
