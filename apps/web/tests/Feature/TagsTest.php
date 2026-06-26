<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class TagsTest extends TestCase
{
    use DatabaseTransactions;

    public function test_tags_page_is_served_by_laravel(): void
    {
        $this->get('/tags')
            ->assertOk()
            ->assertSee('Etiquetas aleatorias', false)
            ->assertSee('Buscar etiquetas', false);
    }

    public function test_tags_search_lists_user_with_that_tag(): void
    {
        $userId = DB::table('users')->insertGetId([
            'name' => 'TagSearchUser'.uniqid(),
            'password' => 'x',
            'rank' => 1,
            'email' => 'test@example.com',
            'birth' => '2000-01-01',
            'hbirth' => date('Y-m-d'),
            'figure' => 'hr-100',
            'sex' => 'M',
            'mission' => 'Hola',
            'guideavailable' => 0,
            'shockwaveid' => '',
            'guide' => 0,
            'guide-allowed' => 0,
            'window' => 0,
        ]);

        DB::table('cms_tags')->insert([
            'ownerid' => $userId,
            'tag' => 'pruebatag',
        ]);

        $name = DB::table('users')->where('id', $userId)->value('name');

        $this->get('/tags?tag=pruebatag')
            ->assertOk()
            ->assertSee($name, false)
            ->assertSee('pruebatag', false);
    }
}
