<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IsAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Valide si l'utilisateur connecté est un administrateur
        if (Auth::user() &&  Auth::user()->is_admin == 1) {
            return $next($request);
        }

        // S'il n'est pas administrateur, on retour à la page d'accueil avec un message d'erreur
        $errors = array("Vous devez avoir un accès administrateur pour accéder à cette page.");
        return redirect('/')->withErrors($errors);
    }
}
