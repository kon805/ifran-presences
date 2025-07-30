<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\Presence;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;

class AbsenceNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $presence;
    protected $etudiant;

    public function __construct(Presence $presence, User $etudiant)
    {
        $this->presence = $presence;
        $this->etudiant = $etudiant;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    /**
     * Determine if the notification should be sent.
     *
     * @param  mixed  $notifiable
     * @return bool
     */
    public function shouldSend($notifiable)
    {
        // Get count of notifications sent today
        $count = DB::table('notifications')
            ->where('notifiable_id', $notifiable->id)
            ->whereDate('created_at', today())
            ->count();

        // Limit to 100 notifications per day per user
        return $count < 100;
    }

    /**
     * Handle a notification failure.
     *
     * @param  mixed  $notifiable
     * @param  \Exception  $e
     * @return void
     */
    public function failed($notifiable, \Exception $e)
    {
        Log::error('Échec de l\'envoi de la notification d\'absence', [
            'notifiable_id' => $notifiable->id,
            'error' => $e->getMessage()
        ]);

        // Store the notification in database even if email fails
        $notifiable->notifications()->create([
            'id' => \Illuminate\Support\Str::uuid(),
            'type' => get_class($this),
            'data' => $this->toArray($notifiable),
            'read_at' => null
        ]);
    }

    public function toMail($notifiable)
    {
        $cours = $this->presence->cours;
        $matiere = $cours->matiere;
        $date = $cours->date_cours ? $cours->date_cours->format('d/m/Y') : 'Date non spécifiée';
        $heure = $cours->heure_debut ?? 'Heure non spécifiée';

        return (new MailMessage)
            ->subject('Notification d\'absence - ' . $this->etudiant->name)
            ->greeting('Bonjour ' . $notifiable->name)
            ->line('Nous vous informons que ' . $this->etudiant->name . ' était absent(e) au cours suivant :')
            ->line('Matière : ' . $matiere->nom)
            ->line('Date : ' . $date)
            ->line('Heure : ' . $heure)
            ->action('Voir les détails', route('parent.presences.etudiant', $this->etudiant->id))
            ->line('Si vous souhaitez justifier cette absence, veuillez contacter l\'administration.')
            ->salutation('Cordialement,');
    }

    public function toArray($notifiable)
    {
        return [
            'message' => 'Absence enregistrée pour ' . $this->etudiant->name,
            'etudiant_id' => $this->etudiant->id,
            'cours_id' => $this->presence->cours_id,
        ];
    }
}
