<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $password = Hash::make('password');
        $now = now();

        $users = [
            ['name' => 'Aminata Diop',  'email' => 'admin@gestsoutenance.test',       'role' => 'administrateur',        'department' => null],
            ['name' => 'Fatou Ndiaye',  'email' => 'secretaire@gestsoutenance.test',  'role' => 'secretaire_pedagogique','department' => null],
            ['name' => 'Moussa Sarr',   'email' => 'responsable@gestsoutenance.test', 'role' => 'responsable_pedagogique','department' => null],
            ['name' => 'Ibrahima Fall', 'email' => 'ibrahima-fall@gestsoutenance.test','role' => 'enseignant',            'department' => 'Informatique'],
            ['name' => 'Awa Camara',    'email' => 'awa-camara@gestsoutenance.test',  'role' => 'enseignant',            'department' => 'Informatique'],
            ['name' => 'Cheikh Diallo', 'email' => 'cheikh-diallo@gestsoutenance.test','role' => 'enseignant',           'department' => 'Informatique'],
            ['name' => 'Mariama Ba',    'email' => 'mariama-ba@gestsoutenance.test',  'role' => 'enseignant',            'department' => 'Informatique'],
            ['name' => 'Ousmane Gueye', 'email' => 'ousmane-gueye@gestsoutenance.test','role' => 'enseignant',           'department' => 'Informatique'],
            ['name' => 'Mamadou Diao',  'email' => 'mamadou-diao@etudiant.gestsoutenance.test', 'role' => 'etudiant',   'department' => null],
            ['name' => 'Khady Sow',     'email' => 'khady-sow@etudiant.gestsoutenance.test',    'role' => 'etudiant',   'department' => null],
            ['name' => 'Babacar Toure', 'email' => 'babacar-toure@etudiant.gestsoutenance.test','role' => 'etudiant',   'department' => null],
            ['name' => 'Aissatou Barry','email' => 'aissatou-barry@etudiant.gestsoutenance.test','role' => 'etudiant',  'department' => null],
            ['name' => 'Modou Lo',      'email' => 'modou-lo@etudiant.gestsoutenance.test',     'role' => 'etudiant',   'department' => null],
            ['name' => 'Bineta Diatta', 'email' => 'bineta-diatta@etudiant.gestsoutenance.test','role' => 'etudiant',   'department' => null],
        ];

        foreach ($users as $data) {
            User::firstOrCreate(
                ['email' => $data['email']],
                [
                    'name'              => $data['name'],
                    'role'              => $data['role'],
                    'department'        => $data['department'],
                    'password'          => $password,
                    'email_verified_at' => $now,
                    'is_active'         => true,
                ]
            );
        }
    }
}
