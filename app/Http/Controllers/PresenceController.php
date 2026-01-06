<?php

namespace App\Http\Controllers;

use App\Models\Etudiant;
use App\Models\Presence;
use App\Models\Group;
use App\Exports\PresencesExport;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PresenceController extends Controller
{
    public function index(Request $request)
    {
        $date = $request->get('date', now()->toDateString());
        $groupId = $request->get('group_id');

        $groups = Group::orderBy('name')->get();

        $etudiants = Etudiant::query()
            ->when($groupId, fn ($q) => $q->where('group_id', $groupId))
            ->with(['presences' => function ($q) use ($date) {
                $q->where('date', $date);
            }])
            ->orderBy('id')
            ->get();

        return Inertia::render('Presences/Index', [
            'date' => $date,
            'groups' => $groups,
            'group_id' => $groupId,
            'etudiants' => $etudiants,
        ]);
    }

	public function exportExcel(Request $request)
	{
    $date = $request->get('date', now()->toDateString());
    $groupId = $request->get('group_id') ? (int)$request->get('group_id') : null;

    return Excel::download(
        new PresencesExport($date, $groupId),
        "presences_{$date}.xlsx"
		);
	}
	
	public function exportPdf(Request $request)
	{
    $date = $request->get('date', now()->toDateString());
    $groupId = $request->get('group_id') ? (int)$request->get('group_id') : null;

    $etudiants = Etudiant::query()
        ->with('group')
        ->when($groupId, fn ($q) => $q->where('group_id', $groupId))
        ->with(['presences' => fn ($q) => $q->where('date', $date)])
        ->orderBy('id')
        ->get();

    $pdf = Pdf::loadView('pdf.presences', compact('date', 'etudiants'));

    return $pdf->download("presences_{$date}.pdf");
	}

	
	
    public function store(Request $request)
    {
        $data = $request->validate([
            'date' => 'required|date',
            'group_id' => 'required|exists:groups,id',
            'items' => 'required|array',
            'items.*.etudiant_id' => 'required|exists:etudiants,id',
            'items.*.statut' => 'required|in:present,absent',
        ]);
	
        $allowedIds = Etudiant::where('group_id', $data['group_id'])
            ->pluck('id')
            ->all();

        foreach ($data['items'] as $item) {
            if (!in_array($item['etudiant_id'], $allowedIds)) {
                continue;
            }

            Presence::updateOrCreate(
                ['etudiant_id' => $item['etudiant_id'], 'date' => $data['date']],
                ['statut' => $item['statut']]
            );
        }

        return redirect()->route('presences.index', [
            'date' => $data['date'],
            'group_id' => $data['group_id'],
        ])->with('success', 'Présences enregistrées ✅');
    }
}
