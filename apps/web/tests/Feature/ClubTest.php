<?php

namespace Tests\Feature;

use App\Support\HoloHash;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class ClubTest extends TestCase
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

    public function test_club_page_is_served_when_logged_in(): void
    {
        $this->loginAs('admin');

        $this->get('/club')
            ->assertOk()
            ->assertSee('Club');
    }

    public function test_purchasing_one_month_deducts_credits_and_records_transaction(): void
    {
        $user = $this->loginAs('admin');

        $creditsBefore = (int) $user->credits;
        $txBefore = (int) DB::table('cms_transactions')
            ->where('userid', $user->id)
            ->where('descr', 'Club subscription')
            ->count();

        $this->post('/club/purchase', ['months' => 1])
            ->assertRedirect();

        $creditsAfter = (int) DB::table('users')->where('id', $user->id)->value('credits');
        $this->assertSame($creditsBefore - 20, $creditsAfter, 'La compra debe deducir 20 créditos.');

        $txAfter = (int) DB::table('cms_transactions')
            ->where('userid', $user->id)
            ->where('descr', 'Club subscription')
            ->count();
        $this->assertSame($txBefore + 1, $txAfter, 'La compra debe registrar una transacción nueva.');
    }
}
