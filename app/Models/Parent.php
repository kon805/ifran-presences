<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ParentModel extends Model
{
    protected $fillable = [
        'user_id',
        'etudiant_id',
    ];

    /**
     * Get the parent user record.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get the student user record.
     */
    public function student(): BelongsTo
    {
        return $this->belongsTo(User::class, 'etudiant_id');
    }
}
