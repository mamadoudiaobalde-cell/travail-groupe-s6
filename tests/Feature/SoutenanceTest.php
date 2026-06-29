<?php

namespace Tests\Feature;

use App\Models\Jury;
use App\Models\Soutenance;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SoutenanceTest extends TestCase
{
    use RefreshDatabase;

    public function test_on_ne_peut_pas_confirmer_une_soutenance_sans_salle(): void
    {
        $secretaire = User::factory()->create(['role' => 'secretaire_pedagogique']);
        $soutenance = Soutenance::factory()->create(['salle_id' => null]);
        Jury::factory()->create(['soutenance_id' => $soutenance->id]);

        $response = $this->actingAs($secretaire)->put("/secretaire/soutenances/{$soutenance->id}/confirm");

        $response->assertRedirect();
        $this->assertSame('brouillon', $soutenance->refresh()->statut);
    }

    public function test_on_ne_peut_pas_confirmer_une_soutenance_sans_jury(): void
    {
        $secretaire = User::factory()->create(['role' => 'secretaire_pedagogique']);
        $soutenance = Soutenance::factory()->create();

        $response = $this->actingAs($secretaire)->put("/secretaire/soutenances/{$soutenance->id}/confirm");

        $response->assertRedirect();
        $this->assertSame('brouillon', $soutenance->refresh()->statut);
    }

    public function test_on_peut_confirmer_une_soutenance_avec_salle_et_jury(): void
    {
        $secretaire = User::factory()->create(['role' => 'secretaire_pedagogique']);
        $soutenance = Soutenance::factory()->create();
        Jury::factory()->create(['soutenance_id' => $soutenance->id]);

        $response = $this->actingAs($secretaire)->put("/secretaire/soutenances/{$soutenance->id}/confirm");

        $response->assertRedirect();
        $this->assertSame('confirmee', $soutenance->refresh()->statut);
    }

    public function test_annuler_une_soutenance_notifie_letudiant_et_le_jury(): void
    {
        $secretaire = User::factory()->create(['role' => 'secretaire_pedagogique']);
        $soutenance = Soutenance::factory()->create();
        $jury = Jury::factory()->create(['soutenance_id' => $soutenance->id]);

        $response = $this->actingAs($secretaire)->put("/secretaire/soutenances/{$soutenance->id}/cancel");

        $response->assertRedirect();
        $this->assertSame('annulee', $soutenance->refresh()->statut);
        $this->assertDatabaseHas('notifications', [
            'utilisateur_id' => $soutenance->etudiant_id,
            'type' => 'annulation',
        ]);
        $this->assertDatabaseHas('notifications', [
            'utilisateur_id' => $jury->utilisateur_id,
            'type' => 'annulation',
        ]);
    }
}
