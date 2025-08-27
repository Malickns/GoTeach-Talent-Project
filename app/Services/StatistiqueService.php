<?php

namespace App\Services;

use App\Models\User;
use App\Models\Jeune;
use App\Models\Employeur;
use App\Models\OffreEmplois;
use App\Models\Postulation;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class StatistiqueService
{
    /**
     * Obtenir les statistiques pour le dashboard employeur
     */
    public static function getStatistiquesEmployeur($employeurId = null)
    {
        // Si aucun employeurId n'est fourni, on récupère celui de l'utilisateur connecté
        if ($employeurId === null) {
            $user = Auth::user();
            if (!$user || !$user->isEmployeur()) {
                return self::getStatistiquesVides();
            }
            
            $employeur = $user->employeur;
            if (!$employeur) {
                return self::getStatistiquesVides();
            }
            
            $employeurId = $employeur->employeur_id;
        }
        
        return [
            'offres_publiees' => self::getOffresPublieesEmployeur($employeurId),
            'candidatures_recues' => self::getCandidaturesRecuesEmployeur($employeurId),
            'talents_contactes' => self::getTalentsContactesEmployeur($employeurId),
            'entretiens' => self::getEntretiensEmployeur($employeurId),
            'vues_profil' => self::getVuesProfilEmployeur($employeurId),
            'taux_reponse' => self::getTauxReponseEmployeur($employeurId),
            'embauches' => self::getEmbauchesEmployeur($employeurId),
            'offres_actives' => self::getOffresActivesEmployeur($employeurId),
        ];
    }

    /**
     * Obtenir les statistiques pour le dashboard jeune
     */
    public static function getStatistiquesJeune($jeuneId = null)
    {
        // Si aucun jeuneId n'est fourni, on récupère celui de l'utilisateur connecté
        if ($jeuneId === null) {
            $user = Auth::user();
            if (!$user || !$user->isJeune()) {
                return self::getStatistiquesVidesJeune();
            }
            
            $jeune = $user->jeune;
            if (!$jeune) {
                return self::getStatistiquesVidesJeune();
            }
            
            $jeuneId = $jeune->jeune_id;
        }
        
        return [
            'total_candidatures' => self::getCandidaturesJeune($jeuneId),
            'entretiens' => self::getEntretiensJeune($jeuneId),
            'emplois_obtenus' => self::getEmploisObtenusJeune($jeuneId),
            'offres_disponibles' => self::getOffresDisponibles(),
            'taux_reponse' => self::getTauxReponseJeune($jeuneId),
        ];
    }

    /**
     * Obtenir les statistiques pour le dashboard admin local
     */
    public static function getStatistiquesLocales($region = null)
    {
        return [
            'jeunes_inscrits' => self::getJeunesInscrits($region),
            'entreprises_partenaires' => self::getEntreprisesPartenaires($region),
            'evenements_mois' => self::getEvenementsMois($region),
            'offres_actives' => self::getOffresActives($region),
            'candidatures_mois' => self::getCandidaturesMois($region),
            'embauches_mois' => self::getEmbauchesMois($region),
        ];
    }

    /**
     * Obtenir les statistiques pour le dashboard admin national
     */
    public static function getStatistiquesNationales()
    {
        return [
            'jeunes_inscrits' => self::getJeunesInscrits(),
            'entreprises' => self::getEntreprisesPartenaires(),
            'evenements_mois' => self::getEvenementsMois(),
            'taux_activite' => self::getTauxActivite(),
            'regions_actives' => self::getRegionsActives(),
            'secteurs_populaires' => self::getSecteursPopulaires(),
            'evolution_mensuelle' => self::getEvolutionMensuelle(),
        ];
    }

    /**
     * Retourner des statistiques vides en cas d'erreur
     */
    private static function getStatistiquesVides()
    {
        return [
            'offres_publiees' => 0,
            'candidatures_recues' => 0,
            'talents_contactes' => 0,
            'entretiens' => 0,
            'vues_profil' => 0,
            'taux_reponse' => 0,
            'embauches' => 0,
            'offres_actives' => 0,
        ];
    }

    /**
     * Retourner des statistiques vides pour les jeunes en cas d'erreur
     */
    private static function getStatistiquesVidesJeune()
    {
        return [
            'total_candidatures' => 0,
            'entretiens' => 0,
            'emplois_obtenus' => 0,
            'offres_disponibles' => 0,
            'taux_reponse' => 0,
        ];
    }

    // ==================== MÉTHODES PRIVÉES ====================

    /**
     * Nombre de jeunes inscrits
     */
    private static function getJeunesInscrits($region = null)
    {
        $query = Jeune::whereHas('user', function($q) {
            $q->where('statut', 'actif');
        });

        if ($region) {
            $query->where('region', $region);
        }

        return $query->count();
    }

    /**
     * Nombre d'entreprises partenaires
     */
    private static function getEntreprisesPartenaires($region = null)
    {
        $query = Employeur::whereHas('user', function($q) {
            $q->where('statut', 'actif');
        });

        if ($region) {
            $query->where('region_entreprise', $region);
        }

        return $query->count();
    }

    /**
     * Nombre d'événements ce mois
     */
    private static function getEvenementsMois($region = null)
    {
        $debutMois = Carbon::now()->startOfMonth();
        $finMois = Carbon::now()->endOfMonth();
        
        $activites = DB::table('users')
            ->where('derniere_activite', '>=', $debutMois)
            ->where('derniere_activite', '<=', $finMois)
            ->count();
        
        return $activites > 0 ? $activites : 0;
    }

    /**
     * Taux d'activité
     */
    private static function getTauxActivite()
    {
        $totalUsers = User::where('statut', 'actif')->count();
        $usersActifs = User::where('statut', 'actif')
            ->where('derniere_activite', '>=', Carbon::now()->subDays(30))
            ->count();
        
        return $totalUsers > 0 ? round(($usersActifs / $totalUsers) * 100, 1) : 0;
    }

    /**
     * Nombre d'offres actives
     */
    private static function getOffresActives($region = null)
    {
        $query = OffreEmplois::visible();

        if ($region) {
            $query->where('ville_travail', 'like', "%{$region}%");
        }

        return $query->count();
    }

    /**
     * Candidatures du mois
     */
    private static function getCandidaturesMois($region = null)
    {
        $debutMois = Carbon::now()->startOfMonth();
        $finMois = Carbon::now()->endOfMonth();
        
        $query = Postulation::whereBetween('created_at', [$debutMois, $finMois]);
        
        if ($region) {
            $query->whereHas('jeune', function($q) use ($region) {
                $q->where('region', $region);
            });
        }
        
        return $query->count();
    }

    /**
     * Embauches du mois
     */
    private static function getEmbauchesMois($region = null)
    {
        $debutMois = Carbon::now()->startOfMonth();
        $finMois = Carbon::now()->endOfMonth();
        
        $query = Postulation::where('statut', 'embauche')
            ->whereBetween('updated_at', [$debutMois, $finMois]);
        
        if ($region) {
            $query->whereHas('jeune', function($q) use ($region) {
                $q->where('region', $region);
            });
        }
        
        return $query->count();
    }

    // ==================== STATISTIQUES EMPLOYEUR ====================

    /**
     * Offres publiées par un employeur
     */
    private static function getOffresPublieesEmployeur($employeurId)
    {
        return OffreEmplois::where('employeur_id', $employeurId)->count();
    }

    /**
     * Candidatures reçues par un employeur
     */
    private static function getCandidaturesRecuesEmployeur($employeurId)
    {
        return Postulation::whereHas('offreEmplois', function($q) use ($employeurId) {
            $q->where('employeur_id', $employeurId);
        })->count();
    }

    /**
     * Talents contactés par un employeur
     */
    private static function getTalentsContactesEmployeur($employeurId)
    {
        return Postulation::whereHas('offreEmplois', function($q) use ($employeurId) {
            $q->where('employeur_id', $employeurId);
        })->distinct('jeune_id')->count();
    }

    /**
     * Entretiens effectués par un employeur
     */
    private static function getEntretiensEmployeur($employeurId)
    {
        return Postulation::whereHas('offreEmplois', function($q) use ($employeurId) {
            $q->where('employeur_id', $employeurId);
        })->whereIn('statut', ['entretien_programme', 'entretien_effectue'])->count();
    }

    /**
     * Vues de profil d'un employeur
     */
    private static function getVuesProfilEmployeur($employeurId)
    {
        $candidatures = Postulation::whereHas('offreEmplois', function($q) use ($employeurId) {
            $q->where('employeur_id', $employeurId);
        })->count();
        
        return $candidatures > 0 ? $candidatures * 3 : 0; // Estimation
    }

    /**
     * Taux de réponse d'un employeur
     */
    private static function getTauxReponseEmployeur($employeurId)
    {
        $totalCandidatures = Postulation::whereHas('offreEmplois', function($q) use ($employeurId) {
            $q->where('employeur_id', $employeurId);
        })->count();
        
        $candidaturesTraitees = Postulation::whereHas('offreEmplois', function($q) use ($employeurId) {
            $q->where('employeur_id', $employeurId);
        })->whereIn('statut', ['retenu', 'rejete', 'embauche'])->count();
        
        return $totalCandidatures > 0 ? round(($candidaturesTraitees / $totalCandidatures) * 100, 1) : 0;
    }

    /**
     * Embauches effectuées par un employeur
     */
    private static function getEmbauchesEmployeur($employeurId)
    {
        return Postulation::whereHas('offreEmplois', function($q) use ($employeurId) {
            $q->where('employeur_id', $employeurId);
        })->where('statut', 'embauche')->count();
    }

    /**
     * Offres actives d'un employeur
     */
    private static function getOffresActivesEmployeur($employeurId)
    {
        return OffreEmplois::where('employeur_id', $employeurId)
            ->visible()
            ->count();
    }

    // ==================== STATISTIQUES JEUNE ====================

    /**
     * Candidatures d'un jeune
     */
    private static function getCandidaturesJeune($jeuneId)
    {
        $jeune = Jeune::where('jeune_id', $jeuneId)->first();
        if (!$jeune) return 0;
        
        // Calculer dynamiquement à partir des postulations
        return $jeune->postulations()->count();
    }

    /**
     * Entretiens d'un jeune
     */
    private static function getEntretiensJeune($jeuneId)
    {
        $jeune = Jeune::where('jeune_id', $jeuneId)->first();
        if (!$jeune) return 0;
        
        // Calculer dynamiquement à partir des postulations avec statut entretien
        return $jeune->postulations()
            ->whereIn('statut', ['entretien_programme', 'entretien_effectue'])
            ->count();
    }

    /**
     * Emplois obtenus par un jeune
     */
    private static function getEmploisObtenusJeune($jeuneId)
    {
        $jeune = Jeune::where('jeune_id', $jeuneId)->first();
        if (!$jeune) return 0;
        
        // Calculer dynamiquement à partir des postulations avec statut embauche
        return $jeune->postulations()
            ->where('statut', 'embauche')
            ->count();
    }

    /**
     * Offres disponibles
     */
    private static function getOffresDisponibles()
    {
        return OffreEmplois::visible()->count();
    }

    /**
     * Taux de réponse d'un jeune
     */
    private static function getTauxReponseJeune($jeuneId)
    {
        $jeune = Jeune::where('jeune_id', $jeuneId)->first();
        if (!$jeune) return 0;
        
        $totalCandidatures = $jeune->postulations()->count();
        $candidaturesTraitees = $jeune->postulations()
            ->whereIn('statut', ['retenu', 'rejete', 'embauche', 'entretien_programme', 'entretien_effectue'])
            ->count();
        
        return $totalCandidatures > 0 ? round(($candidaturesTraitees / $totalCandidatures) * 100, 1) : 0;
    }

    // ==================== STATISTIQUES NATIONALES ====================

    /**
     * Régions les plus actives
     */
    private static function getRegionsActives()
    {
        return Jeune::select('region', DB::raw('count(*) as total'))
            ->groupBy('region')
            ->orderBy('total', 'desc')
            ->limit(5)
            ->get();
    }

    /**
     * Secteurs les plus populaires
     */
    private static function getSecteursPopulaires()
    {
        return OffreEmplois::select('secteur_activite', DB::raw('count(*) as total'))
            ->whereNotNull('secteur_activite')
            ->groupBy('secteur_activite')
            ->orderBy('total', 'desc')
            ->limit(5)
            ->get();
    }

    /**
     * Évolution mensuelle
     */
    private static function getEvolutionMensuelle()
    {
        $mois = [];
        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $mois[] = [
                'mois' => $date->format('M Y'),
                'jeunes' => Jeune::whereMonth('created_at', $date->month)
                    ->whereYear('created_at', $date->year)
                    ->count(),
                'offres' => OffreEmplois::whereMonth('created_at', $date->month)
                    ->whereYear('created_at', $date->year)
                    ->count(),
                'candidatures' => Postulation::whereMonth('created_at', $date->month)
                    ->whereYear('created_at', $date->year)
                    ->count(),
            ];
        }
        
        return $mois;
    }
}
