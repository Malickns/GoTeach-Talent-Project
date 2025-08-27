<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Employeur extends Model
{
    use SoftDeletes;

    protected $table = 'employeurs';
    protected $primaryKey = 'employeur_id';

    protected $fillable = [
        'user_id',
        'nom_entreprise',
        'raison_sociale',
        'numero_rccm',
        'numero_ninea',
        'secteur_activite',
        'description_activite',
        'adresse_entreprise',
        'ville_entreprise',
        'region_entreprise',
        'pays_entreprise',
        'telephone_entreprise',
        'fax_entreprise',
        'site_web',
        'email_entreprise',
        'type_entreprise',
        'nombre_offres_publiees',
        'nombre_candidatures_recues',
        'nombre_embauches_effectuees',
    ];

    protected $casts = [
        // No datetime/boolean casts defined in current simplified schema
    ];

    /**
     * Relation avec l'utilisateur
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    /**
     * Relation avec les offres d'emplois
     */
    public function offresEmplois()
    {
        return $this->hasMany(OffreEmplois::class, 'employeur_id', 'employeur_id');
    }

    /**
     * Relation avec l'utilisateur qui a validé ce compte
     */
    // removed verification-related relations/flags to match schema
} 