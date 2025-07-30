<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AnneeAcademique extends Model
{
    protected $fillable = [
        'annee',
        'date_debut',
        'date_fin',
        'statut'
    ];

    protected $dates = [
        'date_debut',
        'date_fin'
    ];

    public function classes(): HasMany
    {
        return $this->hasMany(Classe::class);
    }

    public function isActive(): bool
    {
        return $this->statut === 'en_cours';
    }
}
