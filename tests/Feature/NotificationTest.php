<?php

namespace Tests\Feature;

use App\Models\Notification;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NotificationTest extends TestCase
{
    use RefreshDatabase;

    public function test_un_utilisateur_non_authentifie_est_redirige_vers_login(): void
    {
        $response = $this->get('/notifications');

        $response->assertRedirect('/login');
    }

    public function test_un_utilisateur_peut_marquer_sa_notification_comme_lue(): void
    {
        $user = User::factory()->create();
        $notification = Notification::factory()->create(['utilisateur_id' => $user->id]);

        $response = $this->actingAs($user)->put("/notifications/{$notification->id}/read");

        $response->assertRedirect();
        $this->assertTrue($notification->refresh()->lu);
        $this->assertNotNull($notification->lu_le);
    }

    public function test_un_utilisateur_ne_peut_pas_marquer_la_notification_dun_autre(): void
    {
        $user = User::factory()->create();
        $autreUser = User::factory()->create();
        $notification = Notification::factory()->create(['utilisateur_id' => $autreUser->id]);

        $response = $this->actingAs($user)->put("/notifications/{$notification->id}/read");

        $response->assertStatus(403);
        $this->assertFalse($notification->refresh()->lu);
    }
}
