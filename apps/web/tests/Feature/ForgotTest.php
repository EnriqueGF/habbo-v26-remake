<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

/**
 * Recuperación de contraseña nativa (reemplaza legacy/forgot.php). Usa la BD de
 * test v26_test con transacciones que se revierten, de modo que el cambio de hash
 * de admin no persiste. En tests $this->post no requiere token CSRF.
 */
class ForgotTest extends TestCase
{
    use DatabaseTransactions;

    public function test_forgot_form_is_served(): void
    {
        $this->get('/forgot')
            ->assertOk()
            ->assertSee('olvidado tu contrase', false);
    }

    public function test_matching_name_and_email_resets_the_password(): void
    {
        $email = DB::table('users')->where('name', 'admin')->value('email');
        $original = DB::table('users')->where('name', 'admin')->value('password');

        $this->post('/forgot', [
            'name' => 'admin',
            'email' => $email,
        ])->assertOk();

        $stored = DB::table('users')->where('name', 'admin')->value('password');

        $this->assertNotSame($original, $stored);
    }

    public function test_wrong_email_does_not_change_the_password(): void
    {
        $original = DB::table('users')->where('name', 'admin')->value('password');

        $this->post('/forgot', [
            'name' => 'admin',
            'email' => 'definitely-not-the-right-email@example.com',
        ])->assertOk()->assertSee('no v', false);

        $stored = DB::table('users')->where('name', 'admin')->value('password');

        $this->assertSame($original, $stored);
    }
}
