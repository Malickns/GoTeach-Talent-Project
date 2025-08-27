<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class RedirectBasedOnRoleAndStatus
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */

    /**
     * On redirige l'utilisateur en fonction de son rôle et de son statut.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // les status sont : 'inactif', 'actif', 'suspendu', 'supprime'
        // les rôles sont : 'jeune', 'employeur', 'admin_local', 'admin_national'
        /**
         * on cherche le role de l'utilisateur puis on vérifie son statut
         * si l'utilisateur est actif, on le redirige vers son tableau de bord
         * s'il est inactif, suspendu ou supprimé, on le redirige vers la page de welcome
         */

        if (Auth::check()) {
            $user = Auth::user();
            $role = $user->role;
            $statut = $user->statut;
            $currentRoute = $request->route()->getName();
            
            // Vérifier si l'utilisateur est déjà sur la bonne route
            $correctRoute = $this->getCorrectRoute($role);
            
            if ($statut === 'actif') {
                // Si l'utilisateur n'est pas sur la bonne route, le rediriger
                if ($currentRoute !== $correctRoute) {
                    return redirect()->route($correctRoute);
                }
            } else {
                // Utilisateur inactif, suspendu ou supprimé
                if ($currentRoute !== 'welcome') {
                    Auth::logout();
                    $request->session()->invalidate();
                    $request->session()->regenerateToken();
                    return redirect('/')->with('error', 'Votre compte n\'est pas actif. Veuillez contacter l\'administrateur.');
                }
            }
        }

        // Si l'utilisateur n'est pas authentifié, continuer la requête normalement
        return $next($request);
    }
    
    /**
     * Obtenir la route correcte selon le rôle
     */
    private function getCorrectRoute(string $role): string
    {
        switch ($role) {
            case 'jeune':
                return 'jeunes.dashboard';
            case 'employeur':
                return 'pages.employeurs.dashboard';
            case 'admin_local':
                return 'pages.admins.local.dashboard';
            case 'admin_national':
                return 'pages.admins.national.dashboard';
            default:
                return 'dashboard';
        }
    }
}

