<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Emploi du temps - Export PDF</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            font-size: 12px;
        }
        h1 {
            color: #1e3a8a;
            font-size: 24px;
            margin-bottom: 20px;
        }
        h2 {
            color: #374151;
            font-size: 18px;
            margin-bottom: 10px;
            padding: 10px;
            background-color: #f3f4f6;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th {
            background-color: #f3f4f6;
            color: #374151;
            font-weight: bold;
            text-align: left;
            padding: 8px;
            border: 1px solid #e5e7eb;
        }
        td {
            padding: 8px;
            border: 1px solid #e5e7eb;
        }
        .date-header {
            margin-top: 20px;
            margin-bottom: 10px;
        }
        .no-cours {
            text-align: center;
            color: #6b7280;
            padding: 20px;
        }
        .type-cours {
            display: inline-block;
            padding: 2px 6px;
            border-radius: 4px;
            font-size: 11px;
        }
        .type-workshop {
            background-color: #fff7ed;
            color: #9a3412;
        }
        .type-elearning {
            background-color: #eff6ff;
            color: #1e40af;
        }
        .type-default {
            background-color: #f3f4f6;
            color: #374151;
        }
        .page-break {
            page-break-after: always;
        }
    </style>
</head>
<body>
    <h1>
        Emploi du temps
        <span style="font-size: 16px; color: #6b7280;">
            (Semaine du {{ $currentMonday->format('d/m/Y') }} au {{ $endOfWeek->format('d/m/Y') }})
        </span>
    </h1>

    @forelse($cours as $date => $coursJour)
        <div class="date-header">
            <h2>{{ Carbon\Carbon::parse($date)->locale('fr')->format('l d F Y') }}</h2>
        </div>

        <table>
            <thead>
                <tr>
                    <th style="width: 15%;">Horaire</th>
                    <th style="width: 25%;">Matière</th>
                    <th style="width: 20%;">Professeur</th>
                    <th style="width: 20%;">Classe</th>
                    <th style="width: 20%;">Type</th>
                </tr>
            </thead>
            <tbody>
                @foreach($coursJour as $cours)
                    <tr>
                        <td>{{ $cours->heure_debut }} - {{ $cours->heure_fin }}</td>
                        <td>{{ $cours->matiere->nom }}</td>
                        <td>{{ $cours->professeur->name }}</td>
                        <td>{{ $cours->classe->nom }}</td>
                        <td>
                            @foreach($cours->types as $type)
                                @if($type->code === 'workshop')
                                    <span class="type-cours type-workshop">{{ $type->nom }}</span>
                                @elseif($type->code === 'e-learning')
                                    <span class="type-cours type-elearning">{{ $type->nom }}</span>
                                @else
                                    <span class="type-cours type-default">{{ $type->nom }}</span>
                                @endif
                            @endforeach
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        @if(!$loop->last)
            <div class="page-break"></div>
        @endif
    @empty
        <div class="no-cours">
            Aucun cours programmé pour cette semaine
        </div>
    @endforelse
</body>
</html>
