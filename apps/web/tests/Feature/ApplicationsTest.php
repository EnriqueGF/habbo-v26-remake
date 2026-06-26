<?php

namespace Tests\Feature;

use App\Support\HoloHash;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class ApplicationsTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * Simula al usuario logueado escribiendo la sesión PHP nativa que lee el
     * middleware legacy.user (vía LegacySession): username + hash HoloHash.
     */
    private function loginAs(string $name): object
    {
        $user = DB::table('users')->where('name', $name)->first();
        $this->assertNotNull($user, "El usuario de prueba '{$name}' no existe en la BD de test.");

        $_SESSION['username'] = $user->name;
        $_SESSION['password'] = HoloHash::make('admin');

        return $user;
    }

    /** Inserta un formulario abierto de prueba y devuelve su id. */
    private function seedForm(array $overrides = []): int
    {
        return (int) DB::table('cms_application_forms')->insertGetId(array_merge([
            'name' => 'Reclutador de prueba',
            'introduction' => 'Intro de prueba',
            'requirements' => 'Requisitos de prueba',
            'hconly' => 0,
            'username' => 1,
            'realname' => 1,
            'birth' => 1,
            'sex' => 1,
            'country' => 1,
            'general_information' => 1,
            'experience' => 1,
            'education' => 1,
            'additional_information' => 1,
            'show_disclaimer' => 1,
            'disclaimer_text' => 'Acepto los terminos.',
            'enabled' => 1,
            'deleted' => 0,
        ], $overrides));
    }

    public function test_index_lists_open_form_when_logged_in(): void
    {
        $this->loginAs('admin');
        $this->seedForm(['name' => 'Reclutador abierto XYZ']);

        $this->get('/applications')
            ->assertOk()
            ->assertSee('Solicitudes', false)
            ->assertSee('Reclutador abierto XYZ', false);
    }

    public function test_show_renders_form_detail(): void
    {
        $this->loginAs('admin');
        $id = $this->seedForm(['name' => 'Detalle Form ABC']);

        $this->get('/applications/'.$id)
            ->assertOk()
            ->assertSee('Solicitud: Detalle Form ABC', false)
            ->assertSee("name='general_information'", false)
            ->assertSee('Enviar solicitud', false);
    }

    public function test_show_closed_form_shows_closed_message(): void
    {
        $this->loginAs('admin');
        $id = $this->seedForm(['enabled' => 0]);

        $this->get('/applications/'.$id)
            ->assertOk()
            ->assertSee('Esta solicitud est', false);
    }

    public function test_submit_inserts_application(): void
    {
        $user = $this->loginAs('admin');
        $id = $this->seedForm(['name' => 'Puesto Enviado QWE']);

        $before = (int) DB::table('cms_applications')
            ->where('rankname', 'Puesto Enviado QWE')
            ->count();

        $this->post('/applications/'.$id, [
            'realname' => 'Test Real Name',
            'sex' => 'Hombre',
            'country' => 'Espana',
            'general_information' => 'Quiero ayudar.',
            'experience' => 'Algo de experiencia.',
            'education' => 'Universidad',
            'additional_information' => 'Aficiones varias.',
            'agreement' => 'on',
        ])
            ->assertOk()
            ->assertSee('Tu solicitud ha sido enviada', false);

        $after = (int) DB::table('cms_applications')
            ->where('rankname', 'Puesto Enviado QWE')
            ->count();
        $this->assertSame($before + 1, $after, 'El envio debe insertar una solicitud nueva.');

        $row = DB::table('cms_applications')
            ->where('rankname', 'Puesto Enviado QWE')
            ->orderByDesc('id')
            ->first();

        $this->assertNotNull($row);
        $this->assertSame($user->name, $row->username);
        $this->assertSame('Test Real Name', $row->realname);
        $this->assertSame('Espana', $row->country);
        $this->assertSame(1, (int) $row->accepted_disclaimer);
    }
}
