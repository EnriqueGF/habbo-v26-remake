<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\ForumThread;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

/**
 * Foro global (reemplaza legacy/forum.php). Lista los hilos de `forumid = 0`
 * paginados de 10 en 10, mostrando primero los stickies (type > 2) y luego los
 * hilos normales (type < 3), ambos ordenados por `unix` descendente.
 *
 * Reproduce fielmente el cálculo de offsets del legacy: los stickies ocupan
 * "huecos" en la página y el LIMIT de los hilos normales se desplaza en función
 * de cuántos stickies hay (de modo que el total visible por página sea 10).
 */
class ForumController extends Controller
{
    private const PER_PAGE = 10;

    public function index(Request $request): View|RedirectResponse
    {
        // Réplica de: if(HoloText(getContent('forum-enabled'), true) !== "1") redirect.
        // getContent() lee cms_content (contentkey/contentvalue). HoloText(..., true)
        // sólo aplica stripslashes, así que basta comparar el valor crudo con "1".
        // Si la clave no existe (BD sin sembrar), se trata como habilitado para no
        // romper la página.
        $forumEnabled = DB::table('cms_content')
            ->where('contentkey', 'forum-enabled')
            ->value('contentvalue');

        if ($forumEnabled !== null && stripslashes((string) $forumEnabled) !== '1') {
            return redirect()->to('/');
        }

        // Total de hilos del foro global y número de páginas.
        $total = ForumThread::query()->where('forumid', 0)->count();
        $pages = (int) ceil($total / self::PER_PAGE);

        // Normalizar la página al rango [1, $pages] (réplica: if($page > $pages || $page < 1) $page = 1).
        $page = (int) $request->query('page', '0');
        if ($pages < 1 || $page > $pages || $page < 1) {
            $page = 1;
        }

        // Stickies: siempre todos, ordenados por unix DESC.
        $stickyThreads = ForumThread::query()
            ->where('type', '>', 2)
            ->where('forumid', 0)
            ->orderByDesc('unix')
            ->get();

        $stickies = $stickyThreads->count();

        // Cálculo de offsets idéntico al legacy.
        $queryMax = self::PER_PAGE - $stickies;          // cuántos normales caben en la página
        $queryMin = ($page * self::PER_PAGE - self::PER_PAGE) - $stickies; // offset
        if ($queryMin < 0) {
            $queryMin = 0;
        }

        // Hilos normales para esta página. Si no quedan huecos (queryMax <= 0) la
        // página sólo muestra stickies; evitamos un LIMIT negativo.
        $normalThreads = $queryMax > 0
            ? ForumThread::query()
                ->where('type', '<', 3)
                ->where('forumid', 0)
                ->orderByDesc('unix')
                ->offset($queryMin)
                ->limit($queryMax)
                ->get()
            : new Collection;

        return view('forum.index', [
            'stickyThreads' => $stickyThreads,
            'normalThreads' => $normalThreads,
            'page' => $page,
            'pages' => $pages,
            'total' => $total,
        ]);
    }
}
