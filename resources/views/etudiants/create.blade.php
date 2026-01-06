@extends('layouts.app')

@section('title', 'Ajouter un étudiant')

@section('content')
<div class="row justify-content-center">
  <div class="col-md-8 col-lg-6">
    <div class="card shadow-sm">
      <div class="card-header bg-white">
        <h4 class="mb-0">Ajouter un étudiant</h4>
        <small class="text-muted">Remplis les infos puis clique sur Enregistrer</small>
      </div>

      <div class="card-body">
        <form action="{{ route('etudiants.store') }}" method="POST">
          @csrf

          <div class="mb-3">
            <label class="form-label">Matricule</label>
            <input type="text" name="matricule"
                   class="form-control @error('matricule') is-invalid @enderror"
                   value="{{ old('matricule') }}" required>
            @error('matricule') <div class="invalid-feedback">{{ $message }}</div> @enderror
          </div>

          <div class="mb-3">
            <label class="form-label">Nom</label>
            <input type="text" name="nom"
                   class="form-control @error('nom') is-invalid @enderror"
                   value="{{ old('nom') }}" required>
            @error('nom') <div class="invalid-feedback">{{ $message }}</div> @enderror
          </div>

          <div class="mb-3">
            <label class="form-label">Prénom</label>
            <input type="text" name="prenom"
                   class="form-control @error('prenom') is-invalid @enderror"
                   value="{{ old('prenom') }}" required>
            @error('prenom') <div class="invalid-feedback">{{ $message }}</div> @enderror
          </div>

          <div class="mb-3">
            <label class="form-label">Filière</label>
            <input type="text" name="filiere"
                   class="form-control @error('filiere') is-invalid @enderror"
                   value="{{ old('filiere') }}" required>
            @error('filiere') <div class="invalid-feedback">{{ $message }}</div> @enderror
          </div>

          <div class="mb-3">
            <label class="form-label">Niveau</label>
            <select name="niveau"
                    class="form-select @error('niveau') is-invalid @enderror" required>
              <option value="">-- Choisir un niveau --</option>
              @for($i=1; $i<=5; $i++)
                <option value="{{ $i }}" {{ old('niveau') == $i ? 'selected' : '' }}>
                  Niveau {{ $i }}
                </option>
              @endfor
            </select>
            @error('niveau') <div class="invalid-feedback">{{ $message }}</div> @enderror
          </div>

          <div class="mb-3">
            <label class="form-label">Enseignant</label>
            <select name="enseignant_id"
                    class="form-select @error('enseignant_id') is-invalid @enderror" required>
              <option value="">-- Choisir un enseignant --</option>
              @foreach($enseignants as $enseignant)
                <option value="{{ $enseignant->id }}"
                  {{ old('enseignant_id') == $enseignant->id ? 'selected' : '' }}>
                  {{ $enseignant->nom }} {{ $enseignant->prenom }}
                </option>
              @endforeach
            </select>
            @error('enseignant_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
          </div>

          <div class="d-flex gap-2">
            <button class="btn btn-primary" type="submit">Enregistrer</button>
            <a class="btn btn-outline-secondary" href="{{ route('etudiants.index') }}">Annuler</a>
          </div>

        </form>
      </div>
    </div>
  </div>
</div>
@endsection
