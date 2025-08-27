<?php

namespace App\Helpers;

use App\Services\StatistiqueService;

class StatistiqueHelper
{
    /**
     * Obtenir les statistiques formatées pour l'affichage
     */
    public static function getStatistiquesFormatees($type = 'employeur', $id = null)
    {
        switch ($type) {
            case 'employeur':
                return self::formatStatistiquesEmployeur(StatistiqueService::getStatistiquesEmployeur($id));
            case 'jeune':
                return self::formatStatistiquesJeune(StatistiqueService::getStatistiquesJeune($id));
            case 'local':
                return self::formatStatistiquesLocales(StatistiqueService::getStatistiquesLocales($id));
            case 'national':
                return self::formatStatistiquesNationales(StatistiqueService::getStatistiquesNationales());
            default:
                return [];
        }
    }

    /**
     * Formater les statistiques employeur
     */
    private static function formatStatistiquesEmployeur($stats)
    {
        return [
            'offres_publiees' => number_format($stats['offres_publiees']),
            'candidatures_recues' => number_format($stats['candidatures_recues']),
            'talents_contactes' => number_format($stats['talents_contactes']),
            'entretiens' => number_format($stats['entretiens']),
            'vues_profil' => number_format($stats['vues_profil']),
            'taux_reponse' => $stats['taux_reponse'],
            'embauches' => number_format($stats['embauches']),
            'offres_actives' => number_format($stats['offres_actives']),
        ];
    }

    /**
     * Formater les statistiques jeune
     */
    private static function formatStatistiquesJeune($stats)
    {
        return [
            'total_candidatures' => number_format($stats['total_candidatures']),
            'entretiens' => number_format($stats['entretiens']),
            'emplois_obtenus' => number_format($stats['emplois_obtenus']),
            'offres_disponibles' => number_format($stats['offres_disponibles']),
            'taux_reponse' => $stats['taux_reponse'],
        ];
    }

    /**
     * Formater les statistiques locales
     */
    private static function formatStatistiquesLocales($stats)
    {
        return [
            'jeunes_inscrits' => number_format($stats['jeunes_inscrits']),
            'entreprises_partenaires' => number_format($stats['entreprises_partenaires']),
            'evenements_mois' => number_format($stats['evenements_mois']),
            'offres_actives' => number_format($stats['offres_actives']),
            'candidatures_mois' => number_format($stats['candidatures_mois']),
            'embauches_mois' => number_format($stats['embauches_mois']),
        ];
    }

    /**
     * Formater les statistiques nationales
     */
    private static function formatStatistiquesNationales($stats)
    {
        return [
            'jeunes_inscrits' => number_format($stats['jeunes_inscrits']),
            'entreprises' => number_format($stats['entreprises']),
            'evenements_mois' => number_format($stats['evenements_mois']),
            'taux_activite' => $stats['taux_activite'],
            'regions_actives' => $stats['regions_actives'] ?? collect(),
            'secteurs_populaires' => $stats['secteurs_populaires'] ?? collect(),
        ];
    }

    /**
     * Obtenir une statistique spécifique
     */
    public static function getStatistique($type, $key, $id = null)
    {
        $stats = self::getStatistiquesFormatees($type, $id);
        return $stats[$key] ?? 0;
    }

    /**
     * Formater un nombre pour l'affichage
     */
    public static function formatNombre($nombre)
    {
        if ($nombre >= 1000000) {
            return round($nombre / 1000000, 1) . 'M';
        } elseif ($nombre >= 1000) {
            return round($nombre / 1000, 1) . 'K';
        }
        return number_format($nombre);
    }

    /**
     * Formater un pourcentage
     */
    public static function formatPourcentage($valeur, $total)
    {
        if ($total == 0) return 0;
        return round(($valeur / $total) * 100, 1);
    }

    /**
     * Obtenir la couleur CSS selon la valeur
     */
    public static function getCouleurStatistique($valeur, $seuil = 50)
    {
        if ($valeur >= $seuil) {
            return 'text-green-600';
        } elseif ($valeur >= $seuil * 0.7) {
            return 'text-yellow-600';
        } else {
            return 'text-red-600';
        }
    }
}
