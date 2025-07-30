<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Presence;
use App\Notifications\AbsenceNotification;

class TestParentEmail extends Command
{
    protected $signature = 'email:test-parent {email}';
    protected $description = 'Envoie un email de test à un parent';

    public function handle()
    {
        $email = $this->argument('email');

        // Trouver le parent par son email
        $parent = User::where('email', $email)->first();

        if (!$parent) {
            $this->error("Aucun parent trouvé avec l'email : $email");
            return 1;
        }

        // Créer une présence de test
        $presence = new Presence([
            'statut' => 'absent',
            'date' => now(),
        ]);

        // Créer une notification de test
        $notification = new AbsenceNotification($presence, $parent);

        try {
            // Envoyer la notification
            $parent->notify($notification);
            $this->info("Email de test envoyé à : $email");

            return 0;
        } catch (\Exception $e) {
            $this->error("Erreur lors de l'envoi : " . $e->getMessage());
            return 1;
        }
    }
}
