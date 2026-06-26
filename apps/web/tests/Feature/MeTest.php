<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class MeTest extends TestCase
{
    use DatabaseTransactions;

    protected function tearDown(): void
    {
        // Limpia la sesión legacy simulada entre tests.
        unset($_SESSION['username'], $_SESSION['password']);

        parent::tearDown();
    }

    /** Simula el login legacy (la sesión PHP nativa es la fuente de verdad). */
    private function loginAsAdmin(): void
    {
        $_SESSION['username'] = 'admin';
        $_SESSION['password'] = md5('235x17aXCaRb'.'admin');
    }

    public function test_me_requires_login_and_redirects_anonymous_users(): void
    {
        unset($_SESSION['username'], $_SESSION['password']);

        $this->get('/me')->assertStatus(302);
    }

    public function test_me_dashboard_is_served_by_laravel_for_logged_in_user(): void
    {
        $this->loginAsAdmin();

        $this->get('/me')
            ->assertOk()
            ->assertSee('admin', false)
            ->assertSee('Mis mensajes', false);
    }

    public function test_remove_feed_item_only_deletes_own_alert(): void
    {
        $this->loginAsAdmin();

        $adminId = (int) DB::table('users')->where('name', 'admin')->value('id');

        $ownAlertId = DB::table('cms_alerts')->insertGetId([
            'userid' => $adminId,
            'alert' => 'Alerta de prueba propia',
            'type' => '1',
        ]);

        // Alerta de otro usuario: no debe poder borrarse.
        $otherAlertId = DB::table('cms_alerts')->insertGetId([
            'userid' => $adminId + 999999,
            'alert' => 'Alerta de otro usuario',
            'type' => '1',
        ]);

        $this->post('/me/feed/remove', ['key' => $ownAlertId])
            ->assertStatus(302);

        $this->assertDatabaseMissing('cms_alerts', ['id' => $ownAlertId]);
        $this->assertDatabaseHas('cms_alerts', ['id' => $otherAlertId]);
    }
}
