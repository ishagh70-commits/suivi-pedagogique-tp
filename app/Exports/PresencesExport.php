<?php

namespace App\Exports;

use App\Models\Etudiant;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class PresencesExport implements FromCollection, WithHeadings
{
    public function __construct(
        public string $date,
        public ?int $groupId = null
    ) {}

    public function headings(): array
    {
        return [
            'ID',
            'Matricule',
            'Nom',
            'Prénom',
            'Groupe',
            'Date',
            'Statut',
        ];
    }

    public function collection(): Collection
    {
        $etudiants = Etudiant::query()
            ->with('group')
            ->when($this->groupId, fn ($q) => $q->where('group_id', $this->groupId))
            ->with(['presences' => function ($q) {
                $q->where('date', $this->date);
            }])
            ->orderBy('id')
            ->get();

        return $etudiants->map(function ($e) {
            $statut = ($e->presences && $e->presences->first())
                ? $e->presences->first()->statut
                : 'present';

            return [
                $e->id,
                $e->matricule,
                $e->nom,
                $e->prenom,
                $e->group?->name ?? '',
                $this->date,
                $statut,
            ];
        });
    }
}
