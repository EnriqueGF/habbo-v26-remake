<?php

namespace Tests\Feature;

use App\Support\HoloHash;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class DeleteHandTest extends TestCase
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

    public function test_deletehand_page_is_served_when_logged_in(): void
    {
        $this->loginAs('admin');

        $this->get('/deletehand')
            ->assertOk()
            ->assertSee('Vaciar la mano', false);
    }

    public function test_emptying_hand_deletes_furniture_and_redirects(): void
    {
        $user = $this->loginAs('admin');

        // Muebles en la mano del usuario (roomid='0'): deben desaparecer.
        DB::table('furniture')->insert([
            'tid' => 1183,
            'ownerid' => $user->id,
            'roomid' => 0,
            'x' => 0,
            'y' => 0,
            'z' => 0,
        ]);

        $this->post('/deletehand')
            ->assertRedirect(route('deletehand'));

        $inHand = (int) DB::table('furniture')
            ->where('ownerid', $user->id)
            ->where('roomid', '0')
            ->count();

        $this->assertSame(0, $inHand, 'Vaciar la mano debe borrar los muebles con roomid=0 del usuario.');
    }
}
