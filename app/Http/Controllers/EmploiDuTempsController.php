<?php

namespace App\Http\Controllers;

use App\Models\Classe;
use App\Models\Cours;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class EmploiDuTempsController extends Controller
{
    public function index(Request $request)
    {
        $user = \Illuminate\Support\Facades\Auth::user();

        // Obtenir le lundi de la semaine courante
        $currentMonday = Carbon::now()->startOfWeek();

        // Si nous sommes au-delà de dimanche + 6 jours, passer à la semaine suivante
        if (Carbon::now()->diffInDays($currentMonday->copy()->addDays(6)) > 6) {
            $currentMonday = $currentMonday->addWeek();
        }

        // Permettre la navigation entre les semaines
        if ($request->has('week')) {
            $currentMonday = Carbon::parse($request->week)->startOfWeek();
        }

        $endOfWeek = $currentMonday->copy()->addDays(6);
        $previousWeek = $currentMonday->copy()->subWeek()->format('Y-m-d');
        $nextWeek = $currentMonday->copy()->addWeek()->format('Y-m-d');

        // Récupérer toutes les classes dont l'utilisateur est coordinateur
        $classes = \App\Models\Classe::where('coordinateur_id', $user->id)
            ->orderBy('nom')
            ->get();

        $classeId = $request->input('classe_id');

        // Récupérer uniquement les cours des classes dont l'utilisateur est coordinateur
        $coursQuery = Cours::whereBetween('date', [
                $currentMonday->format('Y-m-d'),
                $endOfWeek->format('Y-m-d')
            ])
            ->whereHas('classe', function($query) use ($user) {
                $query->where('coordinateur_id', $user->id);
            });

        // Appliquer le filtre par classe si spécifié
        if ($classeId) {
            $coursQuery->where('classe_id', $classeId);
        }

        $cours = $coursQuery
            ->with(['matiere', 'professeur', 'classe', 'types'])
            ->orderBy('date')
            ->orderBy('heure_debut')
            ->get()
            ->groupBy(function($cours) {
                return Carbon::parse($cours->date)->format('Y-m-d');
            });

        return view('coordinateur.planning.index', [
            'cours' => $cours,
            'currentMonday' => $currentMonday,
            'endOfWeek' => $endOfWeek,
            'previousWeek' => $previousWeek,
            'nextWeek' => $nextWeek,
            'classes' => $classes,
            'classeId' => $classeId
        ]);
    }

    public function exportPdf(Request $request)
    {
        $user = \Illuminate\Support\Facades\Auth::user();
        $startDate = Carbon::parse($request->start_date ?? Carbon::now()->startOfWeek());
        $endDate = $startDate->copy()->addDays(6);
        $classeId = $request->input('classe_id');

        $coursQuery = Cours::whereBetween('date', [
                $startDate->format('Y-m-d'),
                $endDate->format('Y-m-d')
            ])
            ->whereHas('classe', function($query) use ($user) {
                $query->where('coordinateur_id', $user->id);
            });

        // Appliquer le filtre par classe si spécifié
        if ($classeId) {
            $coursQuery->where('classe_id', $classeId);
        }

        $cours = $coursQuery
            ->with(['matiere', 'professeur', 'classe', 'types'])
            ->orderBy('date')
            ->orderBy('heure_debut')
            ->get()
            ->groupBy(function($cours) {
                return Carbon::parse($cours->date)->format('Y-m-d');
            });

        $pdf = PDF::loadView('coordinateur.planning.pdf', [
            'cours' => $cours,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'classeId' => $classeId
        ]);

        return $pdf->download('emploi-du-temps-' . $startDate->format('d-m-Y') . '.pdf');
    }
}
