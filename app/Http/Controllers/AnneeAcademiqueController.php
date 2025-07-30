<?php

namespace App\Http\Controllers;

use App\Models\AnneeAcademique;
use App\Models\Classe;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AnneeAcademiqueController extends Controller
{
    public function index()
    {
        $annees = AnneeAcademique::withCount('classes')
            ->orderBy('date_debut', 'desc')
            ->get();
        return view('admin.annees-academiques.index', compact('annees'));
    }

    public function create()
    {
        return view('admin.annees-academiques.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'annee' => 'required|unique:annee_academiques',
            'date_debut' => 'required|date',
            'date_fin' => 'required|date|after:date_debut'
        ]);

        $anneeAcademique = AnneeAcademique::create($validated);

        return redirect()->route('admin.annees-academiques.index')
            ->with('success', 'Année académique créée avec succès.');
    }

    public function show($id)
    {
        $anneeAcademique = AnneeAcademique::findOrFail($id);

        $anneeAcademique->load([
            'classes.etudiants',
            'classes.professeurs',
            'classes.matieres'
        ]);

        $statistiques = [
            'total_etudiants' => $anneeAcademique->classes->sum(function($classe) {
                return $classe->etudiants->count();
            }),
            'total_professeurs' => $anneeAcademique->classes->flatMap(function($classe) {
                return $classe->professeurs;
            })->unique('id')->count(),
            'total_matieres' => $anneeAcademique->classes->flatMap(function($classe) {
                return $classe->matieres;
            })->unique('id')->count(),
        ];

        return view('admin.annees-academiques.show', compact('anneeAcademique', 'statistiques'));
    }

    public function terminer(AnneeAcademique $anneeAcademique)
    {
        DB::transaction(function() use ($anneeAcademique) {
            // Marquer l'année comme terminée
            $anneeAcademique->update(['statut' => 'terminee']);

            // Verrouiller les classes associées
            $anneeAcademique->classes()->update(['statut' => 'terminee']);
        });

        return redirect()->back()
            ->with('success', 'Année académique marquée comme terminée avec succès.');
    }
}
