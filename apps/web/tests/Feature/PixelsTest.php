<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class PixelsTest extends TestCase
{
    use DatabaseTransactions;

    public function test_pixels_page_is_served_by_laravel(): void
    {
        $this->get('/pixels')
            ->assertOk()
            ->assertSee('&iexcl;Aprende c&oacute;mo ganar p&iacute;xeles!', false)
            ->assertSee('&iquest;C&oacute;mo ganar p&iacute;xeles?', false);
    }
}
