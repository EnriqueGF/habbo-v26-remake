<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class CreditsTest extends TestCase
{
    use DatabaseTransactions;

    public function test_credits_page_is_served_by_laravel(): void
    {
        $this->get('/credits')
            ->assertOk()
            ->assertSee('Tu monedero', false)
            ->assertSee('cr&eacute;ditos', false);
    }
}
