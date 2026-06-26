<?php

namespace Tests\Feature;

use Tests\TestCase;

/**
 * Rutas nativas del front controller (no pasan por el legacy). Usan la BD v26_test.
 */
class NativeRoutesTest extends TestCase
{
    public function test_health_endpoint_is_served_by_laravel(): void
    {
        $this->get('/health')
            ->assertOk()
            ->assertJson([
                'served_by' => 'laravel',
                'status' => 'ok',
            ])
            ->assertJsonStructure(['app', 'laravel', 'php', 'db']);
    }

    public function test_status_endpoint_reads_the_v26_database(): void
    {
        $response = $this->get('/status')->assertOk();

        $response->assertJson(['served_by' => 'laravel']);
        $this->assertIsInt($response->json('users_total'));
        $this->assertGreaterThanOrEqual(0, $response->json('users_total'));
    }
}
