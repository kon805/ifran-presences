<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TypeCours extends Model
{
    use HasFactory;

    protected $table = 'type_cours';

    protected $fillable = [
        'nom',
        'code',
        'description'
    ];

    public function cours()
    {
        return $this->belongsToMany(Cours::class, 'cours_type', 'type_cours_id', 'cours_id');
    }
}
