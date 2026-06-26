<?php

namespace Tests\Feature;

use App\Support\HoloHash;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class AccountTest extends TestCase
{
    use DatabaseTransactions;

    /** Simula el login legacy escribiendo la sesión PHP nativa que lee core.php. */
    private function loginAsAdmin(): void
    {
        $_SESSION['username'] = 'admin';
        $_SESSION['password'] = HoloHash::make('admin');
    }

    protected function tearDown(): void
    {
        unset($_SESSION['username'], $_SESSION['password']);
        parent::tearDown();
    }

    public function test_account_page_redirects_when_not_logged_in(): void
    {
        $this->get('/account')->assertRedirect();
    }

    public function test_account_page_is_served_when_logged_in(): void
    {
        $this->loginAsAdmin();

        $this->get('/account')
            ->assertOk()
            ->assertSee('Mis preferencias');
    }

    public function test_password_is_changed_with_correct_current_password(): void
    {
        $this->loginAsAdmin();

        $this->post('/account/password', [
            'current_password' => 'admin',
            'new_password' => 'nuevapass',
            'new_password_confirmation' => 'nuevapass',
        ])->assertRedirect();

        $stored = DB::table('users')->where('name', 'admin')->value('password');

        $this->assertSame(HoloHash::make('nuevapass'), $stored);
    }

    public function test_password_is_not_changed_with_wrong_current_password(): void
    {
        $this->loginAsAdmin();

        $original = DB::table('users')->where('name', 'admin')->value('password');

        $this->post('/account/password', [
            'current_password' => 'contrasena-incorrecta',
            'new_password' => 'nuevapass',
            'new_password_confirmation' => 'nuevapass',
        ])->assertRedirect();

        $stored = DB::table('users')->where('name', 'admin')->value('password');

        $this->assertSame($original, $stored);
        $this->assertSame(HoloHash::make('admin'), $stored);
    }
}
