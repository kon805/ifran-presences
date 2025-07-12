<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Justification extends Model
{
    //
        protected $fillable = [
        'presence_id',
        'coordinateur_id',
        'motif',
        'fichier',
        'justifiee',
    ];

    protected $casts = [
        'justifiee' => 'boolean',
    ];

    public function presence()
    {
        return $this->belongsTo(Presence::class);
    }

    public function coordinateur()
    {
        return $this->belongsTo(User::class, 'coordinateur_id');
    }

}
