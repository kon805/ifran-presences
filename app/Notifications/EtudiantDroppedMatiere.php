<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\User;
use App\Models\Matiere;

class EtudiantDroppedMatiere extends Notification implements ShouldQueue
{
    use Queueable;

    public $etudiant;
    public $matiere;

    public function __construct(User $etudiant, Matiere $matiere)
    {
        $this->etudiant = $etudiant;
        $this->matiere = $matiere;
    }

    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Étudiant droppé de la matière')
            ->line('L’étudiant ' . $this->etudiant->name . ' a été droppé de la matière ' . $this->matiere->nom . ' (taux de présence < 70%).')
            ->line('Il ne pourra plus suivre ni être évalué dans ce module cette année.');
    }

    public function toArray($notifiable)
    {
        return [
            'etudiant_id' => $this->etudiant->id,
            'etudiant_nom' => $this->etudiant->name,
            'matiere_id' => $this->matiere->id,
            'matiere_nom' => $this->matiere->nom,
            'message' => 'Étudiant droppé de la matière (présence < 70%)',
        ];
    }
}
