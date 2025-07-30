<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Classe;
use App\Models\Cours;
use App\Models\User;
use App\Models\Presence;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class GraphiqueController extends Controller
{
    public function index()
    {
        $classes = Classe::orderBy('nom')->get();
        return view('coordinateur.graphiques.index', compact('classes'));
    }

    public function presenceEtudiant(Request $request)
    {
        $query = Presence::query()
            ->join('cours', 'presences.cours_id', '=', 'cours.id')
            ->join('users', 'presences.etudiant_id', '=', 'users.id');

        if ($request->filled('classe')) {
            $query->where('cours.classe_id', $request->classe);
        }

        if ($request->filled('date_debut')) {
            $query->where('cours.date', '>=', $request->date_debut);
        }

        if ($request->filled('date_fin')) {
            $query->where('cours.date', '<=', $request->date_fin);
        }

        $presences = $query->select('users.name', 'presences.etudiant_id')
            ->selectRaw('COUNT(CASE WHEN presences.statut = "present" THEN 1 END) as presents')
            ->selectRaw('COUNT(*) as total')
            ->groupBy('users.name', 'presences.etudiant_id')
            ->get()
            ->map(function ($item) {
                return [
                    'name' => $item->name,
                    'taux' => ($item->presents / $item->total) * 100
                ];
            })
            ->sortByDesc('taux')
            ->values();

        return response()->json([
            'labels' => $presences->pluck('name'),
            'data' => $presences->pluck('taux')
        ]);
    }

    public function presenceClasse(Request $request)
    {
        $query = Presence::query()
            ->join('cours', 'presences.cours_id', '=', 'cours.id')
            ->join('classes', 'cours.classe_id', '=', 'classes.id');

        if ($request->filled('date_debut')) {
            $query->where('cours.date', '>=', $request->date_debut);
        }

        if ($request->filled('date_fin')) {
            $query->where('cours.date', '<=', $request->date_fin);
        }

        $presences = $query->select('classes.nom')
            ->selectRaw('COUNT(CASE WHEN presences.statut = "present" THEN 1 END) as presents')
            ->selectRaw('COUNT(*) as total')
            ->groupBy('classes.nom')
            ->get()
            ->map(function ($item) {
                return [
                    'name' => $item->nom,
                    'taux' => ($item->presents / $item->total) * 100
                ];
            })
            ->sortByDesc('taux')
            ->values();

        return response()->json([
            'labels' => $presences->pluck('name'),
            'data' => $presences->pluck('taux')
        ]);
    }

    public function volumeCours(Request $request)
    {
        $query = Cours::with('types');

        if ($request->filled('classe')) {
            $query->where('classe_id', $request->classe);
        }

        if ($request->filled('date_debut')) {
            $query->where('date', '>=', $request->date_debut);
        }

        if ($request->filled('date_fin')) {
            $query->where('date', '<=', $request->date_fin);
        }

        $cours = $query->get();

        $volumes = [
            'presentiel' => 0,
            'e-learning' => 0,
            'workshop' => 0
        ];

        foreach ($cours as $cours_item) {
            $duree = $this->calculerDuree($cours_item->heure_debut, $cours_item->heure_fin);
            foreach ($cours_item->types as $type) {
                $volumes[$type->code] += $duree;
            }
        }

        return response()->json(array_values($volumes));
    }

    private function calculerDuree($heure_debut, $heure_fin)
    {
        if (!$heure_debut || !$heure_fin) return 0;
        $debut = \Carbon\Carbon::parse($heure_debut);
        $fin = \Carbon\Carbon::parse($heure_fin);
        return $fin->diffInHours($debut) + ($fin->diffInMinutes($debut) % 60) / 60;
    }

    public function volumeCumule(Request $request)
    {
        $query = Cours::with('types');

        if ($request->filled('classe')) {
            $query->where('classe_id', $request->classe);
        }

        if ($request->filled('date_debut')) {
            $query->where('date', '>=', $request->date_debut);
        }

        if ($request->filled('date_fin')) {
            $query->where('date', '<=', $request->date_fin);
        }

        $cours = $query->orderBy('date')->get();

        // Grouper les cours par date en s'assurant que la date est bien un objet Carbon
        $coursGroupes = $cours->groupBy(function ($item) {
            return Carbon::parse($item->date)->format('Y-m-d');
        });

        $cumule = [
            'presentiel' => 0,
            'e-learning' => 0,
            'workshop' => 0
        ];

        $data = [
            'labels' => [],
            'presentiel' => [],
            'e-learning' => [],
            'workshop' => []
        ];

        foreach ($coursGroupes as $date => $coursJour) {
            foreach ($coursJour as $cours_item) {
                $duree = $this->calculerDuree($cours_item->heure_debut, $cours_item->heure_fin);
                foreach ($cours_item->types as $type) {
                    $cumule[$type->code] += $duree;
                }
            }

            $data['labels'][] = Carbon::parse($date)->format('d/m/Y');
            $data['presentiel'][] = $cumule['presentiel'];
            $data['e-learning'][] = $cumule['e-learning'];
            $data['workshop'][] = $cumule['workshop'];
        }

        return response()->json($data);
    }
    //
}
