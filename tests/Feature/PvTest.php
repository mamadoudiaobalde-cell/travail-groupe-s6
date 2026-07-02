<?php

namespace Tests\Feature;

use App\Models\Pv;
use App\Models\Soutenance;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PvTest extends TestCase
{
    use RefreshDatabase;

    public function test_secretaire_peut_saisir_les_resultats_dune_soutenance(): void
    {
        $secretaire = User::factory()->create(['role' => 'secretaire_pedagogique']);
        $soutenance = Soutenance::factory()->create();

        $response = $this->actingAs($secretaire)->post("/secretaire/soutenances/{$soutenance->id}/pv", [
            'note' => 15.5,
            'observations' => 'Bon travail',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('pvs', [
            'soutenance_id' => $soutenance->id,
            'note' => 15.5,
            'mention' => 'Tres bien',
            'status' => 'brouillon',
        ]);
    }

    public function test_la_mention_est_calculee_automatiquement_selon_la_note(): void
    {
        $cases = [
            ['note' => 17, 'mention' => 'Excellent'],
            ['note' => 14, 'mention' => 'Tres bien'],
            ['note' => 12, 'mention' => 'Bien'],
            ['note' => 10, 'mention' => 'Assez bien'],
            ['note' => 5, 'mention' => 'Passable'],
        ];

        foreach ($cases as $case) {
            $this->assertSame($case['mention'], Pv::calculerMention($case['note']));
        }
    }

    public function test_on_ne_peut_pas_creer_deux_pv_pour_la_meme_soutenance(): void
    {
        $secretaire = User::factory()->create(['role' => 'secretaire_pedagogique']);
        $soutenance = Soutenance::factory()->create();
        Pv::factory()->create(['soutenance_id' => $soutenance->id]);

        $response = $this->actingAs($secretaire)->post("/secretaire/soutenances/{$soutenance->id}/pv", [
            'note' => 12,
        ]);

        $response->assertRedirect();
        $this->assertDatabaseCount('pvs', 1);
    }

    public function test_secretaire_peut_soumettre_un_pv_brouillon_pour_validation(): void
    {
        $secretaire = User::factory()->create(['role' => 'secretaire_pedagogique']);
        $pv = Pv::factory()->create(['status' => 'brouillon']);

        $response = $this->actingAs($secretaire)->put("/secretaire/pv/{$pv->id}/submit");

        $response->assertRedirect();
        $this->assertSame('en_validation', $pv->refresh()->status);
    }

    public function test_un_pv_archive_ne_peut_plus_etre_modifie(): void
    {
        $secretaire = User::factory()->create(['role' => 'secretaire_pedagogique']);
        $pv = Pv::factory()->create(['status' => 'archive']);

        $response = $this->actingAs($secretaire)->put("/secretaire/pv/{$pv->id}", [
            'note' => 18,
        ]);

        $response->assertRedirect();
        $this->assertSame('archive', $pv->refresh()->status);
        $this->assertNotEquals(18, $pv->note);
    }

    public function test_responsable_peut_valider_un_pv_en_validation(): void
    {
        $responsable = User::factory()->create(['role' => 'responsable_pedagogique']);
        $pv = Pv::factory()->create(['status' => 'en_validation']);

        $response = $this->actingAs($responsable)->put("/responsable/pv/{$pv->id}/validate");

        $response->assertRedirect();
        $this->assertSame('valide', $pv->refresh()->status);
    }

    public function test_responsable_doit_fournir_un_commentaire_pour_refuser_un_pv(): void
    {
        $responsable = User::factory()->create(['role' => 'responsable_pedagogique']);
        $pv = Pv::factory()->create(['status' => 'en_validation']);

        $response = $this->actingAs($responsable)->put("/responsable/pv/{$pv->id}/reject", []);

        $response->assertSessionHasErrors('commentaire');
        $this->assertSame('en_validation', $pv->refresh()->status);
    }

    public function test_responsable_peut_refuser_un_pv_avec_commentaire(): void
    {
        $responsable = User::factory()->create(['role' => 'responsable_pedagogique']);
        $pv = Pv::factory()->create(['status' => 'en_validation']);

        $response = $this->actingAs($responsable)->put("/responsable/pv/{$pv->id}/reject", [
            'commentaire' => 'Note manquante pour le rapporteur',
        ]);

        $response->assertRedirect();
        $pv->refresh();
        $this->assertSame('brouillon', $pv->status);
        $this->assertStringContainsString('Note manquante pour le rapporteur', $pv->observations);
    }
}
