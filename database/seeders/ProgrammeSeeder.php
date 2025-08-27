<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProgrammeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $noms = ['Tambacounda', 'Dakar', 'Louga', 'Kaolack', 'Ziguinchor'];
        $rows = [];
        foreach ($noms as $nom) {
            $rows[] = [
                'nom' => $nom,
                'description' => 'Programme de ' . $nom,
                'date_creation' => now(),
                'responsable_id' => null,
                'statut' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        DB::table('programmes')->insert($rows);
    }
} 