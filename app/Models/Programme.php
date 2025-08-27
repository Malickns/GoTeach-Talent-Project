<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Programme extends Model
{
    use HasFactory, SoftDeletes;

    protected $primaryKey = 'programme_id';

    protected $fillable = [
        'nom',
        'description',
        'responsable_id',
        'statut',
    ];

    protected $casts = [
        'date_creation' => 'datetime',
        'statut' => 'boolean',
    ];

    /**
     * Relation avec le responsable du programme
     */
    public function responsable()
    {
        return $this->belongsTo(User::class, 'responsable_id', 'user_id');
    }

    /**
     * Relation avec les jeunes du programme
     */
    public function jeunes()
    {
        return $this->hasMany(Jeune::class, 'programme_id', 'programme_id');
    }

    /**
     * Relation avec les catégories du programme
     */
    public function categories()
    {
        return $this->hasMany(CategorieJeune::class, 'programme_id', 'programme_id');
    }

    /**
     * Scope pour les programmes actifs
     */
    public function scopeActifs($query)
    {
        return $query->where('statut', true);
    }

    /**
     * Méthode pour obtenir le nombre de jeunes dans le programme
     */
    public function getNombreJeunesAttribute()
    {
        return $this->jeunes()->count();
    }

    /**
     * Méthode pour obtenir le nombre de jeunes actifs dans le programme
     */
    public function getNombreJeunesActifsAttribute()
    {
        return $this->jeunes()->whereHas('user', function ($query) {
            $query->where('statut', 'actif');
        })->count();
    }
} 