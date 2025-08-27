<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Request;

class OffreVue extends Model
{
    protected $table = 'offre_vues';
    protected $primaryKey = 'vue_id';

    protected $fillable = [
        'offre_id',
        'jeune_id',
        'ip',
        'user_agent',
        'vue_le',
    ];

    protected $casts = [
        'vue_le' => 'datetime',
    ];

    /**
     * Relation avec l'offre d'emploi
     */
    public function offre()
    {
        return $this->belongsTo(OffreEmplois::class, 'offre_id', 'offre_id');
    }

    /**
     * Relation avec le jeune
     */
    public function jeune()
    {
        return $this->belongsTo(Jeune::class, 'jeune_id', 'jeune_id');
    }

    /**
     * Enregistrer une nouvelle vue d'offre
     */
    public static function enregistrerVue($offreId, $jeuneId = null)
    {
        try {
            return self::create([
                'offre_id' => $offreId,
                'jeune_id' => $jeuneId,
                'ip' => Request::ip(),
                'user_agent' => Request::userAgent(),
                'vue_le' => now(),
            ]);
        } catch (\Exception $e) {
            \Log::error('Erreur lors de l\'enregistrement de la vue d\'offre', [
                'offre_id' => $offreId,
                'jeune_id' => $jeuneId,
                'error' => $e->getMessage()
            ]);
            return null;
        }
    }

    /**
     * Vérifier si un jeune a déjà vu une offre récemment (dans les dernières 24h)
     */
    public static function jeuneAVuOffre($offreId, $jeuneId)
    {
        if (!$jeuneId) return false;
        
        return self::where('offre_id', $offreId)
            ->where('jeune_id', $jeuneId)
            ->where('vue_le', '>=', now()->subDay())
            ->exists();
    }

    /**
     * Obtenir le nombre total de vues pour une offre
     */
    public static function nombreVuesOffre($offreId)
    {
        return self::where('offre_id', $offreId)->count();
    }

    /**
     * Obtenir le nombre de vues uniques pour une offre (par jeune)
     */
    public static function nombreVuesUniquesOffre($offreId)
    {
        return self::where('offre_id', $offreId)
            ->whereNotNull('jeune_id')
            ->distinct('jeune_id')
            ->count();
    }

    /**
     * Scope pour les vues récentes (dernières 24h)
     */
    public function scopeRecentes($query)
    {
        return $query->where('vue_le', '>=', now()->subDay());
    }

    /**
     * Scope pour les vues d'une offre spécifique
     */
    public function scopePourOffre($query, $offreId)
    {
        return $query->where('offre_id', $offreId);
    }

    /**
     * Scope pour les vues d'un jeune spécifique
     */
    public function scopePourJeune($query, $jeuneId)
    {
        return $query->where('jeune_id', $jeuneId);
    }
}


