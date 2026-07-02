<?php

namespace Tests\Feature\Middleware;

use App\Models\Jury;
use App\Models\Pv;
use App\Models\Soutenance;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CheckRoleTest extends TestCase
{
    use RefreshDatabase;

    public function test_un_utilisateur_non_authentifie_est_redirige_vers_login(): void
    {
        $response = $this->get('/admin/dashboard');

        $response->assertRedirect('/login');
    }

    public function test_un_role_non_autorise_recoit_403_sur_les_routes_admin(): void
    {
        $user = User::factory()->create(['role' => 'etudiant']);

        $response = $this->actingAs($user)->post('/admin/users', [
            'name' => 'Test',
            'email' => 'test-admin-route@example.com',
            'role' => 'etudiant',
        ]);

        $response->assertStatus(403);
    }

    public function test_administrateur_passe_le_controle_de_role(): void
    {
        $admin = User::factory()->create(['role' => 'administrateur']);

        $response = $this->actingAs($admin)->post('/admin/users', [
            'name' => 'Nouvel Utilisateur',
            'email' => 'nouvel.utilisateur@example.com',
            'role' => 'etudiant',
        ]);

        $response->assertStatus(302);
        $this->assertDatabaseHas('users', ['email' => 'nouvel.utilisateur@example.com']);
    }

    public function test_un_role_non_autorise_recoit_403_sur_les_routes_secretaire(): void
    {
        $user = User::factory()->create(['role' => 'etudiant']);
        $soutenance = Soutenance::factory()->create();

        $response = $this->actingAs($user)->put("/secretaire/soutenances/{$soutenance->id}/confirm");

        $response->assertStatus(403);
    }

    public function test_secretaire_passe_le_controle_de_role(): void
    {
        $secretaire = User::factory()->create(['role' => 'secretaire_pedagogique']);
        $soutenance = Soutenance::factory()->create();

        $response = $this->actingAs($secretaire)->put("/secretaire/soutenances/{$soutenance->id}/confirm");

        $this->assertNotEquals(403, $response->status());
    }

    public function test_un_role_non_autorise_recoit_403_sur_les_routes_enseignant(): void
    {
        $user = User::factory()->create(['role' => 'etudiant']);
        $jury = Jury::factory()->create();

        $response = $this->actingAs($user)->put("/enseignant/jury/{$jury->id}/confirm");

        $response->assertStatus(403);
    }

    public function test_enseignant_passe_le_controle_de_role(): void
    {
        $enseignant = User::factory()->create(['role' => 'enseignant']);
        $jury = Jury::factory()->create(['utilisateur_id' => $enseignant->id]);

        $response = $this->actingAs($enseignant)->put("/enseignant/jury/{$jury->id}/confirm");

        $this->assertNotEquals(403, $response->status());
    }

    public function test_un_role_non_autorise_recoit_403_sur_les_routes_responsable(): void
    {
        $user = User::factory()->create(['role' => 'etudiant']);
        $pv = Pv::factory()->create(['status' => 'en_validation']);

        $response = $this->actingAs($user)->put("/responsable/pv/{$pv->id}/validate");

        $response->assertStatus(403);
    }

    public function test_responsable_passe_le_controle_de_role(): void
    {
        $responsable = User::factory()->create(['role' => 'responsable_pedagogique']);
        $pv = Pv::factory()->create(['status' => 'en_validation']);

        $response = $this->actingAs($responsable)->put("/responsable/pv/{$pv->id}/validate");

        $this->assertNotEquals(403, $response->status());
    }

    public function test_un_role_non_autorise_recoit_403_sur_le_dashboard_etudiant(): void
    {
        $user = User::factory()->create(['role' => 'enseignant']);

        $response = $this->actingAs($user)->get('/etudiant/dashboard');

        $response->assertStatus(403);
    }

    public function test_etudiant_passe_le_controle_de_role(): void
    {
        $etudiant = User::factory()->create(['role' => 'etudiant']);

        $response = $this->actingAs($etudiant)->get('/etudiant/dashboard');

        $this->assertNotEquals(403, $response->status());
    }
}
