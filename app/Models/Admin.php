<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Admin extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'admins';
    protected $primaryKey = 'admin_id';

    protected $fillable = [
        'user_id',
        'niveau_admin',
        'matricule_admin',
        'fonction',
        'departement',
        'programme_id',
        'regions_responsabilite',
        'villes_responsabilite',
        'statut_administratif',
    ];

    protected $casts = [
        'regions_responsabilite' => 'array',
        'villes_responsabilite' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    public function programme()
    {
        return $this->belongsTo(Programme::class, 'programme_id', 'programme_id');
    }
}


