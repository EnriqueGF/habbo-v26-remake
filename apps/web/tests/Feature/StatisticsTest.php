<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class StatisticsTest extends TestCase
{
    use DatabaseTransactions;

    public function test_statistics_page_is_served_by_laravel(): void
    {
        $this->get('/statistics')
            ->assertOk()
            ->assertSee('Acerca del hotel', false)
            ->assertSee('Usuarios registrados', false);
    }
}
