<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    protected $fillable = ['name', 'code'];

    public function etudiants()
    {
        return $this->hasMany(Etudiant::class);
    }
}
