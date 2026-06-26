<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class UserProfileTest extends TestCase
{
    use DatabaseTransactions;

    public function test_profile_page_is_served_by_laravel_for_existing_user(): void
    {
        $this->get('/profile?name=admin')
            ->assertOk()
            ->assertSee('admin', false);
    }

    public function test_profile_page_can_be_resolved_by_id(): void
    {
        // admin tiene id=1 en la BD de pruebas.
        $this->get('/profile?id=1')
            ->assertOk()
            ->assertSee('admin', false);
    }

    public function test_unknown_user_shows_not_found_message_without_error(): void
    {
        $this->get('/profile?name=__noexiste__')
            ->assertOk()
            ->assertSee('no encontrada', false);
    }
}
