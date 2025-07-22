<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use Illuminate\Pagination\Paginator;

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
        // Utiliser Tailwind CSS pour la pagination
        Paginator::useBootstrapFive();
        Route::middleware('web')
        ->get('/redirect-by-role', function () {
            $user = \Illuminate\Support\Facades\Auth::user();
            return match ($user->role) {
                'admin' => redirect('/admin/dashboard'),
                'coordinateur' => redirect('/coordinateur'),
                'professeur' => redirect('/professeur'),
                'etudiant' => redirect('/etudiant'),
                'parent' => redirect('/parent'),
                default => abort(403),
            };
        });
    }
}
