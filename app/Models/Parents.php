<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Parents extends Model
{
    //
     protected $fillable = ['parent_id', 'etudiant_id'];
    public function parent()
    {
        return $this->belongsTo(User::class, 'parent_id');
    }
    public function etudiant()
    {
        return $this->belongsTo(User::class, 'etudiant_id');
    }


}
