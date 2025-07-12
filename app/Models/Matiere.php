<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Matiere extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom',
    ];

    public function cours()
    {
        return $this->hasMany(Cours::class);
    }

    public function etudiants()
    {
        return $this->belongsToMany(User::class)
            ->withPivot('dropped')
            ->withTimestamps();
    }

    public function professeurs()
    {
        return $this->belongsToMany(User::class, 'cours', 'matiere_id', 'professeur_id')
            ->distinct()
            ->where('users.role', 'professeur');
    }
}
