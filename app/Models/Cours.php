<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Cours extends Model
{
    //
     use HasFactory;


        protected $fillable = [
        'classe_id',
        'professeur_id',
        'matiere',
        'date',
        'heure_debut',
        'heure_fin',
        'etat'
    ];

    public function classe()
    {
        return $this->belongsTo(Classe::class);
    }

    public function professeur()
    {
        return $this->belongsTo(User::class, 'professeur_id');
    }

    public function presences()
    {
        return $this->hasMany(Presence::class);
    }
}
