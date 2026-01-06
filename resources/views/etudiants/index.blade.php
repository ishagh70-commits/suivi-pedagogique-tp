@extends('layouts.app')

@section('title', 'Liste des étudiants')

@section('content')
<div class="d-flex align-items-center justify-content-between mb-3">
  <div>
    <h3 class="mb-0">Liste des étudiants</h3>
    <small class="text-muted">Gestion des étudiants (ajout, modification, suppression)</small>
  </div>

  <a href="{{ route('etudiants.create') }}" class="btn btn-primary">
    + Ajouter un étudiant
  </a>
</div>

<div class="card shadow-sm">
  <div class="card-body">

    <div class="table-responsive">
      <table class="table table-hover align-middle mb-0">
        <thead class="table-light">
          <tr>
            <th style="width:80px;">ID</th>
            <th>Matricule</th>
            <th>Nom</th>
            <th>Prénom</th>
            <th>Filière</th>
            <th>Niveau</th>
            <th>Enseignant</th>
            <th class="text-end" style="width:220px;">Actions</th>
          </tr>
        </thead>

        <tbody>
          @forelse($etudiants as $etudiant)
            <tr>
              <td class="fw-semibold">{{ $etudiant->id }}</td>
              <td>{{ $etudiant->matricule ?? '-' }}</td>
              <td>{{ $etudiant->nom }}</td>
              <td>{{ $etudiant->prenom }}</td>
              <td>{{ $etudiant->filiere ?? '-' }}</td>
              <td>{{ $etudiant->niveau ?? '-' }}</td>

              <td>
                @if($etudiant->enseignant)
                  {{ $etudiant->enseignant->nom }} {{ $etudiant->enseignant->prenom }}
                @else
                  <span class="text-muted">—</span>
                @endif
              </td>

              <td class="text-end">
                <a href="{{ route('etudiants.edit', $etudiant) }}" class="btn btn-sm btn-outline-primary">
                  Modifier
                </a>

                <form action="{{ route('etudiants.destroy', $etudiant) }}"
                      method="POST" class="d-inline">
                  @csrf
                  @method('DELETE')
                  <button type="submit" class="btn btn-sm btn-outline-danger"
                          onclick="return confirm('Supprimer cet étudiant ?')">
                    Supprimer
                  </button>
                </form>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="8" class="text-center text-muted py-4">
                Aucun étudiant pour le moment.
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>

  </div>
</div>
@endsection
