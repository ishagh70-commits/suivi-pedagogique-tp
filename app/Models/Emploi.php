<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Emploi extends Model
{
    use HasFactory;

    protected $fillable = [
        'group_id', 'enseignant_id', 'matiere', 'jour',
        'heure_debut', 'heure_fin', 'salle'
    ];

    public function group()
    {
        return $this->belongsTo(Group::class);
    }

    public function enseignant()
    {
        return $this->belongsTo(Enseignant::class);
    }
}
