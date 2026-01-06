<?php

namespace App\Http\Controllers;

use App\Models\Emploi;
use App\Models\Group;
use App\Models\Enseignant;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Inertia\Inertia;

class EmploiController extends Controller
{
    public function index(Request $request)
    {
        $groupId = $request->input('group_id');

        $groups = Group::orderBy('name')->get(['id', 'name', 'code']);
        $enseignants = Enseignant::orderBy('nom')->get(['id', 'nom', 'prenom', 'specialite']);

        $emplois = Emploi::query()
            ->with(['group:id,name,code', 'enseignant:id,nom,prenom,specialite'])
            ->when($groupId, fn ($q) => $q->where('group_id', $groupId))
            ->orderByRaw("FIELD(jour,'Lundi','Mardi','Mercredi','Jeudi','Vendredi','Samedi')")
            ->orderBy('heure_debut')
            ->get();

        return Inertia::render('Emploi/Index', [
            'groups' => $groups,
            'enseignants' => $enseignants,
            'group_id' => $groupId ? (int) $groupId : null,
            'emplois' => $emplois,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'group_id'      => 'required|exists:groups,id',
            'enseignant_id' => 'required|exists:enseignants,id',
            'matiere'       => 'required|string|max:255',
            'jour'          => 'required|in:Lundi,Mardi,Mercredi,Jeudi,Vendredi,Samedi',
            'heure_debut'   => 'required|date_format:H:i',
            'heure_fin'     => 'required|date_format:H:i|after:heure_debut',
            'salle'         => 'nullable|string|max:100',
        ]);

        $conflict = Emploi::where('group_id', $validated['group_id'])
            ->where('jour', $validated['jour'])
            ->where(function ($q) use ($validated) {
                $q->whereBetween('heure_debut', [$validated['heure_debut'], $validated['heure_fin']])
                  ->orWhereBetween('heure_fin', [$validated['heure_debut'], $validated['heure_fin']])
                  ->orWhere(function ($q2) use ($validated) {
                      $q2->where('heure_debut', '<=', $validated['heure_debut'])
                         ->where('heure_fin', '>=', $validated['heure_fin']);
                  });
            })
            ->exists();

        if ($conflict) {
            return back()->withErrors([
                'heure_debut' => "Conflit d'horaires : ce créneau chevauche un autre cours du même groupe.",
            ]);
        }

        Emploi::create($validated);

        return redirect()
            ->route('emploi.index', ['group_id' => $validated['group_id']])
            ->with('success', 'Créneau ajouté ✅');
    }

    public function update(Request $request, Emploi $emploi)
    {
        $validated = $request->validate([
            'group_id'      => 'required|exists:groups,id',
            'enseignant_id' => 'required|exists:enseignants,id',
            'matiere'       => 'required|string|max:255',
            'jour'          => 'required|in:Lundi,Mardi,Mercredi,Jeudi,Vendredi,Samedi',
            'heure_debut'   => 'required|date_format:H:i',
            'heure_fin'     => 'required|date_format:H:i|after:heure_debut',
            'salle'         => 'nullable|string|max:100',
        ]);

        $conflict = Emploi::where('id', '!=', $emploi->id)
            ->where('group_id', $validated['group_id'])
            ->where('jour', $validated['jour'])
            ->where(function ($q) use ($validated) {
                $q->whereBetween('heure_debut', [$validated['heure_debut'], $validated['heure_fin']])
                  ->orWhereBetween('heure_fin', [$validated['heure_debut'], $validated['heure_fin']])
                  ->orWhere(function ($q2) use ($validated) {
                      $q2->where('heure_debut', '<=', $validated['heure_debut'])
                         ->where('heure_fin', '>=', $validated['heure_fin']);
                  });
            })
            ->exists();

        if ($conflict) {
            return back()->withErrors([
                'heure_debut' => "Conflit d'horaires : ce créneau chevauche un autre cours du même groupe.",
            ]);
        }

        $emploi->update($validated);

        return redirect()
            ->route('emploi.index', ['group_id' => $validated['group_id']])
            ->with('success', 'Créneau modifié ✅');
    }

    public function destroy(Emploi $emploi)
    {
        $groupId = $emploi->group_id;
        $emploi->delete();

        return redirect()
            ->route('emploi.index', ['group_id' => $groupId])
            ->with('success', 'Créneau supprimé ✅');
    }

    public function exportPdf(Request $request)
    {
        // Comme c'est un GET /emploi/export/pdf?group_id=...
        $groupId = $request->query('group_id');

        if (!$groupId) {
            return back()->withErrors(['group_id' => 'Veuillez choisir un groupe.']);
        }

        $group = Group::findOrFail($groupId);

        $emplois = Emploi::with('enseignant')
            ->where('group_id', $groupId)
            ->orderByRaw("FIELD(jour,'Lundi','Mardi','Mercredi','Jeudi','Vendredi','Samedi')")
            ->orderBy('heure_debut')
            ->get();

        $pdf = Pdf::loadView('pdf.emploi', [
            'group'   => $group,
            'emplois' => $emplois,
        ])->setPaper('a4', 'portrait');

        return $pdf->download('emploi_du_temps_' . ($group->code ?? $group->id) . '.pdf');
    }
}
