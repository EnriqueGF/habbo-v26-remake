<?php

namespace App\Http\Controllers;

use App\Models\Faq;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

/**
 * Página de ayuda/FAQ (reemplaza legacy/help.php). Lee `cms_faq` con
 * Eloquent/Query Builder parametrizado (sin SQLi).
 *
 * - Siempre lista las categorías (type = 'cat').
 * - Con ?id= se muestra la categoría seleccionada y sus preguntas (type = 'item').
 * - Con ?query= se buscan preguntas por título o contenido.
 */
class HelpController extends Controller
{
    public function index(Request $request): View
    {
        $categories = Faq::query()
            ->where('type', 'cat')
            ->orderBy('id')
            ->get();

        $category = null;
        $items = collect();
        $results = null;
        $query = null;

        if ($request->filled('id')) {
            $category = Faq::query()
                ->where('type', 'cat')
                ->where('id', $request->query('id'))
                ->first();

            if ($category !== null) {
                $items = Faq::query()
                    ->where('type', 'item')
                    ->where('catid', $category->id)
                    ->orderBy('id')
                    ->get();
            }
        } elseif ($request->filled('query')) {
            $query = (string) $request->input('query');

            $results = Faq::query()
                ->where('type', 'item')
                ->where(function ($q) use ($query) {
                    $q->where('title', 'like', '%'.$query.'%')
                        ->orWhere('content', 'like', '%'.$query.'%');
                })
                ->orderBy('id')
                ->get();
        }

        return view('help.index', compact('categories', 'category', 'items', 'results', 'query'));
    }
}
