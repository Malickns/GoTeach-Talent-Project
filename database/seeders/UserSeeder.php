<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            // Jeune - Adama Ndiaye
            [
                'user_id' => 1,
                'prenom' => 'Adama',
                'nom' => 'Ndiaye',
                'email' => 'adama@jeune.com',
                'password' => Hash::make('Passer@123'),
                'telephone' => '+221 77 123 45 67',
                'role' => 'jeune',
                'statut' => 'actif',
                'est_en_ligne' => false,
                
           
                'valide_par' => 3, // Validé par Cheikh Faye (admin local)
                'valide_le' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            
            // Employeur - Fama Seck
            [
                'user_id' => 2,
                'prenom' => 'Fama',
                'nom' => 'Seck',
                'email' => 'fama@employeur.com',
                'password' => Hash::make('Passer@123'),
                'telephone' => '+221 77 234 56 78',
                'role' => 'employeur',
                'statut' => 'actif',
                'est_en_ligne' => false,
                'valide_par' => 3, // Validé par Cheikh Faye (admin local)
                'valide_le' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            
            // Admin Local - Cheikh Faye
            [
                'user_id' => 3,
                'prenom' => 'Cheikh',
                'nom' => 'Faye',
                'email' => 'cheikh@adminLocal.com',
                'password' => Hash::make('Passer@123'),
                'telephone' => '+221 77 345 67 89',
                'role' => 'admin_local',
                'statut' => 'actif',
                'est_en_ligne' => false,
                'valide_par' => 4, // Validé par Aminata Gaye (admin national)
                'valide_le' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            
            // Admin National - Aminata Gaye
            [
                'user_id' => 4,
                'prenom' => 'Aminata',
                'nom' => 'Gaye',
                'email' => 'aminata@adminNational.com',
                'password' => Hash::make('Passer@123'),
                'telephone' => '+221 77 456 78 90',
                'role' => 'admin_national',
                'statut' => 'actif',
                'est_en_ligne' => false,
                'valide_par' => null, // Admin national auto-validé
                'valide_le' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ];

        DB::table('users')->insert($users);
    }
} 