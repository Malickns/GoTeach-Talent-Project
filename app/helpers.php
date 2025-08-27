<?php

use Carbon\Carbon;

if (!function_exists('format_date')) {
    function format_date($date, $format = 'd/m/Y', $default = 'Non spécifiée')
    {
        if (!$date) return $default;
        try {
            if ($date instanceof Carbon) return $date->format($format);
            if (is_string($date)) return Carbon::parse($date)->format($format);
            return $default;
        } catch (\Exception $e) { return $default; }
    }
}

if (!function_exists('safe_array_get')) {
    function safe_array_get($array, $key, $default = null)
    {
        return is_array($array) && array_key_exists($key, $array) ? $array[$key] : $default;
    }
}
