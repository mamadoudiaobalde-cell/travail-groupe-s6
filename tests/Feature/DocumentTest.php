<?php

namespace Tests\Feature;

use App\Models\Document;
use App\Models\Jury;
use App\Models\Soutenance;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class DocumentTest extends TestCase
{
    use RefreshDatabase;

    public function test_un_utilisateur_non_authentifie_est_redirige_vers_login(): void
    {
        $document = Document::factory()->create();

        $response = $this->get("/documents/{$document->id}/download");

        $response->assertRedirect('/login');
    }

    public function test_letudiant_concerne_peut_telecharger_son_document(): void
    {
        Storage::fake('public');

        $soutenance = Soutenance::factory()->create();
        $document = Document::factory()->create([
            'soutenance_id' => $soutenance->id,
            'chemin_fichier' => 'documents/test.pdf',
        ]);
        Storage::disk('public')->put('documents/test.pdf', 'contenu-pdf');

        $etudiant = User::find($soutenance->etudiant_id);

        $response = $this->actingAs($etudiant)->get("/documents/{$document->id}/download");

        $response->assertOk();
    }

    public function test_un_autre_etudiant_ne_peut_pas_telecharger_le_document(): void
    {
        Storage::fake('public');

        $soutenance = Soutenance::factory()->create();
        $document = Document::factory()->create([
            'soutenance_id' => $soutenance->id,
            'chemin_fichier' => 'documents/test.pdf',
        ]);
        Storage::disk('public')->put('documents/test.pdf', 'contenu-pdf');

        $autreEtudiant = User::factory()->create(['role' => 'etudiant']);

        $response = $this->actingAs($autreEtudiant)->get("/documents/{$document->id}/download");

        $response->assertStatus(403);
    }

    public function test_un_membre_du_jury_peut_telecharger_le_document(): void
    {
        Storage::fake('public');

        $soutenance = Soutenance::factory()->create();
        $document = Document::factory()->create([
            'soutenance_id' => $soutenance->id,
            'chemin_fichier' => 'documents/test.pdf',
        ]);
        Storage::disk('public')->put('documents/test.pdf', 'contenu-pdf');

        $enseignant = User::factory()->create(['role' => 'enseignant']);
        Jury::factory()->create(['soutenance_id' => $soutenance->id, 'utilisateur_id' => $enseignant->id]);

        $response = $this->actingAs($enseignant)->get("/documents/{$document->id}/download");

        $response->assertOk();
    }

    public function test_la_secretaire_peut_telecharger_nimporte_quel_document(): void
    {
        Storage::fake('public');

        $document = Document::factory()->create(['chemin_fichier' => 'documents/test.pdf']);
        Storage::disk('public')->put('documents/test.pdf', 'contenu-pdf');

        $secretaire = User::factory()->create(['role' => 'secretaire_pedagogique']);

        $response = $this->actingAs($secretaire)->get("/documents/{$document->id}/download");

        $response->assertOk();
    }
}
