<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Emploi du temps</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            color: #111;
        }

        h1 {
            text-align: center;
            margin-bottom: 5px;
        }

        .subtitle {
            text-align: center;
            font-size: 11px;
            margin-bottom: 15px;
            color: #555;
        }

        .info {
            margin-bottom: 10px;
            font-weight: bold;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 8px;
        }

        th, td {
            border: 1px solid #333;
            padding: 6px;
            text-align: center;
        }

        th {
            background: #f3f4f6;
        }

        tr:nth-child(even) {
            background: #fafafa;
        }

        .footer {
            margin-top: 20px;
            text-align: right;
            font-size: 10px;
            color: #666;
        }
    </style>
</head>
<body>

<h1>Emploi du temps</h1>
<div class="subtitle">
    Année universitaire {{ date('Y') -1 }} / {{ date('Y')  }}
</div>

<div class="info">
    Groupe : {{ $group->name }} 
</div>

<table>
    <thead>
        <tr>
            <th>Jour</th>
            <th>Heure</th>
            <th>Matière</th>
            <th>Enseignant</th>
            <th>Salle</th>
        </tr>
    </thead>
    <tbody>
        @forelse($emplois as $e)
            <tr>
                <td>{{ $e->jour }}</td>
                <td>
                    {{ \Illuminate\Support\Str::of($e->heure_debut)->substr(0,5) }}
                    -
                    {{ \Illuminate\Support\Str::of($e->heure_fin)->substr(0,5) }}
                </td>
                <td>{{ $e->matiere }}</td>
                <td>
                    {{ $e->enseignant->nom }}
                    {{ $e->enseignant->prenom }}
                </td>
                <td>{{ $e->salle ?? '-' }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="5">
                    Aucun créneau pour ce groupe
                </td>
            </tr>
        @endforelse
    </tbody>
</table>

<div class="footer">
    Généré le {{ date('d/m/Y à H:i') }}
</div>

</body>
</html>
