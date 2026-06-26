<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class StaffTest extends TestCase
{
    use DatabaseTransactions;

    public function test_staff_page_is_served_by_laravel(): void
    {
        $this->get('/staff')
            ->assertOk()
            ->assertSee('El equipo', false)
            ->assertSee('Los Administradores', false)
            ->assertSee('Los Moderadores', false);
    }
}
