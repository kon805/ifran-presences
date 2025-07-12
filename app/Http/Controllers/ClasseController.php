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
        return view('admin.create-classe', compact('coordinateurs'));
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
            'annee_academique' => 'required|string|regex:/^\d{4}-\d{4}$/',
            'semestre' => 'required|in:1,2',
        ]);

        try {
            DB::beginTransaction();

            // Créer la première classe pour le semestre demandé
            $classe1 = Classe::create([
                'nom' => $validated['nom'],
                'coordinateur_id' => $validated['coordinateur_id'],
                'annee_academique' => $validated['annee_academique'],
                'semestre' => $validated['semestre']
            ]);

            // Créer automatiquement la classe pour l'autre semestre
            $autreSemestre = $validated['semestre'] === '1' ? '2' : '1';
            $classe2 = Classe::create([
                'nom' => str_replace("S{$validated['semestre']}", "S{$autreSemestre}", $validated['nom']),
                'coordinateur_id' => $validated['coordinateur_id'],
                'annee_academique' => $validated['annee_academique'],
                'semestre' => $autreSemestre
            ]);

            DB::commit();
            return redirect()->route('admin.classes.index')
                ->with('success', "Les classes des semestres 1 et 2 ont été créées avec succès pour l'année {$validated['annee_academique']}");

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->withInput()
                ->withErrors(['error' => 'Une erreur est survenue lors de la création des classes.']);
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
        $user = Auth::user();
        $classe = Classe::findOrFail($id);

        // Vérifications de base
        if (!in_array($user->role, ['admin', 'coordinateur'])) {
            return redirect()->back()->withErrors(['error' => 'Accès non autorisé.']);
        }

        if ($user->role === 'coordinateur' && $classe->coordinateur_id !== $user->id) {
            return redirect()->back()->withErrors(['error' => 'Vous n\'êtes pas le coordinateur de cette classe.']);
        }

        if ($classe->semestre !== '1') {
            return redirect()->back()->withErrors(['error' => 'Seul le semestre 1 peut être terminé.']);
        }

        if ($classe->statut === 'termine') {
            return redirect()->back()->withErrors(['error' => 'Cette classe est déjà terminée.']);
        }

        // Trouver et vérifier la classe du semestre 2
        $classeSemestre2 = Classe::where('annee_academique', $classe->annee_academique)
                               ->where('semestre', '2')
                               ->first();

        if (!$classeSemestre2) {
            return redirect()->back()->withErrors(['error' => 'La classe du semestre 2 n\'existe pas.']);
        }

        try {
            // Obtenir les étudiants du semestre 1
            $etudiants = $classe->etudiants;

            // Les ajouter au semestre 2 s'il y en a
            if ($etudiants->count() > 0) {
                foreach ($etudiants as $etudiant) {
                    if (!$classeSemestre2->etudiants->contains($etudiant->id)) {
                        $classeSemestre2->etudiants()->attach($etudiant->id);
                    }
                }
            }

            // Marquer le semestre comme terminé
            DB::table('classes')
                ->where('id', $classe->id)
                ->update(['statut' => 'termine']);

            return redirect()->back()->with('success',
                'Semestre terminé. ' . $etudiants->count() . ' étudiants migrés vers le semestre 2.');

        } catch (\Exception $e) {
            Log::error('Erreur lors de la terminaison du semestre: ' . $e->getMessage());
            return redirect()->back()->withErrors(['error' => 'Une erreur est survenue. Veuillez réessayer.']);
        }
    }
}
