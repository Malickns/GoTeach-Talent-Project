<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Jobs\EnregistrerVueOffre as EnregistrerVueOffreJob;
use Illuminate\Support\Facades\Log;

class EnregistrerVueOffreMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response|\Illuminate\Http\JsonResponse)  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        // Vérifier si c'est une route de consultation d'offre
        if ($this->isConsultationOffre($request)) {
            $this->enregistrerVueOffre($request);
        }

        return $response;
    }

    /**
     * Vérifier si la requête est pour la consultation d'une offre
     */
    private function isConsultationOffre(Request $request)
    {
        return $request->routeIs('jeunes.offres.show') && 
               $request->route('offre') && 
               $request->isMethod('GET');
    }

    /**
     * Enregistrer la vue de l'offre
     */
    private function enregistrerVueOffre(Request $request)
    {
        try {
            $offre = $request->route('offre');
            $jeuneId = null;

            // Récupérer l'ID du jeune si connecté
            if (Auth::check() && Auth::user()->isJeune()) {
                $jeune = Auth::user()->jeune;
                if ($jeune) {
                    $jeuneId = $jeune->jeune_id;
                }
            }

            // Dispatcher le job en arrière-plan pour ne pas ralentir la réponse
            EnregistrerVueOffreJob::dispatch(
                $offre->offre_id,
                $jeuneId,
                $request->ip(),
                $request->userAgent()
            )->onQueue('offre_vues');

        } catch (\Exception $e) {
            Log::error('Erreur dans le middleware EnregistrerVueOffreMiddleware', [
                'offre_id' => $offre->offre_id ?? 'N/A',
                'user_id' => Auth::id(),
                'error' => $e->getMessage()
            ]);
        }
    }
}
