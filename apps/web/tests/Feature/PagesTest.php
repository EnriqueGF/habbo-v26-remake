<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class PagesTest extends TestCase
{
    use DatabaseTransactions;

    public function test_privacy_page_is_served_by_laravel(): void
    {
        $this->get('/privacy')
            ->assertOk()
            ->assertSee('Información práctica');
    }

    public function test_disclaimer_page_is_served_by_laravel(): void
    {
        $this->get('/disclaimer')
            ->assertOk()
            ->assertSee('Términos de uso');
    }
}
