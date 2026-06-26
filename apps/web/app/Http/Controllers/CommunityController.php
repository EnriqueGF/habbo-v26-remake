<?php

namespace App\Http\Controllers;

use App\Models\News;
use App\Models\Room;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;

/**
 * Página de comunidad (reemplaza legacy/community.php). Vista de solo lectura:
 * salas más visitadas, usuarios aleatorios, titulares de noticias y nube de
 * tags. Todas las consultas usan Eloquent / query builder parametrizado.
 */
class CommunityController extends Controller
{
    public function index(): View
    {
        // Salas del momento: las más visitadas con propietario.
        $rooms = Room::query()
            ->whereNotNull('owner')
            ->where('owner', '!=', '')
            ->orderByDesc('visitors_now')
            ->limit(5)
            ->get();

        // Usuarios aleatorios para el mapa de "Habbos aleatorios".
        $users = User::query()
            ->inRandomOrder()
            ->limit(18)
            ->get();

        // Titulares de noticias recientes (la promo del legacy usaba 4).
        $news = News::query()
            ->orderByDesc('num')
            ->limit(4)
            ->get();

        // Nube de tags: los 20 más usados (cms_tags vía query builder).
        $tags = DB::table('cms_tags')
            ->select('tag', DB::raw('COUNT(id) AS quantity'))
            ->groupBy('tag')
            ->orderByDesc('quantity')
            ->limit(20)
            ->get();

        return view('community.index', compact('rooms', 'users', 'news', 'tags'));
    }
}
