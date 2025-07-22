<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Emploi du temps</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .table th, .table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        .table th {
            background-color: #f8f9fa;
        }
        .day-header {
            font-size: 14px;
            font-weight: bold;
            margin-top: 15px;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Emploi du temps</h1>
        <p>Du {{ $startDate->format('d/m/Y') }} au {{ $endDate->format('d/m/Y') }}</p>
    </div>


    @foreach($cours as $date => $coursJour)
    <div class="day-section">
        <div class="day-header">
            {{ Carbon\Carbon::parse($date)->locale('fr')->format('l d F Y') }}
        </div>
        <table class="table">
            <thead>
                <tr>
                    <th>Horaire</th>
                    <th>Matière</th>
                    <th>Professeur</th>
                    <th>Classe</th>
                    <th>Type</th>
                </tr>
            </thead>
            <tbody>
                @foreach($coursJour as $cours)
                <tr>
                    <td>{{ $cours->heure_debut }} - {{ $cours->heure_fin }}</td>
                    <td>{{ $cours->matiere->nom }}</td>
                    <td>{{ $cours->professeur->name }}</td>
                    <td>{{ $cours->classe->nom }}</td>
                    <td>{{ $cours->types->pluck('nom')->implode(', ') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endforeach
</body>
</html>
