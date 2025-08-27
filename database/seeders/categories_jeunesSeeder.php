<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class categories_jeunesSeeder extends Seeder
{
    
    public function run(): void
    {
        // Pour chaque programme, créer les catégories SIL, PACOP, SGH, PRF
        $programmes = DB::table('programmes')->pluck('programme_id');
        $nomsCategories = [
            ['code' => 'SIL', 'desc' => "Système d'Insertion Locale"],
            ['code' => 'PACOP', 'desc' => "Programme d'Accompagnement et de Coaching Professionnel"],
            ['code' => 'SGH', 'desc' => "Soutien aux Groupes et Hébergements"],
            ['code' => 'PRF', 'desc' => "Programme de Renforcement de la famille"],
        ];

        $rows = [];
        foreach ($programmes as $programmeId) {
            foreach ($nomsCategories as $cat) {
                $rows[] = [
                    'programme_id' => $programmeId,
                    'nom' => $cat['code'],
                    'description' => $cat['desc'],
                    'statut' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        DB::table('categories_jeunes')->insert($rows);
    }
} 