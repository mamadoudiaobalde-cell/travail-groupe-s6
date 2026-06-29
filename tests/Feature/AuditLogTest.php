<?php

namespace Tests\Feature;

use App\Models\Jury;
use App\Models\Pv;
use App\Models\Soutenance;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuditLogTest extends TestCase
{
    use RefreshDatabase;

    public function test_la_confirmation_dune_soutenance_est_journalisee(): void
    {
        $secretaire = User::factory()->create(['role' => 'secretaire_pedagogique']);
        $soutenance = Soutenance::factory()->create();
        Jury::factory()->create(['soutenance_id' => $soutenance->id]);

        $this->actingAs($secretaire)->put("/secretaire/soutenances/{$soutenance->id}/confirm");

        $this->assertDatabaseHas('audit_logs', [
            'utilisateur_id' => $secretaire->id,
            'action' => 'soutenance.confirm',
        ]);
    }

    public function test_lannulation_dune_soutenance_est_journalisee(): void
    {
        $secretaire = User::factory()->create(['role' => 'secretaire_pedagogique']);
        $soutenance = Soutenance::factory()->create();

        $this->actingAs($secretaire)->put("/secretaire/soutenances/{$soutenance->id}/cancel");

        $this->assertDatabaseHas('audit_logs', [
            'utilisateur_id' => $secretaire->id,
            'action' => 'soutenance.cancel',
        ]);
    }

    public function test_la_composition_du_jury_est_journalisee(): void
    {
        $secretaire = User::factory()->create(['role' => 'secretaire_pedagogique']);
        $soutenance = Soutenance::factory()->create();
        $enseignant = User::factory()->create(['role' => 'enseignant']);

        $this->actingAs($secretaire)->post("/secretaire/soutenances/{$soutenance->id}/jury", [
            'utilisateur_id' => $enseignant->id,
            'role' => 'membre',
        ]);

        $this->assertDatabaseHas('audit_logs', [
            'utilisateur_id' => $secretaire->id,
            'action' => 'jury.store',
        ]);
    }

    public function test_la_validation_dun_pv_est_journalisee(): void
    {
        $responsable = User::factory()->create(['role' => 'responsable_pedagogique']);
        $pv = Pv::factory()->create(['status' => 'en_validation']);

        $this->actingAs($responsable)->put("/responsable/pv/{$pv->id}/validate");

        $this->assertDatabaseHas('audit_logs', [
            'utilisateur_id' => $responsable->id,
            'action' => 'pv.validate',
        ]);
    }
}
