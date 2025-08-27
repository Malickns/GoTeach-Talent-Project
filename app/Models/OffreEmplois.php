<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OffreEmplois extends Model
{
    use SoftDeletes;

    protected $table = 'offres_emploi';
    protected $primaryKey = 'offre_id';

    protected $fillable = [
        'employeur_id',
        'titre',
        'description',
        'missions_principales',
        'type_contrat',
        'duree_contrat_mois',
        'date_debut_contrat',
        'date_fin_contrat',
        'grade',
        'ville_travail',
        'competences_requises',
        'date_publication',
        'date_expiration',
        'offre_urgente',
        'nombre_vues',
        'nombre_candidatures',
    ];

    protected $casts = [
        'competences_requises' => 'array',
        'date_debut_contrat' => 'date',
        'date_fin_contrat' => 'date',
        'date_publication' => 'datetime',
        'date_expiration' => 'datetime',
        'offre_urgente' => 'boolean',
    ];

    /**
     * Relation avec l'employeur
     */
    public function employeur()
    {
        return $this->belongsTo(Employeur::class, 'employeur_id', 'employeur_id');
    }

    /**
     * Relation avec les postulations
     */
    public function postulations()
    {
        return $this->hasMany(Postulation::class, 'offre_id', 'offre_id');
    }

    /**
     * Relation avec les vues détaillées
     */
    public function vues()
    {
        return $this->hasMany(OffreVue::class, 'offre_id', 'offre_id');
    }

    /**
     * Scope pour filtrer les offres par employeur
     */
    public function scopeByEmployeur($query, $employeurId)
    {
        return $query->where('employeur_id', $employeurId);
    }

    /**
     * Scope pour les offres visibles publiquement
     */
    public function scopeVisible($query)
    {
        // Par défaut: une offre est visible si elle n'est pas expirée
        return $query->where(function ($q) {
            $q->whereNull('date_expiration')
              ->orWhere('date_expiration', '>', now());
        });
    }

    /**
     * Scope pour les offres urgentes
     */
    public function scopeUrgentes($query)
    {
        return $query->where('offre_urgente', true);
    }

    /**
     * Scope pour les offres récentes (dernières 30 jours)
     */
    public function scopeRecentes($query)
    {
        return $query->where('date_publication', '>=', now()->subDays(30));
    }

    /**
     * Scope pour les offres par ville
     */
    public function scopeParVille($query, $ville)
    {
        return $query->where('ville_travail', $ville);
    }

    /**
     * Scope pour les offres par type de contrat
     */
    public function scopeParTypeContrat($query, $type)
    {
        return $query->where('type_contrat', $type);
    }

    /**
     * Vérifier si l'offre est visible
     */
    public function isVisible()
    {
        if ($this->date_expiration) {
            return $this->date_expiration > now();
        }
        return true;
    }

    /**
     * Vérifier si l'offre est expirée
     */
    public function isExpiree()
    {
        if ($this->date_expiration) {
            return $this->date_expiration <= now();
        }
        return false;
    }

    /**
     * Obtenir le nombre total de vues (depuis la table offre_vues)
     */
    public function getNombreVuesTotalAttribute()
    {
        return $this->vues()->count();
    }

    /**
     * Obtenir le nombre de vues uniques (par jeune)
     */
    public function getNombreVuesUniquesAttribute()
    {
        return $this->vues()->whereNotNull('jeune_id')->distinct('jeune_id')->count();
    }

    /**
     * Obtenir le nombre de vues aujourd'hui
     */
    public function getNombreVuesAujourdhuiAttribute()
    {
        return $this->vues()->whereDate('vue_le', today())->count();
    }

    /**
     * Obtenir le nombre de vues cette semaine
     */
    public function getNombreVuesCetteSemaineAttribute()
    {
        return $this->vues()->whereBetween('vue_le', [now()->startOfWeek(), now()->endOfWeek()])->count();
    }

    /**
     * Obtenir le nombre de vues ce mois
     */
    public function getNombreVuesCeMoisAttribute()
    {
        return $this->vues()->whereMonth('vue_le', now()->month)->whereYear('vue_le', now()->year)->count();
    }

    /**
     * Mettre à jour le compteur de vues dans la table principale
     */
    public function updateCompteurVues()
    {
        $this->update([
            'nombre_vues' => $this->getNombreVuesTotalAttribute()
        ]);
    }

    /**
     * Mettre à jour le compteur de candidatures
     */
    public function updateCompteurCandidatures()
    {
        $this->update([
            'nombre_candidatures' => $this->postulations()->count()
        ]);
    }

    /**
     * Obtenir les statistiques de l'offre
     */
    public function getStatistiques()
    {
        try {
            return [
                'total_vues' => $this->getNombreVuesTotalAttribute(),
                'vues_uniques' => $this->getNombreVuesUniquesAttribute(),
                'vues_aujourd_hui' => $this->getNombreVuesAujourdhuiAttribute(),
                'vues_cette_semaine' => $this->getNombreVuesCetteSemaineAttribute(),
                'vues_ce_mois' => $this->getNombreVuesCeMoisAttribute(),
                'total_candidatures' => $this->postulations()->count(),
                'candidatures_en_attente' => $this->postulations()->where('statut', 'en_attente')->count(),
                'candidatures_retenues' => $this->postulations()->where('statut', 'retenu')->count(),
            ];
        } catch (\Exception $e) {
            \Log::error('Erreur lors de la récupération des statistiques de l\'offre', [
                'offre_id' => $this->offre_id,
                'error' => $e->getMessage()
            ]);
            
            // Retourner des statistiques par défaut en cas d'erreur
            return [
                'total_vues' => 0,
                'vues_uniques' => 0,
                'vues_aujourd_hui' => 0,
                'vues_cette_semaine' => 0,
                'vues_ce_mois' => 0,
                'total_candidatures' => 0,
                'candidatures_en_attente' => 0,
                'candidatures_retenues' => 0,
            ];
        }
    }

    /**
     * Obtenir les statistiques de l'offre avec fallback sécurisé
     */
    public function getStatistiquesSecurisees()
    {
        $stats = $this->getStatistiques();
        
        // S'assurer que toutes les clés existent avec des valeurs par défaut
        $defaults = [
            'total_vues' => 0,
            'vues_uniques' => 0,
            'vues_aujourd_hui' => 0,
            'vues_cette_semaine' => 0,
            'vues_ce_mois' => 0,
            'total_candidatures' => 0,
            'candidatures_en_attente' => 0,
            'candidatures_retenues' => 0,
        ];
        
        return array_merge($defaults, $stats);
    }
}
