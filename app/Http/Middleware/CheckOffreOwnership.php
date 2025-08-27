<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use App\Models\OffreEmplois;

class CheckOffreOwnership
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();
        
        // Si l'utilisateur n'est pas connecté
        if (!$user) {
            return redirect('/login');
        }

        // Si c'est un admin, laisser passer
        if ($user->isAdmin()) {
            return $next($request);
        }

        // Si c'est un employeur, vérifier qu'il possède l'offre
        if ($user->isEmployeur()) {
            $offreId = $request->route('offreEmplois');
            
            if ($offreId) {
                $offre = OffreEmplois::find($offreId);
                
                if (!$offre) {
                    abort(404, 'Offre d\'emploi non trouvée.');
                }

                $employeur = $user->employeur;
                if (!$employeur || $offre->employeur_id !== $employeur->employeur_id) {
                    abort(403, 'Vous ne pouvez modifier que vos propres offres d\'emploi.');
                }
            }
        }

        return $next($request);
    }
} 