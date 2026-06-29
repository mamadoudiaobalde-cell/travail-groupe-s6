<?php

namespace Database\Seeders;

use App\Models\Salle;
use App\Models\Soutenance;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SoutenanceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $etudiants = User::where('role', 'etudiant')->get();
        $enseignants = User::where('role', 'enseignant')->get();
        $salles = Salle::all();

        if ($etudiants->isEmpty() || $enseignants->isEmpty()) {
            $this->command?->warn('Aucun étudiant/enseignant trouvé : exécutez UserSeeder avant SoutenanceSeeder.');
            return;
        }

        $sujets = [
            ['titre' => 'Mise en place d\'une plateforme de gestion des soutenances', 'filiere' => 'Informatique', 'type' => 'master'],
            ['titre' => 'Étude comparative des bases de données NoSQL', 'filiere' => 'Informatique', 'type' => 'licence'],
            ['titre' => 'Optimisation des réseaux IoT en environnement urbain', 'filiere' => 'Réseaux', 'type' => 'master'],
            ['titre' => 'Analyse de la résistance des matériaux composites', 'filiere' => 'Génie Civil', 'type' => 'licence'],
            ['titre' => 'Modèles prédictifs pour la gestion de stocks', 'filiere' => 'Gestion', 'type' => 'master'],
        ];

        $statuts = ['brouillon', 'planifiee', 'confirmee'];

        foreach ($sujets as $index => $sujet) {
            Soutenance::factory()->create([
                'etudiant_id' => $etudiants[$index % $etudiants->count()]->id,
                'directeur_id' => $enseignants[$index % $enseignants->count()]->id,
                'titre' => $sujet['titre'],
                'filiere' => $sujet['filiere'],
                'type' => $sujet['type'],
                'salle_id' => $salles->isNotEmpty() ? $salles[$index % $salles->count()]->id : null,
                'statut' => $statuts[$index % count($statuts)],
            ]);
        }
    }
}
