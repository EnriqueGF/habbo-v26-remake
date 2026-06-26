<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class NewsTest extends TestCase
{
    use DatabaseTransactions;

    public function test_news_archive_is_served_by_laravel(): void
    {
        $this->get('/news')
            ->assertOk()
            ->assertSee('Las noticias');
    }

    public function test_single_article_is_displayed(): void
    {
        $num = DB::table('cms_news')->insertGetId([
            'title' => 'Noticia de prueba',
            'category' => 'general',
            'topstory' => '0',
            'short_story' => 'Resumen de prueba',
            'story' => 'Cuerpo de la noticia de prueba.',
            'date' => date('Y-m-d'),
            'author' => 'admin',
        ], 'num');

        $this->get('/news?id='.$num)
            ->assertOk()
            ->assertSee('Noticia de prueba')
            ->assertSee('Cuerpo de la noticia de prueba.');
    }
}
