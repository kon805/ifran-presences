<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens;

    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory;
    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'matricule',
        'photo',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Get all parent-student relationships where this user is the parent.
     */
    public function parentStudentRelations()
    {
        return $this->hasMany(ParentStudent::class, 'user_id');
    }

    /**
     * Get all parent-student relationships where this user is the student.
     */
    public function studentParentRelations()
    {
        return $this->hasMany(ParentStudent::class, 'etudiant_id');
    }

    public function classe()
    {
        return $this->belongsTo(Classe::class);
    }

    public function presences()
    {
        return $this->hasMany(Presence::class, 'etudiant_id');
    }

    /**
     * Récupère les enfants (étudiants) associés à ce parent
     */
    public function enfants(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'parents', 'user_id', 'etudiant_id')
                    ->where('role', 'etudiant')
                    ->withTimestamps();
    }

    public function parents()
    {
        return $this->belongsToMany(User::class, 'parents', 'etudiant_id', 'user_id')
                    ->where('role', 'parent');
    }
public function matieres()
{
    return $this->belongsToMany(\App\Models\Matiere::class)
    ->withPivot('dropped')
    ->withTimestamps();
}

public function classes()
{
    return $this->belongsToMany(\App\Models\Classe::class, 'classe_user')
    ->withPivot('dropped')
    ->withTimestamps();
}

}
