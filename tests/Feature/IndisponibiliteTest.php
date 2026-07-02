<?php

namespace Tests\Feature;

use App\Models\Indisponibilite;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class IndisponibiliteTest extends TestCase
{
    use RefreshDatabase;

    public function test_un_enseignant_peut_declarer_une_indisponibilite(): void
    {
        $enseignant = User::factory()->create(['role' => 'enseignant']);

        $response = $this->actingAs($enseignant)->post('/enseignant/indisponibilites', [
            'date_debut' => '2026-08-01',
            'date_fin' => '2026-08-05',
            'motif' => 'Congé',
        ]);

        $response->assertRedirect();
        $indisponibilite = Indisponibilite::where('utilisateur_id', $enseignant->id)->first();
        $this->assertNotNull($indisponibilite);
        $this->assertSame('2026-08-01', $indisponibilite->date_debut->format('Y-m-d'));
        $this->assertSame('2026-08-05', $indisponibilite->date_fin->format('Y-m-d'));
    }

    public function test_la_date_fin_doit_etre_posterieure_ou_egale_a_la_date_debut(): void
    {
        $enseignant = User::factory()->create(['role' => 'enseignant']);

        $response = $this->actingAs($enseignant)->post('/enseignant/indisponibilites', [
            'date_debut' => '2026-08-05',
            'date_fin' => '2026-08-01',
        ]);

        $response->assertSessionHasErrors('date_fin');
        $this->assertDatabaseCount('indisponibilites', 0);
    }

    public function test_un_enseignant_peut_modifier_sa_propre_indisponibilite(): void
    {
        $enseignant = User::factory()->create(['role' => 'enseignant']);
        $indisponibilite = Indisponibilite::factory()->create(['utilisateur_id' => $enseignant->id]);

        $response = $this->actingAs($enseignant)->put("/enseignant/indisponibilites/{$indisponibilite->id}", [
            'date_debut' => '2026-09-01',
            'date_fin' => '2026-09-02',
        ]);

        $response->assertRedirect();
        $this->assertSame('2026-09-01', $indisponibilite->refresh()->date_debut->format('Y-m-d'));
    }

    public function test_un_enseignant_ne_peut_pas_modifier_lindisponibilite_dun_autre(): void
    {
        $enseignant = User::factory()->create(['role' => 'enseignant']);
        $autreEnseignant = User::factory()->create(['role' => 'enseignant']);
        $indisponibilite = Indisponibilite::factory()->create(['utilisateur_id' => $autreEnseignant->id]);

        $response = $this->actingAs($enseignant)->put("/enseignant/indisponibilites/{$indisponibilite->id}", [
            'date_debut' => '2026-09-01',
            'date_fin' => '2026-09-02',
        ]);

        $response->assertStatus(403);
    }

    public function test_un_enseignant_peut_supprimer_sa_propre_indisponibilite(): void
    {
        $enseignant = User::factory()->create(['role' => 'enseignant']);
        $indisponibilite = Indisponibilite::factory()->create(['utilisateur_id' => $enseignant->id]);

        $response = $this->actingAs($enseignant)->delete("/enseignant/indisponibilites/{$indisponibilite->id}");

        $response->assertRedirect();
        $this->assertDatabaseMissing('indisponibilites', ['id' => $indisponibilite->id]);
    }

    public function test_un_enseignant_ne_peut_pas_supprimer_lindisponibilite_dun_autre(): void
    {
        $enseignant = User::factory()->create(['role' => 'enseignant']);
        $autreEnseignant = User::factory()->create(['role' => 'enseignant']);
        $indisponibilite = Indisponibilite::factory()->create(['utilisateur_id' => $autreEnseignant->id]);

        $response = $this->actingAs($enseignant)->delete("/enseignant/indisponibilites/{$indisponibilite->id}");

        $response->assertStatus(403);
        $this->assertDatabaseHas('indisponibilites', ['id' => $indisponibilite->id]);
    }

    public function test_un_etudiant_ne_peut_pas_acceder_aux_routes_indisponibilites(): void
    {
        $etudiant = User::factory()->create(['role' => 'etudiant']);

        $response = $this->actingAs($etudiant)->post('/enseignant/indisponibilites', [
            'date_debut' => '2026-08-01',
            'date_fin' => '2026-08-05',
        ]);

        $response->assertStatus(403);
    }
}
