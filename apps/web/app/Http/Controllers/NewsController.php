<?php

namespace App\Http\Controllers;

use App\Models\News;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

/**
 * Noticias del hotel (reemplaza legacy/news.php). Lee `cms_news` con Eloquent
 * (sin SQLi). Muestra un artículo concreto o el archivo de noticias.
 */
class NewsController extends Controller
{
    public function index(Request $request): View
    {
        $latest = News::query()->orderByDesc('num')->limit(10)->get();

        $article = null;
        $archive = null;
        $category = null;

        if ($request->filled('id')) {
            $article = News::query()->where('num', $request->query('id'))->first();
            if ($article === null) {
                $archive = News::query()->orderByDesc('num')->get();
            }
        } elseif ($request->filled('category')) {
            $category = (string) $request->query('category');
            $archive = News::query()->where('category', $category)->orderByDesc('num')->limit(25)->get();
        } else {
            $archive = News::query()->orderByDesc('num')->get();
        }

        return view('news.index', compact('latest', 'article', 'archive', 'category'));
    }
}
