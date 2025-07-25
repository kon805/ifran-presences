<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class EtudiantPolicy
{
    use HandlesAuthorization;

    /**
     * Determine if a parent user can view an etudiant's details.
     *
     * @param User $user The parent user
     * @param User $etudiant The student
     * @return bool
     */
    public function view(User $user, User $etudiant)
    {
        // Le parent peut voir un étudiant si l'étudiant est lié au parent
        return $user->role === 'parent' &&
               $etudiant->role === 'etudiant' &&
               $etudiant->parents()->where('user_id', $user->id)->exists();
    }
}
