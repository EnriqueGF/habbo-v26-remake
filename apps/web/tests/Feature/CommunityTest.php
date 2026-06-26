<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class CommunityTest extends TestCase
{
    use DatabaseTransactions;

    public function test_community_page_is_served_by_laravel(): void
    {
        $this->get('/community')
            ->assertOk()
            ->assertSee('Salas del momento', false)
            ->assertSee('aleatorios', false);
    }

    public function test_community_page_shows_recent_news_headline(): void
    {
        $num = DB::table('cms_news')->insertGetId([
            'title' => 'Titular de comunidad de prueba',
            'category' => 'general',
            'topstory' => '0',
            'short_story' => 'Resumen de prueba',
            'story' => 'Cuerpo de la noticia de prueba.',
            'date' => date('Y-m-d'),
            'author' => 'admin',
        ], 'num');

        $this->get('/community')
            ->assertOk()
            ->assertSee('Titular de comunidad de prueba', false)
            ->assertSee('/news?id='.$num, false);
    }
}
