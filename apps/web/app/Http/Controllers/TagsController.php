<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

/**
 * Tags (reemplaza legacy/tags.php). Vista de solo lectura: nube de etiquetas
 * (cms_tags agrupadas) y buscador que lista los usuarios que usaron una
 * etiqueta. Todas las consultas usan query builder parametrizado (sin SQLi).
 *
 * NOTA: el legacy tenía UNA escritura (INSERT en cms_tags con ?tag=X&add=true
 * para que el usuario añadiera una etiqueta a su perfil). Requiere sesión
 * iniciada ($my_id) y validación no trivial (longitud 2-20, solo alfanumérico,
 * máximo 20 tags por usuario, sin duplicados). Se difiere a una fase posterior;
 * cuando se implemente debe ser un POST con @csrf y la validación anterior.
 */
class TagsController extends Controller
{
    public function index(Request $request): View
    {
        // Nube de etiquetas: los 20 tags más usados (igual que tagcloud.php).
        $tags = DB::table('cms_tags')
            ->select('tag', DB::raw('COUNT(id) AS quantity'))
            ->groupBy('tag')
            ->orderByDesc('quantity')
            ->limit(20)
            ->get();

        // Búsqueda: solo si llega ?tag= y la consulta es válida (igual que el legacy).
        $query = null;
        $validSearch = false;
        $results = 0;
        $taggers = new Collection;

        if ($request->filled('tag')) {
            $query = strtolower((string) $request->query('tag'));

            if ($query !== '') {
                $validSearch = true;

                // Propietarios que usaron esa etiqueta (LIKE exacto, parametrizado, máx 20).
                $ownerIds = DB::table('cms_tags')
                    ->where('tag', 'like', $query)
                    ->limit(20)
                    ->pluck('ownerid');

                $results = $ownerIds->count();

                // Por cada propietario que exista, sus datos y sus etiquetas.
                $taggers = $ownerIds
                    ->map(function ($ownerId) {
                        $user = User::query()
                            ->where('id', $ownerId)
                            ->orderBy('name')
                            ->first(['id', 'name', 'mission', 'figure']);

                        if ($user === null) {
                            return null;
                        }

                        $user->user_tags = DB::table('cms_tags')
                            ->where('ownerid', $user->id)
                            ->pluck('tag');

                        return $user;
                    })
                    ->filter()
                    ->values();
            }
        }

        return view('tags.index', compact('tags', 'query', 'validSearch', 'results', 'taggers'));
    }
}
