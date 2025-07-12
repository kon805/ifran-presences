<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class Classe extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom',
        'coordinateur_id',
        'annee_academique',
        'semestre',
        'statut',
    ];

    public function etudiants()
    {
        return $this->belongsToMany(User::class, 'classe_user', 'classe_id', 'user_id');
    }    public function syncEtudiants($etudiantIds, $forceSemestre2 = false)
    {
        // Empêcher l'ajout direct d'étudiants au semestre 2 sauf lors de la migration
        if ($this->semestre === '2' && !$forceSemestre2) {
            throw new \Exception('Les étudiants ne peuvent pas être ajoutés directement au semestre 2.');
        }

        // Détacher tous les anciens étudiants de cette classe
        $this->etudiants()->detach();

        if (!empty($etudiantIds)) {
            // Vérifier si les étudiants ne sont pas déjà dans une autre classe du même semestre
            $etudiantsDejaInscrits = DB::table('classe_user')
                ->join('classes', 'classes.id', '=', 'classe_user.classe_id')
                ->where('classes.semestre', $this->semestre)
                ->where('classes.annee_academique', $this->annee_academique)
                ->whereIn('classe_user.user_id', $etudiantIds)
                ->where('classes.id', '!=', $this->id)
                ->exists();

            if ($etudiantsDejaInscrits) {
                throw new \Exception('Certains étudiants sont déjà inscrits dans une autre classe pour ce semestre.');
            }

            // Attacher les nouveaux étudiants
            foreach ($etudiantIds as $etudiantId) {
                $this->etudiants()->attach($etudiantId);
            }
        }
    }

    public function coordinateur()
    {
        return $this->belongsTo(User::class, 'coordinateur_id');
    }

    /**
     * Trouve la classe correspondant à l'autre semestre de la même année académique
     */
    public function getAutreSemestreClasse()
    {
        $autreSemestre = $this->semestre === '1' ? '2' : '1';
        return self::where('annee_academique', $this->annee_academique)
            ->where('semestre', $autreSemestre)
            ->where('coordinateur_id', $this->coordinateur_id)
            ->first();
    }

    // La synchronisation automatique avec l'autre semestre a été supprimée
    // Les étudiants ne sont migrés vers le semestre 2 que lors de la fin du semestre 1

    /**
     * Termine le semestre et migre les étudiants vers le semestre suivant
     */

public function terminerSemestre($id)
{
    try {
        $user = Auth::user();
        $classe = Classe::findOrFail($id);

        // Vérifications
        if (!in_array($user->role, ['admin', 'coordinateur'])) {
            throw new \Exception('Accès non autorisé.');
        }

        if ($user->role === 'coordinateur' && $classe->coordinateur_id !== $user->id) {
            throw new \Exception('Vous n\'êtes pas le coordinateur de cette classe.');
        }

        if ($classe->semestre !== '1') {
            throw new \Exception('Seul le semestre 1 peut être terminé.');
        }

        if ($classe->statut === 'termine') {
            throw new \Exception('Cette classe est déjà terminée.');
        }

        // Trouver la classe du semestre 2
        $classeSemestre2 = Classe::where([
            'annee_academique' => $classe->annee_academique,
            'semestre' => '2',
            'coordinateur_id' => $classe->coordinateur_id
        ])->first();

        if (!$classeSemestre2) {
            throw new \Exception('La classe du semestre 2 n\'existe pas.');
        }

        DB::beginTransaction();

        // Récupérer les étudiants actuels
        $etudiantIds = $classe->etudiants()->pluck('users.id')->toArray();

        // Migrer les étudiants vers le semestre 2 (forcer l'ajout même au semestre 2)
        $classeSemestre2->syncEtudiants($etudiantIds, true);

        // Marquer le semestre comme terminé
        $classe->statut = 'termine';
        $classe->save();

        DB::commit();

        return redirect()->back()
            ->with('success', 'Semestre terminé. ' . count($etudiantIds) . ' étudiants migrés vers le semestre 2.');

    } catch (\Exception $e) {
        DB::rollBack();
        Log::error($e);
        return redirect()->back()->withErrors(['error' => $e->getMessage()]);
    }



}
}
