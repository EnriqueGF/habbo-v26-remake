<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;

/**
 * Páginas legales estáticas (reemplaza legacy/privacy.php y legacy/disclaimer.php).
 * Solo texto/HTML estático: el chrome lo aporta el layout community. Sin SQL.
 */
class PageController extends Controller
{
    public function privacy(): View
    {
        return view('pages.privacy');
    }

    public function disclaimer(): View
    {
        return view('pages.disclaimer');
    }
}
