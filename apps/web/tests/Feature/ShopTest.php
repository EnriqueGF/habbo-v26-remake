<?php

namespace Tests\Feature;

use App\Support\HoloHash;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class ShopTest extends TestCase
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

    public function test_shop_frontpage_is_served(): void
    {
        $this->get('/shop')
            ->assertOk()
            ->assertSee('Categor', false);
    }

    public function test_shop_category_is_served(): void
    {
        // Elegimos una categoría real visible para invitados (minrank <= 0/1).
        $category = DB::table('catalogue_pages')
            ->where('indexid', '!=', 1)
            ->where('minrank', '<=', 1)
            ->orderBy('indexid')
            ->value('indexid');

        $this->assertNotNull($category, 'No hay categorías de prueba en la BD.');

        $this->get('/shop?category='.$category)->assertOk();
    }

    public function test_purchase_deducts_credits_and_inserts_furniture_and_transaction(): void
    {
        $user = $this->loginAs('admin');

        // Mueble real barato (la transacción del test se revierte al terminar).
        $item = DB::table('catalogue_items')
            ->where('catalogue_id_page', '>', 0)
            ->where('catalogue_cost', '>', 0)
            ->orderBy('catalogue_cost')
            ->first();

        $this->assertNotNull($item, 'No hay muebles de prueba en la BD.');

        $creditsBefore = (int) DB::table('users')->where('id', $user->id)->value('credits');
        $furniBefore = (int) DB::table('furniture')->where('ownerid', $user->id)->count();
        $txBefore = (int) DB::table('cms_transactions')->where('userid', $user->id)->count();

        $this->post('/shop/purchase', ['furniID' => $item->tid, 'quantity' => 1])
            ->assertRedirect();

        $creditsAfter = (int) DB::table('users')->where('id', $user->id)->value('credits');
        $this->assertSame($creditsBefore - (int) $item->catalogue_cost, $creditsAfter, 'La compra debe deducir el precio del mueble.');

        $furniAfter = (int) DB::table('furniture')->where('ownerid', $user->id)->count();
        $this->assertSame($furniBefore + 1, $furniAfter, 'La compra debe insertar 1 fila en furniture.');

        $txAfter = (int) DB::table('cms_transactions')->where('userid', $user->id)->count();
        $this->assertSame($txBefore + 1, $txAfter, 'La compra debe registrar una transacción nueva.');
    }
}
