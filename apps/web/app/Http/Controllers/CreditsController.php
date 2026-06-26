<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;

/**
 * Página de créditos (reemplaza legacy/credits.php). Es informativa: explica
 * cómo conseguir créditos y muestra el monedero del usuario si está logueado.
 * Los datos del usuario los aporta request()->user() (middleware legacy.user)
 * y se exponen a la vista mediante el ChromeComposer ($chromeUser/$loggedIn).
 */
class CreditsController extends Controller
{
    public function index(): View
    {
        return view('credits.index');
    }
}
