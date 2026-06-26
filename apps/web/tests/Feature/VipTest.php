<?php

namespace Tests\Feature;

use Tests\TestCase;

class VipTest extends TestCase
{
    public function test_vip_page_is_served_by_laravel(): void
    {
        $this->get('/vip')
            ->assertOk()
            ->assertSee('VIP Club', false);
    }
}
