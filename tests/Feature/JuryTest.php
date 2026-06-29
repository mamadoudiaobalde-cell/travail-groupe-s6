<?php

namespace Tests\Feature;

use App\Models\Jury;
use App\Models\Soutenance;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class JuryTest extends TestCase
{
    use RefreshDatabase;

    public function test_secretaire_peut_composer_le_jury_dune_soutenance(): void
    {
        $secretaire = User::factory()->create(['role' => 'secretaire_pedagogique']);
        $soutenance = Soutenance::factory()->create();
        $enseignant = User::factory()->create(['role' => 'enseignant']);

        $response = $this->actingAs($secretaire)->post("/secretaire/soutenances/{$soutenance->id}/jury", [
            'utilisateur_id' => $enseignant->id,
            'role' => 'president',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('jury_membres', [
            'soutenance_id' => $soutenance->id,
            'utilisateur_id' => $enseignant->id,
            'role' => 'president',
            'statut_confirmation' => 'en_attente',
        ]);
    }

    public function test_un_role_invalide_est_rejete(): void
    {
        $secretaire = User::factory()->create(['role' => 'secretaire_pedagogique']);
        $soutenance = Soutenance::factory()->create();
        $enseignant = User::factory()->create(['role' => 'enseignant']);

        $response = $this->actingAs($secretaire)->post("/secretaire/soutenances/{$soutenance->id}/jury", [
            'utilisateur_id' => $enseignant->id,
            'role' => 'role_inexistant',
        ]);

        $response->assertSessionHasErrors('role');
        $this->assertDatabaseCount('jury_membres', 0);
    }

    public function test_secretaire_peut_retirer_un_membre_du_jury(): void
    {
        $secretaire = User::factory()->create(['role' => 'secretaire_pedagogique']);
        $jury = Jury::factory()->create();

        $response = $this->actingAs($secretaire)->delete("/secretaire/jury/{$jury->id}");

        $response->assertRedirect();
        $this->assertDatabaseMissing('jury_membres', ['id' => $jury->id]);
    }

    public function test_enseignant_peut_confirmer_sa_participation(): void
    {
        $enseignant = User::factory()->create(['role' => 'enseignant']);
        $jury = Jury::factory()->create(['utilisateur_id' => $enseignant->id]);

        $response = $this->actingAs($enseignant)->put("/enseignant/jury/{$jury->id}/confirm");

        $response->assertRedirect();
        $this->assertSame('confirme', $jury->refresh()->statut_confirmation);
    }

    public function test_enseignant_peut_refuser_sa_participation(): void
    {
        $enseignant = User::factory()->create(['role' => 'enseignant']);
        $jury = Jury::factory()->create(['utilisateur_id' => $enseignant->id]);

        $response = $this->actingAs($enseignant)->put("/enseignant/jury/{$jury->id}/decline");

        $response->assertRedirect();
        $this->assertSame('refuse', $jury->refresh()->statut_confirmation);
    }

    public function test_un_enseignant_ne_peut_pas_confirmer_la_participation_dun_autre(): void
    {
        $enseignant = User::factory()->create(['role' => 'enseignant']);
        $autreEnseignant = User::factory()->create(['role' => 'enseignant']);
        $jury = Jury::factory()->create(['utilisateur_id' => $autreEnseignant->id]);

        $response = $this->actingAs($enseignant)->put("/enseignant/jury/{$jury->id}/confirm");

        $response->assertStatus(403);
        $this->assertSame('en_attente', $jury->refresh()->statut_confirmation);
    }
}
