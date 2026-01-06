<!doctype html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <style>
    body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
    table { width: 100%; border-collapse: collapse; margin-top: 10px; }
    th, td { border: 1px solid #333; padding: 6px; }
    th { background: #f2f2f2; }
  </style>
</head>
<body>
  <h2>Présences / Absences — {{ $date }}</h2>

  <table>
    <thead>
      <tr>
        <th>ID</th>
        <th>Matricule</th>
        <th>Nom</th>
        <th>Prénom</th>
        <th>Groupe</th>
        <th>Statut</th>
      </tr>
    </thead>
    <tbody>
      @foreach($etudiants as $e)
        @php
          $statut = ($e->presences && $e->presences->first()) ? $e->presences->first()->statut : 'present';
        @endphp
        <tr>
          <td>{{ $e->id }}</td>
          <td>{{ $e->matricule }}</td>
          <td>{{ $e->nom }}</td>
          <td>{{ $e->prenom }}</td>
          <td>{{ $e->group?->name }}</td>
          <td>{{ $statut }}</td>
        </tr>
      @endforeach
    </tbody>
  </table>
</body>
</html>
