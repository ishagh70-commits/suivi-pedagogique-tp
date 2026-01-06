<?php

namespace App\Http\Controllers;

use App\Models\Etudiant;
use App\Models\Group;
use Illuminate\Http\Request;
use Inertia\Inertia;

class EtudiantController extends Controller
{
    // Liste des étudiants (Inertia)
    public function index()
    {
        $etudiants = Etudiant::with('group')
            ->orderBy('id')
            ->get();

        return Inertia::render('Etudiants/Index', [
            'etudiants' => $etudiants,
        ]);
    }

    // Formulaire de création (Inertia)
    public function create()
    {
        $groups = Group::orderBy('name')->get();

        return Inertia::render('Etudiants/Create', [
            'groups' => $groups,
        ]);
    }

    // Enregistrement d’un étudiant
    public function store(Request $request)
{
    $validated = $request->validate([
        'matricule' => 'required|string|max:255|unique:etudiants,matricule',
        'nom'       => 'required|string|max:255',
        'prenom'    => 'required|string|max:255',
        'filiere'   => 'required|string|max:255',
        'niveau'    => 'required|in:L1,L2,L3,M1,M2',
        'group_id'  => 'required|exists:groups,id',
    ]);

    Etudiant::create($validated);

    return redirect()->route('etudiants.index')
        ->with('success', 'Étudiant ajouté avec succès ✅');
}

        

    // (Optionnel) édition plus tard en Inertia
    public function edit(Etudiant $etudiant)
    {
        $groups = Group::orderBy('name')->get();

        return Inertia::render('Etudiants/Edit', [
            'etudiant' => $etudiant->load('group'),
            'groups'   => $groups,
        ]);
    }

    public function update(Request $request, Etudiant $etudiant)
    {
        $validated = $request->validate([
            'matricule' => 'required|string|max:255|unique:etudiants,matricule,' . $etudiant->id,
            'nom'       => 'required|string|max:255',
            'prenom'    => 'required|string|max:255',
            'filiere'   => 'required|string|max:255',
            'niveau' => 'required|in:L1,L2,L3,M1,M2',
            'group_id'  => 'required|exists:groups,id',
        ]);

        $etudiant->update($validated);

        return redirect()->route('etudiants.index')
            ->with('success', 'Étudiant mis à jour avec succès ✅');
    }

    public function destroy(Etudiant $etudiant)
    {
        $etudiant->delete();

        return redirect()->route('etudiants.index')
            ->with('success', 'Étudiant supprimé avec succès ✅');
    }
}
