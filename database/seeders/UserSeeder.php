<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Aminata Diop',
            'email' => 'admin@gestsoutenance.test',
            'role' => 'administrateur',
        ]);

        User::factory()->create([
            'name' => 'Fatou Ndiaye',
            'email' => 'secretaire@gestsoutenance.test',
            'role' => 'secretaire_pedagogique',
        ]);

        User::factory()->create([
            'name' => 'Moussa Sarr',
            'email' => 'responsable@gestsoutenance.test',
            'role' => 'responsable_pedagogique',
        ]);

        $enseignants = [
            'Ibrahima Fall',
            'Awa Camara',
            'Cheikh Diallo',
            'Mariama Ba',
            'Ousmane Gueye',
        ];

        foreach ($enseignants as $nom) {
            User::factory()->create([
                'name' => $nom,
                'email' => str()->slug($nom) . '@gestsoutenance.test',
                'role' => 'enseignant',
                'department' => 'Informatique',
            ]);
        }

        $etudiants = [
            'Mamadou Diao',
            'Khady Sow',
            'Babacar Toure',
            'Aissatou Barry',
            'Modou Lo',
            'Bineta Diatta',
        ];

        foreach ($etudiants as $nom) {
            User::factory()->create([
                'name' => $nom,
                'email' => str()->slug($nom) . '@etudiant.gestsoutenance.test',
                'role' => 'etudiant',
            ]);
        }
    }
}
