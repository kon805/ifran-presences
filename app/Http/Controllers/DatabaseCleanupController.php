<?php

namespace App\Http\Controllers;

use App\Models\Cours;
use Illuminate\Support\Facades\DB;

class DatabaseCleanupController extends Controller
{
    public function cleanupCours()
    {
        try {
            DB::beginTransaction();

            // Supprime les cours sans classe ou sans matière
            $coursToDelete = Cours::whereDoesntHave('classe')
                ->orWhereDoesntHave('matiere')
                ->get();

            $count = $coursToDelete->count();

            foreach ($coursToDelete as $cours) {
                // Supprime d'abord les présences associées
                $cours->presences()->delete();
                // Supprime le cours
                $cours->delete();
            }

            DB::commit();

            return redirect()->back()->with('success', $count . ' cours sans classe ou matière ont été supprimés.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors(['error' => 'Erreur lors du nettoyage : ' . $e->getMessage()]);
        }
    }
}
