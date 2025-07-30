<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class MonitorQueue extends Command
{
    protected $signature = 'queue:monitor';
    protected $description = 'Monitore l\'état de la queue des emails';

    public function handle()
    {
        while (true) {
            $stats = [
                'en_attente' => DB::table('jobs')->count(),
                'echoues' => DB::table('failed_jobs')->count(),
            ];

            $this->info('État de la queue :');
            $this->table(
                ['Status', 'Nombre'],
                [
                    ['En attente', $stats['en_attente']],
                    ['Échoués', $stats['echoues']]
                ]
            );

            if ($stats['echoues'] > 0) {
                $this->warn("\nDétails des jobs échoués :");
                $failedJobs = DB::table('failed_jobs')
                    ->select(['id', 'failed_at', 'exception'])
                    ->get();

                foreach ($failedJobs as $job) {
                    $this->error("ID: {$job->id} - Échoué le: {$job->failed_at}");
                    $this->line("Erreur: " . substr($job->exception, 0, 200) . "...\n");
                }
            }

            sleep(10); // Actualiser toutes les 10 secondes
            $this->line("\033[H\033[2J"); // Effacer l'écran
        }
    }
}
