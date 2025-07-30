Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::resource('admin/annees-academiques', AnneeAcademiqueController::class)
         ->names('admin.annees-academiques');
    Route::post('admin/annees-academiques/{anneeAcademique}/terminer',
        [AnneeAcademiqueController::class, 'terminer'])
        ->name('admin.annees-academiques.terminer');
});
