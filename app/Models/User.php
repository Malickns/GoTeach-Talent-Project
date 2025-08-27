<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes;

    protected $primaryKey = 'user_id';
    
    // Spécifier le nom de la table
    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'prenom',
        'nom',
        'photo_profil',
        'email',
        'password',
        'telephone',
        'role',
        'statut',
        'valide_par',
        'valide_le',
        'commentaire_validation',
        'est_en_ligne',
        'derniere_activite',
        'session_token',
        'email_verified_at',
        'notifications_email',
        'notifications_sms',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'session_token',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'valide_le' => 'datetime',
            'derniere_activite' => 'datetime',
            'password' => 'hashed',
            'est_en_ligne' => 'boolean',
            'notifications_email' => 'boolean',
            'notifications_sms' => 'boolean',
        ];
    }


    /**
     * Relation avec le profil jeune
     */
    public function jeune()
    {
        return $this->hasOne(Jeune::class, 'user_id', 'user_id');
    }

    /**
     * Relation avec le profil employeur
     */
    public function employeur()
    {
        return $this->hasOne(Employeur::class, 'user_id', 'user_id');
    }

    /**
     * Relation avec le profil admin
     */
    public function admin()
    {
        return $this->hasOne(Admin::class, 'user_id', 'user_id');
    }

    /**
     * Relation avec l'utilisateur qui a validé ce compte
     */
    public function validePar()
    {
        return $this->belongsTo(User::class, 'valide_par', 'user_id');
    }

    /**
     * Vérifie si l'utilisateur est un jeune
     */
    public function isJeune()
    {
        return $this->role === 'jeune';
    }

    /**
     * Vérifie si l'utilisateur est un employeur
     */
    public function isEmployeur()
    {
        return $this->role === 'employeur';
    }

    /**
     * Vérifie si l'utilisateur est un admin
     */
    public function isAdmin()
    {
        return in_array($this->role, ['admin_local', 'admin_national']);
    }

    /**
     * Vérifie si l'utilisateur est un admin national
     */
    public function isAdminNational()
    {
        return $this->role === 'admin_national';
    }

    /**
     * Vérifie si l'utilisateur est actif
     */
    public function isActif()
    {
        return $this->statut === 'actif';
    }

    /**
     * Obtient le nom complet de l'utilisateur
     */
    public function getNomCompletAttribute()
    {
        return $this->prenom . ' ' . $this->nom;
    }

    /**
     * Met à jour la dernière activité
     */
    public function updateLastActivity()
    {
        $this->update([
            'derniere_activite' => now(),
            'est_en_ligne' => true
        ]);
    }

    /**
     * Déconnecte l'utilisateur
     */
    public function logout()
    {
        $this->update([
            'est_en_ligne' => false,
            'session_token' => null
        ]);
    }
}
