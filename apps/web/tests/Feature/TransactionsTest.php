<?php

namespace Tests\Feature;

use App\Support\HoloHash;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class TransactionsTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * Simula al usuario logueado escribiendo la sesión PHP nativa que lee el
     * middleware legacy.user (vía LegacySession): username + hash HoloHash.
     */
    private function loginAs(string $name): object
    {
        $user = DB::table('users')->where('name', $name)->first();
        $this->assertNotNull($user, "El usuario de prueba '{$name}' no existe en la BD de test.");

        $_SESSION['username'] = $user->name;
        $_SESSION['password'] = HoloHash::make('admin');

        return $user;
    }

    public function test_transactions_page_is_served_when_logged_in(): void
    {
        $this->loginAs('admin');

        $this->get('/transactions')
            ->assertOk()
            ->assertSee('Transacciones de tu cuenta', false)
            ->assertSee('Tu monedero', false);
    }
}
