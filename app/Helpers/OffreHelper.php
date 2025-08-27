<?php

namespace App\Helpers;

use App\Models\OffreEmplois;
use Carbon\Carbon;

class OffreHelper
{
    /**
     * Formate la durée d'un contrat
     */
    public static function formatDureeContrat($typeContrat, $dureeMois = null)
    {
        switch ($typeContrat) {
            case 'cdi':
                return 'CDI - Contrat à durée indéterminée';
            case 'cdd':
                return $dureeMois ? "CDD - {$dureeMois} mois" : 'CDD';
            case 'stage':
                return 'Stage';
            case 'formation':
                return 'Formation';
            case 'freelance':
                return 'Freelance';
            default:
                return ucfirst($typeContrat);
        }
    }

    /**
     * Vérifie si une offre est expirée
     */
    public static function isExpired($offre)
    {
        if (!$offre->date_expiration) {
            return false;
        }
        
        return Carbon::parse($offre->date_expiration)->isPast();
    }

    /**
     * Calcule le nombre de jours restants avant expiration
     */
    public static function joursRestants($offre)
    {
        if (!$offre->date_expiration) {
            return null;
        }
        
        $expiration = Carbon::parse($offre->date_expiration);
        $now = Carbon::now();
        
        return $now->diffInDays($expiration, false);
    }

    /**
     * Formate le niveau d'étude
     */
    // removed niveau etude formatting (field dropped)

    /**
     * Formate le type de contrat
     */
    public static function formatTypeContrat($type)
    {
        $types = config('offres.types_contrat');
        return $types[$type] ?? ucfirst($type);
    }

    /**
     * Génère un résumé d'une offre
     */
    public static function generateResume($offre)
    {
        $resume = [];
        
        $resume[] = self::formatTypeContrat($offre->type_contrat);
        $resume[] = $offre->ville_travail;
        
        // hours/experience removed in schema
        
        return implode(' • ', $resume);
    }

    /**
     * Vérifie si un utilisateur peut postuler à une offre
     */
    public static function canPostuler($user, $offre)
    {
        // Vérifier que l'utilisateur est un jeune
        if (!$user->isJeune()) {
            return false;
        }
        
        // Vérifier que l'offre n'est pas expirée
        return !self::isExpired($offre);
    }

    /**
     * Vérifie si un utilisateur peut modifier une offre
     */
    public static function canModifier($user, $offre)
    {
        // Les admins peuvent tout modifier
        if ($user->isAdmin()) {
            return true;
        }
        
        // Les employeurs ne peuvent modifier que leurs offres
        if ($user->isEmployeur()) {
            $employeur = $user->employeur;
            return $employeur && $offre->employeur_id === $employeur->employeur_id;
        }
        
        return false;
    }

    /**
     * Vérifie si un utilisateur peut supprimer une offre
     */
    public static function canSupprimer($user, $offre)
    {
        // Vérifier d'abord les permissions de modification
        if (!self::canModifier($user, $offre)) {
            return false;
        }
        
        // Vérifier qu'il n'y a pas de candidatures
        if ($offre->postulations()->count() > 0) {
            return false;
        }
        
        return true;
    }
} 