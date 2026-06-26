<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class HelpTest extends TestCase
{
    use DatabaseTransactions;

    public function test_help_page_is_served_by_laravel(): void
    {
        $this->get('/help')
            ->assertOk();
    }

    public function test_category_and_its_item_are_displayed(): void
    {
        $token = 'T'.uniqid();
        $catTitle = 'Categoria '.$token;
        $itemTitle = 'Pregunta '.$token;
        $itemContent = 'Respuesta '.$token;

        $catId = DB::table('cms_faq')->insertGetId([
            'type' => 'cat',
            'catid' => 0,
            'title' => $catTitle,
            'content' => 'Descripcion '.$token,
        ], 'id');

        DB::table('cms_faq')->insert([
            'type' => 'item',
            'catid' => $catId,
            'title' => $itemTitle,
            'content' => $itemContent,
        ]);

        // La categoría aparece en el listado de la página principal.
        $this->get('/help')
            ->assertOk()
            ->assertSee($catTitle);

        // Al abrir la categoría se muestran sus preguntas.
        $this->get('/help?id='.$catId)
            ->assertOk()
            ->assertSee($itemTitle)
            ->assertSee($itemContent);
    }
}
