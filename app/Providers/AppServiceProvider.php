<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;

class AppServiceProvider extends ServiceProvider
{


    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }
        public const HOME = '/redirect-by-role';

    public function boot(): void
    {
        Route::middleware('web')
        ->get('/redirect-by-role', function () {
            $user = \Illuminate\Support\Facades\Auth::user();
            return match ($user->role) {
                'admin' => redirect('/admin/dashboard'),
                'coordinateur' => redirect('/coordinateur'),
                'professeur' => redirect('/professeur'),
                'etudiant' => redirect('/etudiant'),
                default => abort(403),
            };
        });
    }
}
