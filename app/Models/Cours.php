<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Cours extends Model
{
    use HasFactory;

    protected $fillable = [
        'classe_id',
        'professeur_id',
        'matiere_id',
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

    public function matiere()
    {
        return $this->belongsTo(Matiere::class);
    }

    public function presences()
    {
        return $this->hasMany(Presence::class);
    }

    public function types()
    {
        return $this->belongsToMany(TypeCours::class, 'cours_type', 'cours_id', 'type_cours_id');
    }

    public function isPresentiel()
    {
        return $this->types()->where('code', 'presentiel')->exists();
    }

    public function isElearning()
    {
        return $this->types()->where('code', 'e-learning')->exists();
    }

    public function isWorkshop()
    {
        return $this->types()->where('code', 'workshop')->exists();
    }
}
