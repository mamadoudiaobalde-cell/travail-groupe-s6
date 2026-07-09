<?php

namespace Database\Seeders;

use App\Models\Salle;
use App\Models\Soutenance;
use App\Models\User;
use Illuminate\Database\Seeder;
<<<<<<< HEAD

class SoutenanceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
=======
use Carbon\Carbon;

class SoutenanceSeeder extends Seeder
{
>>>>>>> origin/ibrahimadev
    public function run(): void
    {
        $etudiants = User::where('role', 'etudiant')->get();
        $enseignants = User::where('role', 'enseignant')->get();
        $salles = Salle::all();

        if ($etudiants->isEmpty() || $enseignants->isEmpty()) {
<<<<<<< HEAD
            $this->command?->warn('Aucun étudiant/enseignant trouvé : exécutez UserSeeder avant SoutenanceSeeder.');

=======
            $this->command?->warn('❌ Aucun étudiant ou enseignant trouvé !');
            return;
        }

        if ($salles->isEmpty()) {
            $this->command?->warn('❌ Aucune salle trouvée !');
>>>>>>> origin/ibrahimadev
            return;
        }

        $sujets = [
<<<<<<< HEAD
            ['titre' => 'Mise en place d\'une plateforme de gestion des soutenances', 'filiere' => 'Informatique', 'type' => 'master'],
            ['titre' => 'Étude comparative des bases de données NoSQL', 'filiere' => 'Informatique', 'type' => 'licence'],
            ['titre' => 'Optimisation des réseaux IoT en environnement urbain', 'filiere' => 'Réseaux', 'type' => 'master'],
            ['titre' => 'Analyse de la résistance des matériaux composites', 'filiere' => 'Génie Civil', 'type' => 'licence'],
            ['titre' => 'Modèles prédictifs pour la gestion de stocks', 'filiere' => 'Gestion', 'type' => 'master'],
        ];

        $statuts = ['brouillon', 'planifiee', 'confirmee'];

        foreach ($sujets as $index => $sujet) {
            Soutenance::factory()->create([
=======
            [
                'titre' => 'Mise en place d\'une plateforme de gestion des soutenances',
                'filiere' => 'Informatique',
                'type' => 'master',
                'statut' => 'planifiee'
            ],
            [
                'titre' => 'Étude comparative des bases de données NoSQL',
                'filiere' => 'Informatique',
                'type' => 'licence',
                'statut' => 'planifiee'
            ],
            [
                'titre' => 'Optimisation des réseaux IoT en environnement urbain',
                'filiere' => 'Réseaux',
                'type' => 'master',
                'statut' => 'confirmee'
            ],
            [
                'titre' => 'Analyse de la résistance des matériaux composites',
                'filiere' => 'Génie Civil',
                'type' => 'licence',
                'statut' => 'brouillon'
            ],
            [
                'titre' => 'Modèles prédictifs pour la gestion de stocks',
                'filiere' => 'Gestion',
                'type' => 'master',
                'statut' => 'planifiee'
            ],
        ];

        foreach ($sujets as $index => $sujet) {
            Soutenance::create([
>>>>>>> origin/ibrahimadev
                'etudiant_id' => $etudiants[$index % $etudiants->count()]->id,
                'directeur_id' => $enseignants[$index % $enseignants->count()]->id,
                'titre' => $sujet['titre'],
                'filiere' => $sujet['filiere'],
                'type' => $sujet['type'],
<<<<<<< HEAD
                'salle_id' => $salles->isNotEmpty() ? $salles[$index % $salles->count()]->id : null,
                'statut' => $statuts[$index % count($statuts)],
            ]);
        }
    }
}
=======
                'date' => Carbon::now()->addDays(($index + 1) * 7),
                'heure' => '09:00:00',
                'salle_id' => $salles[$index % $salles->count()]->id,
                'statut' => $sujet['statut'],
            ]);
        }

        $this->command?->info('✅ ' . Soutenance::count() . ' soutenances créées !');
    }
}
>>>>>>> origin/ibrahimadev
