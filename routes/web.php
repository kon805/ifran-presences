<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClasseController;
use App\Http\Controllers\CoursController;
use App\Http\Controllers\PresenceController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\PresenceConsultationController;
use App\Http\Controllers\EtudiantPresenceController;
use App\Http\Controllers\EtudiantEmploiTempsController;
use App\Http\Controllers\EtudiantMatiereController;
use App\Http\Controllers\JustificationController;





Route::middleware(['auth:sanctum', 'verified'])->get('/redirect-by-role', function (\Illuminate\Http\Request $request) {
        $role = $request->user()->role;
        return match ($role) {
            'admin' => redirect('/admin/dashboard'),
            'coordinateur' => redirect('/coordinateur'),
            'professeur' => redirect('/professeur'),
            'etudiant' => redirect('/etudiant'),
            default => abort(403),
        };
    });

    Route::middleware('role:admin')->prefix('admin')->group(function () {
        Route::get('/dashboard', fn () => view('admin.dashboard'))->name('admin.dashboard');
        Route::get('/users', [AdminController::class, 'index'])->name('admin.users.index');
        Route::get('/users/create', [AdminController::class, 'create'])->name('admin.users.create');
        Route::post('/users', [AdminController::class, 'store'])->name('admin.users.store');
        Route::get('/users/{id}/edit', [AdminController::class, 'edit'])->name('admin.users.edit');
        Route::put('/users/{id}', [AdminController::class, 'update'])->name('admin.users.update');
        Route::delete('/users/{id}', [AdminController::class, 'destroy'])->name('admin.users.destroy');

          // Gestion des matières
        Route::get('/matieres', [\App\Http\Controllers\MatiereController::class, 'index'])->name('admin.matieres.index');
        Route::get('/matieres/create', [\App\Http\Controllers\MatiereController::class, 'create'])->name('admin.matieres.create');
        Route::post('/matieres', [\App\Http\Controllers\MatiereController::class, 'store'])->name('admin.matieres.store');
        Route::get('/matieres/{id}/edit', [\App\Http\Controllers\MatiereController::class, 'edit'])->name('admin.matieres.edit');
        Route::put('/matieres/{id}', [\App\Http\Controllers\MatiereController::class, 'update'])->name('admin.matieres.update');
        Route::delete('/matieres/{id}', [\App\Http\Controllers\MatiereController::class, 'destroy'])->name('admin.matieres.destroy');


          Route::get('/classes', [ClasseController::class, 'index'])->name('admin.classes.index');
          Route::get('/classes/create', [ClasseController::class, 'create'])->name('admin.classes.create');
          Route::delete('/classes/{id}', [ClasseController::class, 'destroy'])->name('admin.classes.delete');
          Route::post('/classes', [ClasseController::class, 'store'])->name('admin.classes.store');
          Route::post('/classes/{id}/terminer-semestre', [ClasseController::class, 'terminerSemestre'])->name('admin.classes.terminer-semestre');


    });

    Route::middleware('role:coordinateur')->prefix('coordinateur')->group(function () {
        Route::get('/', fn () => view('coordinateur.dashboard'))->name('coordinateur.dashboard');
        Route::get('/classes', [ClasseController::class, 'index'])->name('coordinateur.classes.index');

        Route::get('/classes/{id}', [ClasseController::class, 'show'])->name('coordinateur.classes.show');
        Route::get('/classes/{id}/edit', [ClasseController::class, 'edit'])->name('coordinateur.classes.edit');
        Route::put('/classes/{id}', [ClasseController::class, 'update'])->name('coordinateur.classes.update');
        Route::post('/classes/{id}/terminer-semestre', [ClasseController::class, 'terminerSemestre'])->name('coordinateur.classes.terminer-semestre');

        // Routes pour les justifications
        Route::get('/justifications', [\App\Http\Controllers\JustificationController::class, 'index'])
            ->name('coordinateur.justifications.index');
        Route::get('/justifications/{presence}/create', [\App\Http\Controllers\JustificationController::class, 'create'])
            ->name('coordinateur.justifications.create');
        Route::post('/justifications/{presence}', [\App\Http\Controllers\JustificationController::class, 'store'])
            ->name('coordinateur.justifications.store');

        Route::get('/justifications/history', [\App\Http\Controllers\JustificationController::class, 'history'])
            ->name('coordinateur.justifications.history');

        Route::get('/presences', [PresenceConsultationController::class, 'index'])->name('coordinateur.presences.index');
        Route::get('/presences/{cours}', [PresenceConsultationController::class, 'show'])->name('coordinateur.presences.show');
        Route::get('/presences/{cours}/edit', [PresenceConsultationController::class, 'edit'])->name('coordinateur.presences.edit');
        Route::put('/presences/{cours}', [PresenceConsultationController::class, 'update'])->name('coordinateur.presences.update');

        Route::resource('emploi-du-temps', CoursController::class);
    });

    Route::middleware('role:professeur')->prefix('professeur')->group(function () {
        Route::get('/', fn () => view('professeur.dashboard'))->name('professeur.dashboard');
        Route::get('/mes-cours', [CoursController::class, 'index'])->name('presences.index');
        Route::get('/presences/{cours}', [PresenceController::class, 'edit'])->name('presences.edit');
        Route::post('/presences/{cours}', [PresenceController::class, 'store'])->name('presences.store');
         // Route pour le nettoyage de la base de données
          Route::get('/cleanup/cours', [\App\Http\Controllers\DatabaseCleanupController::class, 'cleanupCours'])->name('professeur.cleanup.cours');
    });

    Route::middleware('role:etudiant')->prefix('etudiant')->group(function () {
        Route::get('/', fn () => view('etudiant.dashboard'))->name('etudiant.dashboard');

        // Routes pour les absences/présences
        Route::get('/presences', [EtudiantPresenceController::class, 'index'])->name('etudiant.presences.index');
        Route::get('/presences/{presence}', [EtudiantPresenceController::class, 'show'])->name('etudiant.presences.show');

        // Routes pour l'emploi du temps
        Route::get('/emploi-du-temps', [EtudiantEmploiTempsController::class, 'index'])->name('etudiant.emploi-du-temps.index');

        // Routes pour les matières
        Route::get('/matieres', [EtudiantMatiereController::class, 'index'])->name('etudiant.matieres.index');
        Route::get('/matieres/{matiere}', [EtudiantMatiereController::class, 'show'])->name('etudiant.matieres.show');
    });


Route::get('/', function () {
    return view('welcome');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
    'role:admin',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});
