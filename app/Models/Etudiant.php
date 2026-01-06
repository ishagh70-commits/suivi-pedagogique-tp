<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Presence;

class Etudiant extends Model
{
    protected $fillable = [
        'matricule', 'nom', 'prenom', 'filiere', 'niveau', 'group_id'
    ];

    public function presences()
    {
        return $this->hasMany(Presence::class);
    }

    public function group()
    {
        return $this->belongsTo(Group::class);
    }

    public function enseignants()
    {
        return $this->belongsToMany(Enseignant::class, 'enseignant_etudiant');
    }
}
