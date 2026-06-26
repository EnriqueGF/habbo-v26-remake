<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\RateLimiter;
use Tests\TestCase;

/**
 * Login/logout nativos. El formulario legacy hace POST a index.php, que Apache
 * sirve vía public/index.php (front controller), por lo que Laravel lo recibe como
 * POST "/". Los tests envían a "/" para reflejar exactamente eso.
 *
 * Usan la BD de test v26_test (InnoDB) con transacciones que se revierten, y la
 * cuenta admin/admin de la semilla. La "sesión legacy" es la sesión PHP nativa
 * ($_SESSION), que en CLI es un array normal inspeccionable.
 */
class LoginTest extends TestCase
{
    use DatabaseTransactions;

    protected function setUp(): void
    {
        parent::setUp();
        $_SESSION = [];
        RateLimiter::clear('login:127.0.0.1|admin');
    }

    public function test_valid_credentials_authenticate_and_set_the_legacy_session(): void
    {
        $response = $this->post('/', ['username' => 'admin', 'password' => 'admin']);

        $response->assertRedirectContains('security_check.php');
        $this->assertSame('admin', $_SESSION['username'] ?? null);
        $this->assertSame(md5('235x17aXCaRb'.'admin'), $_SESSION['password'] ?? null);
    }

    public function test_invalid_password_is_rejected_without_a_session(): void
    {
        $response = $this->post('/', ['username' => 'admin', 'password' => 'wrong-password']);

        $response->assertRedirectContains('error=1');
        $this->assertArrayNotHasKey('username', $_SESSION);
    }

    public function test_empty_fields_redirect_with_error(): void
    {
        $this->post('/', ['username' => '', 'password' => ''])
            ->assertRedirectContains('error=1');
    }

    public function test_unknown_user_is_rejected(): void
    {
        $this->post('/', ['username' => 'no_such_user_xyz', 'password' => 'whatever'])
            ->assertRedirectContains('error=1');
    }

    public function test_logout_clears_the_legacy_session(): void
    {
        $_SESSION['username'] = 'admin';
        $_SESSION['password'] = 'whatever';

        $this->post('/logout')->assertRedirectContains('/');

        $this->assertArrayNotHasKey('username', $_SESSION);
    }
}
