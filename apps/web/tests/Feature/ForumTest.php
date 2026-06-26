<?php

declare(strict_types=1);

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class ForumTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * Inserta un hilo del foro global y devuelve su título (único por test).
     * Rellena todas las columnas NOT NULL de cms_forum_threads. `title` es
     * varchar(30), así que el título de prueba se mantiene corto.
     */
    private function insertThread(): string
    {
        $title = 'Hilo de prueba '.uniqid();
        $title = substr($title, 0, 30);

        DB::table('cms_forum_threads')->insert([
            'type' => 1,
            'title' => $title,
            'author' => 'admin',
            'date' => '01-01-2026 12:00',
            'lastpost_author' => 'admin',
            'lastpost_date' => '01-01-2026 12:00',
            'views' => 0,
            'posts' => 0,
            'unix' => (string) time(),
            'forumid' => 0,
        ]);

        return $title;
    }

    public function test_forum_page_shows_a_global_thread(): void
    {
        $title = $this->insertThread();

        $this->get('/forum')
            ->assertOk()
            ->assertSee($title, false);
    }

    public function test_forum_page_is_served_to_guests(): void
    {
        // El foro permite invitados ($allow_guests = true en el legacy).
        $this->get('/forum')
            ->assertOk();
    }
}
