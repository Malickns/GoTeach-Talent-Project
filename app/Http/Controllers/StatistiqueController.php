<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Jeune;
use App\Models\Employeur;
use App\Models\OffreEmplois;
use App\Models\Postulation;
use App\Models\Document;
use App\Models\Programme;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class StatistiqueController extends Controller
{
    /**
     * Obtenir les statistiques générales pour les admins
     */
    public function statistiquesGenerales()
    {
        $stats = [
            'jeunes_inscrits' => $this->getJeunesInscrits(),
            'entreprises_partenaires' => $this->getEntreprisesPartenaires(),
            'evenements_mois' => $this->getEvenementsMois(),
            'taux_activite' => $this->getTauxActivite(),
            'offres_actives' => $this->getOffresActives(),
            'candidatures_total' => $this->getCandidaturesTotal(),
            'embauches_total' => $this->getEmbauchesTotal(),
            'taux_reponse' => $this->getTauxReponse(),
        ];

        return response()->json($stats);
    }

    /**
     * Obtenir les statistiques pour un employeur spécifique
     */
    public function statistiquesEmployeur($employeurId = null)
    {
        // Si aucun employeurId n'est fourni, on récupère celui de l'utilisateur connecté
        if ($employeurId === null) {
            $user = Auth::user();
            if (!$user || !$user->isEmployeur()) {
                return response()->json(['error' => 'Utilisateur non autorisé'], 403);
            }
            
            $employeur = $user->employeur;
            if (!$employeur) {
                return response()->json(['error' => 'Profil employeur non trouvé'], 404);
            }
            
            $employeurId = $employeur->employeur_id;
        }
        
        $stats = [
            'offres_publiees' => $this->getOffresPublieesEmployeur($employeurId),
            'candidatures_recues' => $this->getCandidaturesRecuesEmployeur($employeurId),
            'talents_contactes' => $this->getTalentsContactesEmployeur($employeurId),
            'entretiens' => $this->getEntretiensEmployeur($employeurId),
            'vues_profil' => $this->getVuesProfilEmployeur($employeurId),
            'taux_reponse' => $this->getTauxReponseEmployeur($employeurId),
            'embauches' => $this->getEmbauchesEmployeur($employeurId),
            'offres_actives' => $this->getOffresActivesEmployeur($employeurId),
        ];

        return response()->json($stats);
    }

    /**
     * Obtenir les statistiques pour un jeune spécifique
     */
    public function statistiquesJeune($jeuneId = null)
    {
        // Si aucun jeuneId n'est fourni, on récupère celui de l'utilisateur connecté
        if ($jeuneId === null) {
            $user = Auth::user();
            if (!$user || !$user->isJeune()) {
                return response()->json(['error' => 'Utilisateur non autorisé'], 403);
            }
            
            $jeune = $user->jeune;
            if (!$jeune) {
                return response()->json(['error' => 'Profil jeune non trouvé'], 404);
            }
            
            $jeuneId = $jeune->jeune_id;
        }
        
        $stats = [
            'total_candidatures' => $this->getCandidaturesJeune($jeuneId),
            'entretiens' => $this->getEntretiensJeune($jeuneId),
            'emplois_obtenus' => $this->getEmploisObtenusJeune($jeuneId),
            'offres_disponibles' => $this->getOffresDisponibles(),
            'taux_reponse' => $this->getTauxReponseJeune($jeuneId),
            'derniere_candidature' => $this->getDerniereCandidatureJeune($jeuneId),
        ];

        return response()->json($stats);
    }

    /**
     * Obtenir les statistiques locales pour les admins locaux
     */
    public function statistiquesLocales($region = null)
    {
        $stats = [
            'jeunes_inscrits' => $this->getJeunesInscritsRegion($region),
            'entreprises_partenaires' => $this->getEntreprisesPartenairesRegion($region),
            'evenements_mois' => $this->getEvenementsMoisRegion($region),
            'offres_actives' => $this->getOffresActivesRegion($region),
            'candidatures_mois' => $this->getCandidaturesMoisRegion($region),
            'embauches_mois' => $this->getEmbauchesMoisRegion($region),
        ];

        return response()->json($stats);
    }

    /**
     * Obtenir les statistiques nationales pour les admins nationaux
     */
    public function statistiquesNationales()
    {
        $stats = [
            'jeunes_inscrits' => $this->getJeunesInscrits(),
            'entreprises' => $this->getEntreprisesPartenaires(),
            'evenements_mois' => $this->getEvenementsMois(),
            'taux_activite' => $this->getTauxActivite(),
            'regions_actives' => $this->getRegionsActives(),
            'secteurs_populaires' => $this->getSecteursPopulaires(),
            'evolution_mensuelle' => $this->getEvolutionMensuelle(),
        ];

        return response()->json($stats);
    }

    // ==================== MÉTHODES PRIVÉES ====================

    /**
     * Nombre de jeunes inscrits
     */
    private function getJeunesInscrits($region = null)
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
    private function getEntreprisesPartenaires($region = null)
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
     * Nombre d'événements ce mois (placeholder - à implémenter selon vos besoins)
     */
    private function getEvenementsMois($region = null)
    {
        // Pour l'instant, retourne un nombre basé sur les activités récentes
        $debutMois = Carbon::now()->startOfMonth();
        $finMois = Carbon::now()->endOfMonth();
        
        $activites = DB::table('users')
            ->where('derniere_activite', '>=', $debutMois)
            ->where('derniere_activite', '<=', $finMois)
            ->count();
        
        return $activites > 0 ? $activites : 0;
    }

    /**
     * Taux d'activité (pourcentage d'utilisateurs actifs ce mois)
     */
    private function getTauxActivite()
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
    private function getOffresActives($region = null)
    {
        $query = OffreEmplois::visible();

        if ($region) {
            $query->where('ville_travail', 'like', "%{$region}%");
        }

        return $query->count();
    }

    /**
     * Nombre total de candidatures
     */
    private function getCandidaturesTotal()
    {
        return Postulation::count();
    }

    /**
     * Nombre total d'embauches
     */
    private function getEmbauchesTotal()
    {
        return Postulation::where('statut', 'embauche')->count();
    }

    /**
     * Taux de réponse global
     */
    private function getTauxReponse()
    {
        $totalCandidatures = Postulation::count();
        $candidaturesTraitees = Postulation::whereIn('statut', ['retenu', 'rejete', 'embauche'])->count();
        
        return $totalCandidatures > 0 ? round(($candidaturesTraitees / $totalCandidatures) * 100, 1) : 0;
    }

    // ==================== STATISTIQUES EMPLOYEUR ====================

    /**
     * Offres publiées par un employeur
     */
    private function getOffresPublieesEmployeur($employeurId)
    {
        return OffreEmplois::where('employeur_id', $employeurId)->count();
    }

    /**
     * Candidatures reçues par un employeur
     */
    private function getCandidaturesRecuesEmployeur($employeurId)
    {
        return Postulation::whereHas('offreEmplois', function($q) use ($employeurId) {
            $q->where('employeur_id', $employeurId);
        })->count();
    }

    /**
     * Talents contactés par un employeur
     */
    private function getTalentsContactesEmployeur($employeurId)
    {
        return Postulation::whereHas('offreEmplois', function($q) use ($employeurId) {
            $q->where('employeur_id', $employeurId);
        })->distinct('jeune_id')->count();
    }

    /**
     * Entretiens effectués par un employeur
     */
    private function getEntretiensEmployeur($employeurId)
    {
        return Postulation::whereHas('offreEmplois', function($q) use ($employeurId) {
            $q->where('employeur_id', $employeurId);
        })->whereIn('statut', ['entretien_programme', 'entretien_effectue'])->count();
    }

    /**
     * Vues de profil d'un employeur (placeholder - à implémenter selon vos besoins)
     */
    private function getVuesProfilEmployeur($employeurId)
    {
        // Pour l'instant, retourne un nombre basé sur les candidatures
        $candidatures = Postulation::whereHas('offreEmplois', function($q) use ($employeurId) {
            $q->where('employeur_id', $employeurId);
        })->count();
        
        return $candidatures > 0 ? $candidatures * 3 : 0; // Estimation
    }

    /**
     * Taux de réponse d'un employeur
     */
    private function getTauxReponseEmployeur($employeurId)
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
    private function getEmbauchesEmployeur($employeurId)
    {
        return Postulation::whereHas('offreEmplois', function($q) use ($employeurId) {
            $q->where('employeur_id', $employeurId);
        })->where('statut', 'embauche')->count();
    }

    /**
     * Offres actives d'un employeur
     */
    private function getOffresActivesEmployeur($employeurId)
    {
        return OffreEmplois::where('employeur_id', $employeurId)
            ->visible()
            ->count();
    }

    // ==================== STATISTIQUES JEUNE ====================

    /**
     * Candidatures d'un jeune
     */
    private function getCandidaturesJeune($jeuneId)
    {
        $jeune = Jeune::where('jeune_id', $jeuneId)->first();
        if (!$jeune) return 0;
        
        // Calculer dynamiquement à partir des postulations
        return $jeune->postulations()->count();
    }

    /**
     * Entretiens d'un jeune
     */
    private function getEntretiensJeune($jeuneId)
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
    private function getEmploisObtenusJeune($jeuneId)
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
    private function getOffresDisponibles()
    {
        return OffreEmplois::visible()->count();
    }

    /**
     * Taux de réponse d'un jeune
     */
    private function getTauxReponseJeune($jeuneId)
    {
        $jeune = Jeune::where('jeune_id', $jeuneId)->first();
        if (!$jeune) return 0;
        
        $totalCandidatures = $jeune->postulations()->count();
        $candidaturesTraitees = $jeune->postulations()
            ->whereIn('statut', ['retenu', 'rejete', 'embauche', 'entretien_programme', 'entretien_effectue'])
            ->count();
        
        return $totalCandidatures > 0 ? round(($candidaturesTraitees / $totalCandidatures) * 100, 1) : 0;
    }

    /**
     * Dernière candidature d'un jeune
     */
    private function getDerniereCandidatureJeune($jeuneId)
    {
        $jeune = Jeune::where('jeune_id', $jeuneId)->first();
        if (!$jeune) return null;
        
        // Récupérer la dernière candidature
        $dernierePostulation = $jeune->postulations()
            ->orderBy('created_at', 'desc')
            ->first();
        
        return $dernierePostulation ? $dernierePostulation->created_at : null;
    }

    // ==================== STATISTIQUES RÉGIONALES ====================

    /**
     * Jeunes inscrits par région
     */
    private function getJeunesInscritsRegion($region)
    {
        return $this->getJeunesInscrits($region);
    }

    /**
     * Entreprises partenaires par région
     */
    private function getEntreprisesPartenairesRegion($region)
    {
        return $this->getEntreprisesPartenaires($region);
    }

    /**
     * Événements du mois par région
     */
    private function getEvenementsMoisRegion($region)
    {
        return $this->getEvenementsMois($region);
    }

    /**
     * Offres actives par région
     */
    private function getOffresActivesRegion($region)
    {
        return $this->getOffresActives($region);
    }

    /**
     * Candidatures du mois par région
     */
    private function getCandidaturesMoisRegion($region)
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
     * Embauches du mois par région
     */
    private function getEmbauchesMoisRegion($region)
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

    // ==================== STATISTIQUES NATIONALES ====================

    /**
     * Régions les plus actives
     */
    private function getRegionsActives()
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
    private function getSecteursPopulaires()
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
    private function getEvolutionMensuelle()
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
