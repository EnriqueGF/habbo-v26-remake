<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;

/**
 * Página del VIP Club (reemplaza legacy/vip.php). Informativa/estática.
 */
class VipController extends Controller
{
    public function index(): View
    {
        return view('vip.index');
    }
}
