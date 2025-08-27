<?php

namespace App\Services;

use App\Models\OffreVue;
use App\Models\OffreEmplois;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class OffreVueService
{
    /**
     * Enregistrer une vue d'offre avec gestion intelligente
     */
    public static function enregistrerVue($offreId, $jeuneId = null, $force = false)
    {
        try {
            // Vérifier si on doit enregistrer la vue
            if (!$force && !self::doitEnregistrerVue($offreId, $jeuneId)) {
                return null;
            }

            // Enregistrer la vue
            $vue = OffreVue::create([
                'offre_id' => $offreId,
                'jeune_id' => $jeuneId,
                'ip' => Request::ip(),
                'user_agent' => Request::userAgent(),
                'vue_le' => now(),
            ]);

            // Mettre à jour le compteur de vues dans la table principale
            self::updateCompteurVues($offreId);

            // Invalider le cache des statistiques
            self::invaliderCacheStatistiques($offreId);

            // Log de la vue pour analytics
            self::loggerVue($offreId, $jeuneId);

            return $vue;

        } catch (\Exception $e) {
            Log::error('Erreur lors de l\'enregistrement de la vue d\'offre', [
                'offre_id' => $offreId,
                'jeune_id' => $jeuneId,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return null;
        }
    }

    /**
     * Déterminer si on doit enregistrer la vue
     */
    private static function doitEnregistrerVue($offreId, $jeuneId)
    {
        // Si c'est un jeune connecté, vérifier s'il a déjà vu récemment
        if ($jeuneId) {
            if (OffreVue::jeuneAVuOffre($offreId, $jeuneId)) {
                return false;
            }
        }

        // Vérifier le rate limiting par IP (max 1 vue par IP par heure pour éviter le spam)
        $cacheKey = "offre_vue_ip_{$offreId}_" . Request::ip();
        if (Cache::has($cacheKey)) {
            return false;
        }

        // Mettre en cache pour 1 heure
        Cache::put($cacheKey, true, now()->addHour());

        return true;
    }

    /**
     * Mettre à jour le compteur de vues dans la table principale
     */
    public static function updateCompteurVues($offreId)
    {
        try {
            $offre = OffreEmplois::find($offreId);
            if ($offre) {
                $offre->updateCompteurVues();
            }
        } catch (\Exception $e) {
            Log::error('Erreur lors de la mise à jour du compteur de vues', [
                'offre_id' => $offreId,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Invalider le cache des statistiques
     */
    private static function invaliderCacheStatistiques($offreId)
    {
        $cacheKeys = [
            "offre_stats_{$offreId}",
            "offre_vues_{$offreId}",
            "offre_vues_uniques_{$offreId}"
        ];

        foreach ($cacheKeys as $key) {
            Cache::forget($key);
        }
    }

    /**
     * Logger la vue pour analytics
     */
    private static function loggerVue($offreId, $jeuneId)
    {
        $data = [
            'offre_id' => $offreId,
            'jeune_id' => $jeuneId,
            'ip' => Request::ip(),
            'user_agent' => Request::userAgent(),
            'timestamp' => now()->toISOString(),
            'session_id' => Request::session()->getId(),
            'referer' => Request::header('referer'),
            'url' => Request::fullUrl(),
        ];

        Log::channel('offre_vues')->info('Nouvelle vue d\'offre', $data);
    }

    /**
     * Obtenir les statistiques de vues d'une offre avec cache
     */
    public static function getStatistiquesOffre($offreId, $useCache = true)
    {
        $cacheKey = "offre_stats_{$offreId}";

        if ($useCache && Cache::has($cacheKey)) {
            return Cache::get($cacheKey);
        }

        // Récupérer l'offre avec ses relations
        $offre = OffreEmplois::with(['postulations', 'vues'])->find($offreId);
        
        if (!$offre) {
            return [
                'total_vues' => 0,
                'vues_uniques' => 0,
                'vues_aujourd_hui' => 0,
                'vues_cette_semaine' => 0,
                'vues_ce_mois' => 0,
                'total_candidatures' => 0,
                'candidatures_en_attente' => 0,
                'candidatures_retenues' => 0,
                'vues_par_jour' => [],
                'vues_par_heure' => [],
            ];
        }

        $stats = [
            'total_vues' => $offre->vues()->count(),
            'vues_uniques' => $offre->vues()->whereNotNull('jeune_id')->distinct('jeune_id')->count(),
            'vues_aujourd_hui' => $offre->vues()->whereDate('vue_le', today())->count(),
            'vues_cette_semaine' => $offre->vues()
                ->whereBetween('vue_le', [now()->startOfWeek(), now()->endOfWeek()])
                ->count(),
            'vues_ce_mois' => $offre->vues()
                ->whereMonth('vue_le', now()->month)
                ->whereYear('vue_le', now()->year)
                ->count(),
            'total_candidatures' => $offre->postulations()->count(),
            'candidatures_en_attente' => $offre->postulations()->where('statut', 'en_attente')->count(),
            'candidatures_retenues' => $offre->postulations()->where('statut', 'retenu')->count(),
            'vues_par_jour' => self::getVuesParJour($offreId),
            'vues_par_heure' => self::getVuesParHeure($offreId),
        ];

        // Mettre en cache pour 15 minutes
        Cache::put($cacheKey, $stats, now()->addMinutes(15));

        return $stats;
    }

    /**
     * Obtenir les vues par jour pour une offre
     */
    private static function getVuesParJour($offreId)
    {
        return OffreVue::pourOffre($offreId)
            ->selectRaw('DATE(vue_le) as date, COUNT(*) as total')
            ->where('vue_le', '>=', now()->subDays(30))
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->pluck('total', 'date')
            ->toArray();
    }

    /**
     * Obtenir les vues par heure pour une offre (aujourd'hui)
     */
    private static function getVuesParHeure($offreId)
    {
        return OffreVue::pourOffre($offreId)
            ->selectRaw('HOUR(vue_le) as heure, COUNT(*) as total')
            ->whereDate('vue_le', today())
            ->groupBy('heure')
            ->orderBy('heure')
            ->get()
            ->pluck('total', 'heure')
            ->toArray();
    }

    /**
     * Obtenir les offres les plus vues
     */
    public static function getOffresPlusVues($limit = 10, $period = 'month')
    {
        $query = OffreVue::selectRaw('offre_id, COUNT(*) as total_vues')
            ->groupBy('offre_id')
            ->orderBy('total_vues', 'desc');

        switch ($period) {
            case 'week':
                $query->where('vue_le', '>=', now()->startOfWeek());
                break;
            case 'month':
                $query->where('vue_le', '>=', now()->startOfMonth());
                break;
            case 'year':
                $query->where('vue_le', '>=', now()->startOfYear());
                break;
        }

        return $query->limit($limit)->get();
    }

    /**
     * Obtenir les jeunes les plus actifs (qui consultent le plus d'offres)
     */
    public static function getJeunesPlusActifs($limit = 10, $period = 'month')
    {
        $query = OffreVue::selectRaw('jeune_id, COUNT(*) as total_vues')
            ->whereNotNull('jeune_id')
            ->groupBy('jeune_id')
            ->orderBy('total_vues', 'desc');

        switch ($period) {
            case 'week':
                $query->where('vue_le', '>=', now()->startOfWeek());
                break;
            case 'month':
                $query->where('vue_le', '>=', now()->startOfMonth());
                break;
            case 'year':
                $query->where('vue_le', '>=', now()->startOfYear());
                break;
        }

        return $query->limit($limit)->get();
    }

    /**
     * Nettoyer les anciennes vues (maintenance)
     */
    public static function nettoyerAnciennesVues($days = 365)
    {
        try {
            $deleted = OffreVue::where('vue_le', '<', now()->subDays($days))->delete();
            
            Log::info("Nettoyage des anciennes vues d'offres", [
                'deleted_count' => $deleted,
                'older_than_days' => $days
            ]);

            return $deleted;
        } catch (\Exception $e) {
            Log::error('Erreur lors du nettoyage des anciennes vues', [
                'error' => $e->getMessage()
            ]);
            return 0;
        }
    }
}
