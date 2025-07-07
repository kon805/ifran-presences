
<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClasseController;
use App\Http\Controllers\CoursController;
use App\Http\Controllers\PresenceController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\PresenceConsultationController;





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
        Route::get('/users/create', [AdminController::class, 'create'])->name('admin.users.create');
        Route::post('/users', [AdminController::class, 'store'])->name('admin.users.store');
        Route::post('/users', [\App\Http\Controllers\AdminController::class, 'store'])->name('admin.users.store');
    });

    Route::middleware('role:coordinateur')->prefix('coordinateur')->group(function () {
        Route::get('/', fn () => view('coordinateur.dashboard'))->name('coordinateur.dashboard');
        Route::get('/classes', [ClasseController::class, 'index'])->name('coordinateur.classes.index');
        Route::get('/classes/create', [ClasseController::class, 'create'])->name('coordinateur.classes.create');
        Route::post('/classes', [ClasseController::class, 'store'])->name('coordinateur.classes.store');
        Route::get('/classes/{id}', [ClasseController::class, 'show'])->name('coordinateur.classes.show');
        Route::get('/classes/{id}/edit', [ClasseController::class, 'edit'])->name('coordinateur.classes.edit');
        Route::put('/classes/{id}', [ClasseController::class, 'update'])->name('coordinateur.classes.update');
        Route::delete('/classes/{id}', [ClasseController::class, 'destroy'])->name('coordinateur.classes.delete');

          Route::get('/presences', [PresenceConsultationController::class, 'index'])->name('coordinateur.presences.index');
          Route::get('/presences/{cours}', [PresenceConsultationController::class, 'show'])->name('coordinateur.presences.show');
          Route::get('/presences/{cours}/edit', [PresenceConsultationController::class, 'edit'])->name('coordinateur.presences.edit');
          Route::put('/presences/{cours}', [PresenceConsultationController::class, 'update'])->name('coordinateur.presences.update');

        Route::resource('emploi-du-temps', CoursController::class);

    });

    Route::middleware('role:professeur')->prefix('professeur')->group(function () {
        Route::get('/', fn () => view('professeur.dashboard'))->name('professeur.dashboard');
          Route::get('/mes-cours', [App\Http\Controllers\PresenceController::class, 'index'])->name('presences.index');
          Route::get('/presences/{cours}', [App\Http\Controllers\PresenceController::class, 'edit'])->name('presences.edit');
          Route::post('/presences/{cours}', [App\Http\Controllers\PresenceController::class, 'store'])->name('presences.store');
    });

    Route::middleware('role:etudiant')->prefix('etudiant')->group(function () {
        Route::get('/', fn () => view('etudiant.dashboard'))->name('etudiant.dashboard');
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
