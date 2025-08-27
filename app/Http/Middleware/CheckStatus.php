<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class CheckStatus
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $status): Response
    {
        if (!Auth::check()) {
            return redirect('/login');
        }

        $user = Auth::user();
        
        // Si un statut spécifique est requis (ex: "actif") et que l'utilisateur ne l'a pas
        if ($user->statut !== $status) {
            // Déterminer le message selon le statut actuel de l'utilisateur
            $message = 'Accès non autorisé.';
            if ($status === 'actif') {
                switch ($user->statut) {
                    case 'inactif':
                        $message = 'Votre compte n\'est pas encore activé. Veuillez attendre la validation par un administrateur.';
                        break;
                    case 'suspendu':
                        $message = 'Votre compte a été suspendu. Veuillez contacter l\'administrateur.';
                        break;
                    case 'supprime':
                        $message = 'Votre compte a été supprimé.';
                        break;
                    default:
                        $message = 'Votre compte n\'est pas actif. Veuillez contacter l\'administrateur.';
                        break;
                }
            }

            // Déconnecter l'utilisateur et rediriger vers l'accueil avec un message (SweetAlert côté front)
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            return redirect('/')->with('error', $message);
        }

        return $next($request);
    }
} 