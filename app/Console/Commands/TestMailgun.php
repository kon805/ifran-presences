<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class TestMailgun extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mail:test {email}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test l\'envoi d\'emails avec Mailgun';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $email = $this->argument('email');

        try {
            Mail::raw('Test email from Laravel application using Mailgun', function ($message) use ($email) {
                $message->to($email)
                    ->subject('Test Email');
            });

            $this->info('Email test envoyé avec succès !');
        } catch (\Exception $e) {
            $this->error('Erreur lors de l\'envoi du mail : ' . $e->getMessage());
        }
    }
}
