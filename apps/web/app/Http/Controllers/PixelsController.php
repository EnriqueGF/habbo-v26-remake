<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;

/**
 * Página informativa de píxeles (reemplaza legacy/pixels.php). Es estática:
 * explica cómo ganar y gastar píxeles. Permite invitados.
 */
class PixelsController extends Controller
{
    public function index(): View
    {
        return view('pixels.index');
    }
}
