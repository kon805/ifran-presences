<?php

namespace App\Actions\Fortify;

use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;
use Illuminate\Http\Request;

class LoginResponse implements LoginResponseContract
{
    public function toResponse($request)
    {
        $user = $request->user();
        if ($user) {
            switch ($user->role) {
                case 'admin':
                    return redirect('/admin/dashboard');
                case 'coordinateur':
                    return redirect('/coordinateur');
                case 'professeur':
                    return redirect('/professeur');
                case 'etudiant':
                    return redirect('/etudiant');
                default:
                    abort(403);
            }
        }
        return redirect('/');
    }
}
