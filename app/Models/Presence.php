<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Presence extends Model
{
    //
      use HasFactory;

    protected $fillable = [
        'cours_id',
        'etudiant_id',
        'statut'
    ];

    public function cours()
    {
        return $this->belongsTo(Cours::class);
    }

    public function etudiant()
    {
        return $this->belongsTo(User::class, 'etudiant_id');
    }
    public function justification()
    {
        return $this->hasOne(Justification::class);
    }
}
