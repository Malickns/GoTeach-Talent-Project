<?php

namespace App\Helpers;

class StatistiquesHelper
{
    /**
     * Structure par défaut des statistiques d'offre
     */
    private static $defaultStats = [
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

    /**
     * Valider et nettoyer les statistiques d'une offre
     */
    public static function validerStatistiques($stats)
    {
        if (!is_array($stats)) {
            return self::$defaultStats;
        }

        // Fusionner avec les valeurs par défaut pour s'assurer que toutes les clés existent
        $statsValides = array_merge(self::$defaultStats, $stats);

        // Valider que toutes les valeurs sont numériques ou des tableaux
        foreach ($statsValides as $key => $value) {
            if (in_array($key, ['vues_par_jour', 'vues_par_heure'])) {
                // Ces clés doivent être des tableaux
                if (!is_array($value)) {
                    $statsValides[$key] = [];
                }
            } else {
                // Les autres clés doivent être numériques
                if (!is_numeric($value)) {
                    $statsValides[$key] = 0;
                }
                // S'assurer que les valeurs sont positives
                $statsValides[$key] = max(0, (int) $value);
            }
        }

        return $statsValides;
    }

    /**
     * Formater les statistiques pour l'affichage
     */
    public static function formaterStatistiques($stats)
    {
        $statsValides = self::validerStatistiques($stats);

        return [
            'total_vues' => number_format($statsValides['total_vues']),
            'vues_uniques' => number_format($statsValides['vues_uniques']),
            'vues_aujourd_hui' => number_format($statsValides['vues_aujourd_hui']),
            'vues_cette_semaine' => number_format($statsValides['vues_cette_semaine']),
            'vues_ce_mois' => number_format($statsValides['vues_ce_mois']),
            'total_candidatures' => number_format($statsValides['total_candidatures']),
            'candidatures_en_attente' => number_format($statsValides['candidatures_en_attente']),
            'candidatures_retenues' => number_format($statsValides['candidatures_retenues']),
            'vues_par_jour' => $statsValides['vues_par_jour'],
            'vues_par_heure' => $statsValides['vues_par_heure'],
        ];
    }

    /**
     * Vérifier si les statistiques sont valides
     */
    public static function statistiquesValides($stats)
    {
        if (!is_array($stats)) {
            return false;
        }

        $requiredKeys = array_keys(self::$defaultStats);
        
        foreach ($requiredKeys as $key) {
            if (!array_key_exists($key, $stats)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Obtenir des statistiques d'erreur (fallback)
     */
    public static function getStatistiquesErreur()
    {
        return self::$defaultStats;
    }
}
