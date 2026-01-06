<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Modifier un étudiant</title>
</head>
<body>

<h1>Modifier un étudiant</h1>

@if($errors->any())
    <ul style="color:red;">
        @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
@endif

<form action="{{ route('etudiants.update', $etudiant) }}" method="POST">
    @csrf
    @method('PUT')

    <label>Matricule :</label>
    <input type="text" name="matricule" value="{{ old('matricule', $etudiant->matricule) }}"><br>

    <label>Nom :</label>
    <input type="text" name="nom" value="{{ old('nom', $etudiant->nom) }}"><br>

    <label>Prénom :</label>
    <input type="text" name="prenom" value="{{ old('prenom', $etudiant->prenom) }}"><br>

    <label>Email :</label>
    <input type="email" name="email" value="{{ old('email', $etudiant->email) }}"><br>

    <label>Filière :</label>
    <input type="text" name="filiere" value="{{ old('filiere', $etudiant->filiere) }}"><br>

    <label>Niveau :</label>
    <input type="number" name="niveau" value="{{ old('niveau', $etudiant->niveau) }}"><br>

    <label>Enseignant :</label>
    <select name="enseignant_id">
        @foreach ($enseignants as $ens)
            <option value="{{ $ens->id }}"
                {{ $etudiant->enseignant_id == $ens->id ? 'selected' : '' }}>
                {{ $ens->nom }} {{ $ens->prenom }}
            </option>
        @endforeach
    </select>

    <br><br>
    <button type="submit">Mettre à jour</button>
</form>

<br>
<a href="{{ route('etudiants.index') }}">Retour à la liste</a>

</body>
</html>
