<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategorieJeune extends Model
{
    use HasFactory;

    protected $table = 'categories_jeunes';
    protected $primaryKey = 'categorie_id';

    protected $fillable = [
        'programme_id',
        'nom',
        'description',
        'statut',
    ];

    protected $casts = [
        'statut' => 'boolean',
    ];

    /**
     * Relation avec les jeunes de cette catégorie
     */
    public function jeunes()
    {
        return $this->hasMany(Jeune::class, 'categorie_id', 'categorie_id');
    }

    /**
     * Relation avec le programme
     */
    public function programme()
    {
        return $this->belongsTo(Programme::class, 'programme_id', 'programme_id');
    }

    /**
     * Scope pour les catégories actives
     */
    public function scopeActives($query)
    {
        return $query->where('statut', true);
    }

    /**
     * Méthode pour obtenir le nombre de jeunes dans cette catégorie
     */
    public function getNombreJeunesAttribute()
    {
        return $this->jeunes()->count();
    }

    /**
     * Méthode pour obtenir le nombre de jeunes actifs dans cette catégorie
     */
    public function getNombreJeunesActifsAttribute()
    {
        return $this->jeunes()->whereHas('user', function ($query) {
            $query->where('statut', 'actif');
        })->count();
    }
} 