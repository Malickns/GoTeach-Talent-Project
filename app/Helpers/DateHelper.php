<?php

namespace App\Helpers;

use Carbon\Carbon;

class DateHelper
{
    /**
     * Formater une date de manière sécurisée
     */
    public static function formatDate($date, $format = 'd/m/Y', $default = 'Non spécifiée')
    {
        if (!$date) {
            return $default;
        }

        try {
            if ($date instanceof Carbon) {
                return $date->format($format);
            }

            if (is_string($date)) {
                $carbonDate = Carbon::parse($date);
                return $carbonDate->format($format);
            }

            return $default;
        } catch (\Exception $e) {
            return $default;
        }
    }

    /**
     * Formater une date avec fallback intelligent
     */
    public static function formatDateIntelligente($date, $format = 'd/m/Y')
    {
        if (!$date) {
            return 'Non spécifiée';
        }

        try {
            if ($date instanceof Carbon) {
                return $date->format($format);
            }

            if (is_string($date)) {
                $carbonDate = Carbon::parse($date);
                return $carbonDate->format($format);
            }

            return 'Format invalide';
        } catch (\Exception $e) {
            return 'Date invalide';
        }
    }

    /**
     * Vérifier si une date est valide
     */
    public static function dateValide($date)
    {
        if (!$date) {
            return false;
        }

        try {
            if ($date instanceof Carbon) {
                return true;
            }

            if (is_string($date)) {
                Carbon::parse($date);
                return true;
            }

            return false;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Obtenir la différence humaine d'une date
     */
    public static function differenceHumaine($date, $default = 'Date inconnue')
    {
        if (!$date) {
            return $default;
        }

        try {
            if ($date instanceof Carbon) {
                return $date->diffForHumans();
            }

            if (is_string($date)) {
                $carbonDate = Carbon::parse($date);
                return $carbonDate->diffForHumans();
            }

            return $default;
        } catch (\Exception $e) {
            return $default;
        }
    }

    /**
     * Formater une date pour l'affichage dans les formulaires
     */
    public static function formatDateForm($date, $format = 'Y-m-d')
    {
        if (!$date) {
            return '';
        }

        try {
            if ($date instanceof Carbon) {
                return $date->format($format);
            }

            if (is_string($date)) {
                $carbonDate = Carbon::parse($date);
                return $carbonDate->format($format);
            }

            return '';
        } catch (\Exception $e) {
            return '';
        }
    }

    /**
     * Vérifier si une date est dans le passé
     */
    public static function estDansLePasse($date)
    {
        if (!$date) {
            return false;
        }

        try {
            if ($date instanceof Carbon) {
                return $date->isPast();
            }

            if (is_string($date)) {
                $carbonDate = Carbon::parse($date);
                return $carbonDate->isPast();
            }

            return false;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Vérifier si une date est dans le futur
     */
    public static function estDansLeFutur($date)
    {
        if (!$date) {
            return false;
        }

        try {
            if ($date instanceof Carbon) {
                return $date->isFuture();
            }

            if (is_string($date)) {
                $carbonDate = Carbon::parse($date);
                return $carbonDate->isFuture();
            }

            return false;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Obtenir l'âge à partir d'une date de naissance
     */
    public static function calculerAge($dateNaissance)
    {
        if (!$dateNaissance) {
            return null;
        }

        try {
            if ($dateNaissance instanceof Carbon) {
                return $dateNaissance->age;
            }

            if (is_string($dateNaissance)) {
                $carbonDate = Carbon::parse($dateNaissance);
                return $carbonDate->age;
            }

            return null;
        } catch (\Exception $e) {
            return null;
        }
    }
}
