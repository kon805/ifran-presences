<?php

namespace App\Http\Controllers;

use App\Models\Classe;
use App\Models\User;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;


class ClasseController extends Controller
{
    // Affiche la liste des classes (admin : toutes, coordinateur : ses classes)
    public function index()
    {
        $user = \Illuminate\Support\Facades\Auth::user();
        if ($user->role === 'admin') {
            $classes = \App\Models\Classe::with(['etudiants', 'coordinateur'])->get();
        } else if ($user->role === 'coordinateur') {
            $classes = \App\Models\Classe::with(['etudiants', 'coordinateur'])
                ->where('coordinateur_id', $user->id)
                ->get();
        } else {
            abort(403);
        }
        return view('admin.list-classes', compact('classes'));
    }

    // Affiche les détails d'une classe
    public function show($id)
    {
        $classe = \App\Models\Classe::with('etudiants')->findOrFail($id);
        return view('coordinateur.show-classe', compact('classe'));
    }

    // Affiche le formulaire d'édition d'une classe (admin : tout, coordinateur : que ses classes, et ne peut modifier que les étudiants)
    public function edit($id)
    {
        $user = \Illuminate\Support\Facades\Auth::user();
        $classe = \App\Models\Classe::with('etudiants')->findOrFail($id);
        if ($user->role === 'coordinateur' && $classe->coordinateur_id !== $user->id) {
            abort(403);
        }
        $etudiants = \App\Models\User::where('role', 'etudiant')->get();
        return view('coordinateur.edit-classe', compact('classe', 'etudiants'));
    }

    // Met à jour la classe (admin : tout, coordinateur : que les étudiants)
    public function update(Request $request, $id)
    {
        $user = \Illuminate\Support\Facades\Auth::user();
        $classe = \App\Models\Classe::findOrFail($id);
        if ($user->role === 'coordinateur' && $classe->coordinateur_id !== $user->id) {
            abort(403);
        }
        $rules = [
            'etudiants' => 'required|array',
            'etudiants.*' => 'exists:users,id',
        ];
        if ($user->role === 'admin') {
            $rules['nom'] = 'required|string|max:255';
        }
        $validated = $request->validate($rules);
        if ($user->role === 'admin') {
            $classe->update(['nom' => $validated['nom']]);
        }            // Gérer la synchronisation des étudiants avec la classe et son semestre jumeau
            try {
                DB::beginTransaction();

                if (!empty($validated['etudiants'])) {
                    // Détacher les étudiants de leurs classes actuelles
                    DB::table('classe_user')
                        ->whereIn('user_id', $validated['etudiants'])
                        ->delete();

                    // Récupérer toutes les classes où ces étudiants étaient inscrits
                    $anciennesClasses = Classe::whereHas('etudiants', function($query) use ($validated) {
                        $query->whereIn('users.id', $validated['etudiants']);
                    })->get();

                    // Pour chaque ancienne classe, détacher aussi de l'autre semestre
                    foreach ($anciennesClasses as $ancienneClasse) {
                        $autreSemestre = $ancienneClasse->getAutreSemestreClasse();
                        if ($autreSemestre) {
                            $autreSemestre->etudiants()->detach($validated['etudiants']);
                        }
                    }

                    // Attacher les étudiants à la classe actuelle
                    $classe->etudiants()->attach($validated['etudiants']);

                    // Synchroniser avec l'autre semestre de la même année
                    $classe->syncEtudiantsWithAutreSemestre($validated['etudiants']);
                } else {
                    // Si la liste est vide, détacher tous les étudiants
                    $classe->etudiants()->detach();
                    $autreSemestre = $classe->getAutreSemestreClasse();
                    if ($autreSemestre) {
                        $autreSemestre->etudiants()->detach();
                    }
                }

                DB::commit();

                // Construire le message de succès
                $autreSemestre = $classe->getAutreSemestreClasse();
                $message = 'Les étudiants ont été assignés avec succès';
                if ($autreSemestre) {
                    $message .= " aux deux semestres de l'année académique {$classe->annee_academique}";
                }
                $message .= '.';

                return redirect()->route('coordinateur.classes.index')->with('success', $message);

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->withInput()
                ->withErrors(['error' => 'Erreur lors de la modification des étudiants. Un étudiant est peut-être déjà assigné à une autre classe.']);
        }
    }

