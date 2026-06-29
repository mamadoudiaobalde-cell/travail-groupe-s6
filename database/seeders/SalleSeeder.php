<?php

namespace Database\Seeders;

use App\Models\Salle;
use Illuminate\Database\Seeder;

class SalleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $salles = [
            ['nom' => 'Amphi A', 'capacite' => 80, 'localisation' => 'Bâtiment principal', 'equipements' => 'Vidéoprojecteur, micro, sonorisation'],
            ['nom' => 'Salle 101', 'capacite' => 30, 'localisation' => 'Bâtiment B', 'equipements' => 'Vidéoprojecteur, tableau blanc'],
            ['nom' => 'Salle 102', 'capacite' => 25, 'localisation' => 'Bâtiment B', 'equipements' => 'Tableau blanc'],
            ['nom' => 'Salle de soutenance 1', 'capacite' => 15, 'localisation' => 'Bâtiment C', 'equipements' => 'Vidéoprojecteur, table en U'],
            ['nom' => 'Salle de soutenance 2', 'capacite' => 15, 'localisation' => 'Bâtiment C', 'equipements' => 'Vidéoprojecteur, table en U'],
        ];

        foreach ($salles as $salle) {
            Salle::factory()->create($salle);
        }
    }
}
