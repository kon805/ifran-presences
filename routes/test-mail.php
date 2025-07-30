<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Mail;

Route::get('/test-mail', function () {
    try {
        Mail::raw('Test email from Laravel app', function ($message) {
            $message->to('kdaou048@gmail.com')
                   ->subject('Test Email');
        });

        return 'Email de test envoyé avec succès !';
    } catch (\Exception $e) {
        return 'Erreur lors de l\'envoi : ' . $e->getMessage();
    }
});
