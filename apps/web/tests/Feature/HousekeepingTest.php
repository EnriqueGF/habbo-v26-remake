<?php

namespace Tests\Feature;

use App\Support\HoloHash;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

/**
 * Cubre el control de acceso del panel de housekeeping (lo más crítico de seguridad):
 * solo el staff (rango > 5) entra, el guard revalida en cada petición, y la sesión
 * `acp` es la que comparte con el legacy.
 */
class HousekeepingTest extends TestCase
{
    use DatabaseTransactions;

    protected function setUp(): void
    {
        parent::setUp();
        // Cada test arranca sin sesión legacy.
        $_SESSION = [];
    }

    /** Inicia la sesión de admin (acp) como lo haría un login válido. */
    private function startAdminSession(string $name, string $hash): void
    {
        $_SESSION['acp'] = true;
        $_SESSION['hkusername'] = $name;
        $_SESSION['hkpassword'] = $hash;
    }

    /** Crea un usuario no-staff copiando la fila admin (satisface las NOT NULL). */
    private function createLowRankUser(string $name, string $plainPassword): object
    {
        $admin = (array) DB::table('users')->where('name', 'admin')->first();
        unset($admin['id']);
        $admin['name'] = $name;
        $admin['rank'] = 1;
        $admin['password'] = HoloHash::make($plainPassword);
        $id = DB::table('users')->insertGetId($admin);

        return DB::table('users')->find($id);
    }

    public function test_login_page_shown_when_not_authenticated(): void
    {
        $this->get('/housekeeping')
            ->assertOk()
            ->assertSee('Bienvenido al panel', false);
    }

    public function test_dashboard_redirects_to_login_without_session(): void
    {
        $this->get('/housekeeping/dashboard')
            ->assertRedirect(route('hk.login'));
    }

    public function test_staff_can_log_in_and_reach_dashboard(): void
    {
        $this->post('/housekeeping/login', ['username' => 'admin', 'password' => 'admin'])
            ->assertRedirect(route('hk.dashboard'));

        $this->assertTrue(! empty($_SESSION['acp']), 'El login válido debe fijar la sesión acp.');
        $this->assertSame('admin', $_SESSION['hkusername']);

        $this->get('/housekeeping/dashboard')
            ->assertOk()
            ->assertSee('Housekeeping', false)
            ->assertSee('Resumen del sistema', false);
    }

    public function test_non_staff_cannot_log_in(): void
    {
        $this->createLowRankUser('peoncito', 'secret123');

        $this->post('/housekeeping/login', ['username' => 'peoncito', 'password' => 'secret123'])
            ->assertOk()
            ->assertSee('incorrecta', false);

        $this->assertTrue(empty($_SESSION['acp']), 'Un usuario sin rango de staff no debe obtener sesión acp.');
    }

    public function test_guard_rejects_low_rank_session(): void
    {
        $low = $this->createLowRankUser('peoncito2', 'secret123');
        // Aunque alguien forje la sesión acp con un usuario de rango bajo,
        // el guard revalida rango > 5 contra la BD y lo rechaza.
        $this->startAdminSession($low->name, $low->password);

        $this->get('/housekeeping/dashboard')
            ->assertRedirect(route('hk.login'));
    }

    public function test_wrong_password_rejected(): void
    {
        $this->post('/housekeeping/login', ['username' => 'admin', 'password' => 'definitelywrong'])
            ->assertOk()
            ->assertSee('incorrecta', false);

        $this->assertTrue(empty($_SESSION['acp']));
    }

    public function test_logout_clears_admin_session(): void
    {
        $this->startAdminSession('admin', HoloHash::make('admin'));

        $this->get('/housekeeping/logout')
            ->assertOk()
            ->assertSee('cerrada', false);

        $this->assertTrue(empty($_SESSION['acp']), 'El logout debe limpiar la sesión acp.');
    }
}