    // Affiche le formulaire de création de classe (admin uniquement)
    public function create()
    {
        $user = \Illuminate\Support\Facades\Auth::user();
        if ($user->role !== 'admin') {
            abort(403);
        }
        $coordinateurs = User::where('role', 'coordinateur')->get();
        $anneesAcademiques = \App\Models\AnneeAcademique::where('statut', 'en_cours')->get();
        return view('admin.create-classe', compact('coordinateurs', 'anneesAcademiques'));
    }

    // Enregistre la classe (admin uniquement, sans étudiants)
    public function store(Request $request)
    {
        $user = \Illuminate\Support\Facades\Auth::user();
        if ($user->role !== 'admin') {
            abort(403);
        }

        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'coordinateur_id' => 'required|exists:users,id',
            'annee_academique_id' => 'required|exists:annee_academiques,id',
            'etudiants' => 'nullable|array',
            'etudiants.*' => 'exists:users,id'
        ]);

        try {
            // Utiliser la méthode statique du modèle pour créer la classe
            $classe = Classe::creerClasse($validated);
            // Récupérer l'année académique
            $anneeAcademique = \App\Models\AnneeAcademique::findOrFail($validated['annee_academique_id']);
            return redirect()->route('admin.classes.index')
                ->with('success', "La classe a été créée avec succès pour le semestre 1 de l'année {$anneeAcademique->annee}. Le semestre 2 sera créé automatiquement lors de la clôture du semestre 1.");

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erreur lors de la création de la classe: ' . $e->getMessage());
            return redirect()->back()
                ->withInput()
                ->withErrors(['error' => 'Une erreur est survenue lors de la création de la classe: ' . $e->getMessage()]);
        }
    }

    // Supprime une classe
    public function destroy($id)
    {
        $classe = Classe::findOrFail($id);
        // Détacher tous les étudiants de cette classe
        \App\Models\User::where('classe_id', $classe->id)->update(['classe_id' => null]);
        $classe->delete();
        return back()->with('success', 'Classe supprimée avec succès.');
    }

    // Termine le semestre et migre les étudiants vers le semestre suivant

public function terminerSemestre($id)
{
    try {
        DB::beginTransaction();

        $user = Auth::user();
        $classe = Classe::findOrFail($id);

        // Vérifications de base
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

        // Trouver ou créer la classe du semestre 2
        $classeSemestre2 = Classe::where([
            'annee_academique' => $classe->annee_academique,
            'semestre' => '2',
            'coordinateur_id' => $classe->coordinateur_id
        ])->first();

        if (!$classeSemestre2) {
            // Créer la classe du semestre 2
            $classeSemestre2 = Classe::create([
                'nom' => $classe->nom,
                'coordinateur_id' => $classe->coordinateur_id,
                'annee_academique' => $classe->annee_academique,
                'semestre' => '2',
                'semestre_actuel' => 2,
                'semestre_termine' => false,
                'statut' => 'en_cours'
            ]);
        }

        // Obtenir et migrer les étudiants non "dropped" vers le semestre 2
        $etudiantsNonDropped = $classe->etudiants()
            ->wherePivot('dropped', false)
            ->get();

        if ($etudiantsNonDropped->count() > 0) {
            $classeSemestre2->etudiants()->sync(
                $etudiantsNonDropped->pluck('id')->toArray()
            );
        }

        // Marquer le semestre 1 comme terminé
        $updated = DB::table('classes')
            ->where('id', $classe->id)
            ->update([
                'statut' => 'termine',
                'semestre_termine' => true
            ]);

        if (!$updated) {
            throw new \Exception('Erreur lors de la terminaison du semestre.');
        }

        // Rafraîchir l'instance depuis la base de données
        $classe->refresh();

        DB::commit();
        return redirect()->back()->with('success',
            'Semestre terminé. ' . $etudiantsNonDropped->count() . ' étudiants non dropped migrés vers le semestre 2.');

    } catch (\Exception $e) {
        DB::rollBack();
        Log::error('Erreur lors de la terminaison du semestre: ' . $e->getMessage());
        return redirect()->back()
            ->withInput()
            ->withErrors(['error' => 'Une erreur est survenue : ' . $e->getMessage()]);
    }
}
}
