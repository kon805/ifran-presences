<?php

namespace App\Http\Controllers;

use App\Models\Classe;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SemestreController extends Controller
{
    public function terminerSemestre(Request $request, Classe $classe)
    {
        $request->validate([
            'semestre' => 'required|in:1,2'
        ]);

        DB::transaction(function () use ($classe) {
            // Marquer le semestre comme terminé
            $classe->update([
                'semestre_termine' => true,
                'semestre_actuel' => $classe->semestre_actuel + 1
            ]);

            // Si c'était le semestre 1, créer la classe pour le semestre 2
            if ($classe->semestre_actuel === 1) {
                $nouvelleCLasse = $classe->replicate();
                $nouvelleCLasse->semestre_actuel = 2;
                $nouvelleCLasse->semestre_termine = false;
                $nouvelleCLasse->save();

                // Copier les étudiants qui n'ont pas été "dropped"
                $etudiantsNonDroppes = $classe->etudiants()
                    ->whereNotExists(function ($query) {
                        $query->select(DB::raw(1))
                            ->from('etudiant_matiere')
                            ->whereRaw('etudiant_matiere.etudiant_id = classe_user.user_id')
                            ->where('dropped', true);
                    })
                    ->pluck('users.id');

                $nouvelleCLasse->etudiants()->attach($etudiantsNonDroppes);
            }
        });

        return redirect()->back()->with('success', 'Le semestre a été terminé avec succès.');
    }
}
