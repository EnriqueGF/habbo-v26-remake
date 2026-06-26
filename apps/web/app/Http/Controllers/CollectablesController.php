<?php

namespace App\Http\Controllers;

use App\Models\Collectable;
use Illuminate\Contracts\View\View;

/**
 * Showroom de coleccionables (reemplaza legacy/collectables.php). Es informativa:
 * muestra el coleccionable del mes y el listado de coleccionables del showroom.
 * Lee `cms_collectables` con Eloquent (sin SQLi). Permite invitados.
 */
class CollectablesController extends Controller
{
    public function index(): View
    {
        // Coleccionable del mes: réplica del legacy
        // (WHERE month = n/m del mes actual AND year = año actual), LIMIT 1.
        $month = (int) date('n');
        $year = (int) date('Y');

        $current = Collectable::query()
            ->where('month', $month)
            ->where('year', $year)
            ->first();

        // Listado del showroom, más recientes primero (réplica del legacy).
        $collectables = Collectable::query()
            ->where('showroom', '1')
            ->orderByDesc('id')
            ->get();

        // Segundos restantes hasta fin de mes (réplica del cálculo del legacy:
        // mktime(0,0,0, mes, 31, año) - time()).
        $timeLeft = mktime(0, 0, 0, $month, 31, $year) - time();

        // Mapa mes (número) -> nombre en español, réplica del legacy.
        $monthNames = [
            1 => 'Enero', 2 => 'Febrero', 3 => 'Marzo', 4 => 'Abril',
            5 => 'Mayo', 6 => 'Junio', 7 => 'Julio', 8 => 'Agosto',
            9 => 'Septiembre', 10 => 'Octubre', 11 => 'Noviembre', 12 => 'Diciembre',
        ];

        return view('collectables.index', compact('current', 'collectables', 'timeLeft', 'monthNames'));
    }
}
