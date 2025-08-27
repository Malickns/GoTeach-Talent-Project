<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    use HasFactory;

    /**
     * Clé primaire personnalisée
     */
    protected $primaryKey = 'document_id';

    /**
     * Attributs assignables en masse
     */
    protected $fillable = [
        'jeune_id',
        'type', // 'cv', 'lettre_motivation'
        'nom_original',
        'chemin_fichier',
        'date_upload',
    ];

    /**
     * Attributs à caster
     */
    protected $casts = [
        'date_upload' => 'datetime',
    ];

    /**
     * Relation avec le jeune
     */
    public function jeune()
    {
        return $this->belongsTo(Jeune::class, 'jeune_id', 'jeune_id');
    }

    /**
     * Relation avec les postulations (pour CV et lettre de motivation)
     */
    public function postulations()
    {
        return $this->hasMany(Postulation::class, 'cv_document_id', 'document_id');
    }

    /**
     * Relation avec les postulations (pour lettre de motivation)
     */
    public function postulationsLettreMotivation()
    {
        return $this->hasMany(Postulation::class, 'lm_document_id', 'document_id');
    }

    /**
     * Accesseur pour l'URL du fichier
     */
    public function getUrlAttribute()
    {
        return asset('storage/' . $this->chemin_fichier);
    }

    /**
     * Scope pour les CV
     */
    public function scopeCv($query)
    {
        return $query->where('type', 'cv');
    }

    /**
     * Scope pour les lettres de motivation
     */
    public function scopeLettresMotivation($query)
    {
        return $query->where('type', 'lettre_motivation');
    }

    /**
     * Méthode pour vérifier si le fichier existe
     */
    public function fichierExiste()
    {
        return file_exists(storage_path('app/public/' . $this->chemin_fichier));
    }

    /**
     * Méthode pour supprimer le fichier physique
     */
    public function supprimerFichier()
    {
        $chemin = storage_path('app/public/' . $this->chemin_fichier);
        if (file_exists($chemin)) {
            unlink($chemin);
        }
    }

    /**
     * Méthode pour télécharger le fichier
     */
    public function telecharger()
    {
        $chemin = storage_path('app/public/' . $this->chemin_fichier);
        if (file_exists($chemin)) {
            return response()->download($chemin, $this->nom_original);
        }
        return null;
    }

    /**
     * Méthode pour obtenir l'extension du fichier
     */
    public function getExtensionAttribute()
    {
        return pathinfo($this->nom_original ?? '', PATHINFO_EXTENSION);
    }

    /**
     * Méthode pour obtenir le type de document lisible
     */
    public function getTypeLisibleAttribute()
    {
        return match($this->type) {
            'cv' => 'CV',
            'lettre_motivation' => 'Lettre de motivation',
            default => ucfirst((string) $this->type)
        };
    }

    /**
     * Méthode pour obtenir la taille formatée du fichier
     */
    public function getTailleFormateeAttribute()
    {
        $chemin = storage_path('app/public/' . $this->chemin_fichier);
        if (file_exists($chemin)) {
            $taille = filesize($chemin);
            $unites = ['B', 'KB', 'MB', 'GB'];
            $i = 0;
            while ($taille >= 1024 && $i < count($unites) - 1) {
                $taille /= 1024;
                $i++;
            }
            return round($taille, 2) . ' ' . $unites[$i];
        }
        return 'N/A';
    }

    /**
     * Méthode pour obtenir l'icône selon le type
     */
    public function getIconeAttribute()
    {
        return match($this->type) {
            'cv' => 'fas fa-file-alt',
            'lettre_motivation' => 'fas fa-envelope',
            default => 'fas fa-file'
        };
    }

    /**
     * Méthode pour obtenir la couleur selon le type
     */
    public function getCouleurAttribute()
    {
        return match($this->type) {
            'cv' => 'primary',
            'lettre_motivation' => 'success',
            default => 'secondary'
        };
    }
} 