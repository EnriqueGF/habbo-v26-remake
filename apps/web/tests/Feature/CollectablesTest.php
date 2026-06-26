<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class CollectablesTest extends TestCase
{
    use DatabaseTransactions;

    public function test_collectables_page_is_served_by_laravel(): void
    {
        $this->get('/collectables')
            ->assertOk()
            ->assertSee('Coleccionable del mes', false)
            ->assertSee('&iquest;Qu&eacute; es un coleccionable?', false);
    }
}
