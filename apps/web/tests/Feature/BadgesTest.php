<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class BadgesTest extends TestCase
{
    use DatabaseTransactions;

    public function test_badges_page_is_served_by_laravel(): void
    {
        $this->get('/badges')
            ->assertOk()
            ->assertSee('Tienda de placas', false)
            ->assertSee('/Badges/HC2.gif', false);
    }
}
