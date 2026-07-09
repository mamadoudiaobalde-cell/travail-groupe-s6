<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
<<<<<<< HEAD
        // Vérifier si l'utilisateur est connecté
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        // Récupérer le rôle de l'utilisateur
        $userRole = Auth::user()->role;

        // Vérifier si le rôle de l'utilisateur est dans la liste autorisée
        if (!in_array($userRole, $roles)) {
            abort(403, 'Accès non autorisé. Vous n\'avez pas les droits nécessaires.');
=======
        if (! Auth::check()) {
            return response()->json(['message' => 'Non authentifié.'], 401);
        }

        if (! in_array(Auth::user()->role, $roles, true)) {
            return response()->json(['message' => 'Accès non autorisé.'], 403);
>>>>>>> origin/ibrahimadev
        }

        return $next($request);
    }
}