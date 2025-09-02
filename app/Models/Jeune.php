<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Jeune extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * Clé primaire personnalisée
     */
    protected $primaryKey = 'jeune_id';

    /**
     * Attributs assignables en masse
     */
    protected $fillable = [
        'user_id',
        'date_naissance',
        'genre',
        'lieu_naissance',
        'numero_cni',
        'adresse',
        'ville',
        'region',
        'niveau_etude',
        'dernier_diplome',
        'etablissement',
        'annee_obtention',
        'programme_id',
        'nombre_candidatures',
        'preferences_emploi',
        'types_contrat_preferes',
        'secteurs_preferes',
    ];

    /**
     * Attributs à caster
     */
    protected $casts = [
        'date_naissance' => 'date',
        'types_contrat_preferes' => 'array',
        'secteurs_preferes' => 'array',
    ];

    /**
     * Relation avec l'utilisateur
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    /**
     * Relation avec le programme SOS
     */
    public function programme()
    {
        return $this->belongsTo(Programme::class, 'programme_id', 'programme_id');
    }

    /**
     * Relation avec la catégorie
     */
    public function categorie()
    {
        return $this->belongsTo(CategorieJeune::class, 'categorie_id', 'categorie_id');
    }

    /**
     * Relation avec les postulations
     */
    public function postulations()
    {
        return $this->hasMany(Postulation::class, 'jeune_id', 'jeune_id');
    }

    /**
     * Relation avec les documents
     */
    public function documents()
    {
        return $this->hasMany(Document::class, 'jeune_id', 'jeune_id');
    }

    /**
     * Accesseur pour le nom complet
     */
    public function getNomCompletAttribute()
    {
        if ($this->user) {
            return trim($this->user->prenom . ' ' . $this->user->nom);
        }
        return 'Jeune inconnu';
    }

    /**
     * Accesseur pour l'email
     */
    public function getEmailAttribute()
    {
        return $this->user->email ?? '';
    }

    /**
     * Accesseur pour le téléphone
     */
    public function getTelephoneAttribute()
    {
        return $this->user->telephone ?? '';
    }

    /**
     * Accesseur pour l'âge
     */
    public function getAgeAttribute()
    {
        if (!$this->date_naissance) {
            return null;
        }
        return $this->date_naissance->age;
    }

    /**
     * Scope pour les jeunes actifs
     */
    public function scopeActifs($query)
    {
        return $query->whereHas('user', function ($q) {
            $q->where('statut', 'actif');
        });
    }

    /**
     * Scope pour les jeunes avec profil public
     */
    // removed scopes for profil_public/recevoir_offres to match schema

    /**
     * Méthode pour vérifier si le jeune a un CV
     */
    public function hasCv()
    {
        return $this->documents()->where('type', 'cv')->exists();
    }

    /**
     * Méthode pour obtenir le CV du jeune
     */
    public function getCv()
    {
        return $this->documents()->where('type', 'cv')->first();
    }

    /**
     * Méthode pour vérifier si le jeune a une lettre de motivation
     */
    public function hasLettreMotivation()
    {
        return $this->documents()->where('type', 'lettre_motivation')->exists();
    }

    /**
     * Méthode pour obtenir la lettre de motivation du jeune
     */
    public function getLettreMotivation()
    {
        return $this->documents()->where('type', 'lettre_motivation')->first();
    }

    /**
     * Méthode pour vérifier si le jeune a postulé à une offre
     */
    public function hasPostule($offreId)
    {
        return $this->postulations()->where('offre_id', $offreId)->exists();
    }

    /**
     * Méthode pour obtenir les offres recommandées
     */
    public function getOffresRecommandees()
    {
        return OffreEmplois::visible()
            ->whereNotIn('offre_id', $this->postulations()->pluck('offre_id'))
            ->orderBy('created_at', 'desc')
            ->limit(10);
    }

    /**
     * Méthode pour mettre à jour les statistiques
     */
    public function updateStatistiques()
    {
        $this->update([
            'nombre_candidatures' => $this->postulations()->count(),
        ]);
    }
} 