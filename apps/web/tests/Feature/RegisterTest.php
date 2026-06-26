<?php

namespace Tests\Feature;

use App\Support\HoloHash;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

/**
 * Registro nativo (reemplaza legacy/register.php). Usa la BD de test v26_test con
 * transacciones que se revierten, de modo que las cuentas creadas no persisten.
 * En tests $this->post no requiere token CSRF.
 */
class RegisterTest extends TestCase
{
    use DatabaseTransactions;

    protected function setUp(): void
    {
        parent::setUp();
        $_SESSION = [];
    }

    protected function tearDown(): void
    {
        unset($_SESSION['username'], $_SESSION['password']);
        parent::tearDown();
    }

    public function test_register_form_is_served(): void
    {
        $this->get('/register')
            ->assertOk()
            ->assertSee('Crea tu avatar');
    }

    public function test_valid_registration_creates_the_user_and_logs_in(): void
    {
        $name = 'test'.substr(uniqid(), -8);

        $response = $this->post('/register', [
            'name' => $name,
            'password' => 'secret123',
            'password_confirmation' => 'secret123',
            'email' => $name.'@example.com',
            'sex' => 'M',
            'bean_day' => '5',
            'bean_month' => '6',
            'bean_year' => '1995',
            'terms' => 'true',
        ]);

        $response->assertRedirect();

        $user = DB::table('users')->where('name', $name)->first();
        $this->assertNotNull($user);
        $this->assertSame(HoloHash::make('secret123'), $user->password);
        $this->assertSame('M', $user->sex);
        $this->assertSame('5-6-1995', $user->birth);
        $this->assertSame($name, $_SESSION['username'] ?? null);
    }

    public function test_duplicate_name_is_rejected_and_creates_nothing(): void
    {
        $before = DB::table('users')->count();

        $this->post('/register', [
            'name' => 'admin',
            'password' => 'secret123',
            'password_confirmation' => 'secret123',
            'email' => 'someone@example.com',
            'sex' => 'M',
            'bean_day' => '5',
            'bean_month' => '6',
            'bean_year' => '1995',
            'terms' => 'true',
        ])->assertSessionHasErrors('name');

        $this->assertSame($before, DB::table('users')->count());
    }
}
