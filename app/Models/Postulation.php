<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Postulation extends Model
{
    use SoftDeletes;

    protected $table = 'postulations';
    protected $primaryKey = 'postulation_id';

    protected $fillable = [
        'offre_id',
        'jeune_id',
        'cv_path',
        'lettre_motivation_path',
        'cv_document_id',
        'lm_document_id',
        'documents_supplementaires',
        'statut',
        'nombre_vues_employeur',
        'nombre_telechargements_cv',
        'nombre_telechargements_lm',
    ];

    protected $casts = [
        'documents_supplementaires' => 'array',
    ];

    /**
     * Relation avec l'offre d'emploi
     */
    public function offre()
    {
        return $this->belongsTo(OffreEmplois::class, 'offre_id', 'offre_id');
    }

    /**
     * Alias pour la relation offre (pour compatibilité avec le dashboard)
     */
    public function offreEmplois()
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
     * Relation avec l'utilisateur qui a modifié la postulation
     */
    // removed modifiePar fields/relations to match schema

    /**
     * Relation avec le CV document
     */
    public function cvDocument()
    {
        return $this->belongsTo(Document::class, 'cv_document_id');
    }

    /**
     * Relation avec la lettre de motivation document
     */
    public function lettreMotivationDocument()
    {
        return $this->belongsTo(Document::class, 'lm_document_id');
    }

    /**
     * Scope pour filtrer par statut
     */
    public function scopeParStatut($query, $statut)
    {
        return $query->where('statut', $statut);
    }

    /**
     * Scope pour les postulations en attente
     */
    public function scopeEnAttente($query)
    {
        return $query->where('statut', 'en_attente');
    }

    /**
     * Scope pour les postulations retenues
     */
    public function scopeRetenues($query)
    {
        return $query->where('statut', 'retenu');
    }

    /**
     * Scope pour les postulations rejetées
     */
    public function scopeRejetees($query)
    {
        return $query->where('statut', 'rejete');
    }

    /**
     * Méthode pour obtenir l'URL du CV
     */
    public function getCvUrlAttribute()
    {
        if ($this->cv_document_id) {
            return $this->cvDocument->url ?? null;
        }
        return $this->cv_path;
    }

    /**
     * Méthode pour obtenir l'URL de la lettre de motivation
     */
    public function getLettreMotivationUrlAttribute()
    {
        if ($this->lm_document_id) {
            return $this->lettreMotivationDocument->url ?? null;
        }
        return $this->lettre_motivation_path;
    }

    /**
     * Méthode pour vérifier si la postulation a un CV
     */
    public function hasCv()
    {
        return !empty($this->cv_path) || !empty($this->cv_document_id);
    }

    /**
     * Méthode pour vérifier si la postulation a une lettre de motivation
     */
    public function hasLettreMotivation()
    {
        return !empty($this->lettre_motivation_path) || !empty($this->lm_document_id);
    }

    /**
     * Méthode pour obtenir le nom du candidat
     */
    public function getNomCandidatAttribute()
    {
        return $this->jeune->nom ?? 'Candidat inconnu';
    }

    /**
     * Méthode pour obtenir l'email du candidat
     */
    public function getEmailCandidatAttribute()
    {
        return $this->jeune->email ?? null;
    }

    /**
     * Méthode pour obtenir le téléphone du candidat
     */
    public function getTelephoneCandidatAttribute()
    {
        return $this->jeune->telephone ?? null;
    }

    /**
     * Méthode pour obtenir le titre de l'offre
     */
    public function getTitreOffreAttribute()
    {
        return $this->offre->titre ?? 'Offre inconnue';
    }

    /**
     * Méthode pour obtenir le nom de l'employeur
     */
    public function getNomEmployeurAttribute()
    {
        return $this->offre->employeur->nom ?? 'Employeur inconnu';
    }
} 