<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;

/**
 * El equipo (reemplaza legacy/staff.php). Vista de solo lectura: lista a los
 * miembros del equipo agrupados por rango, igual que el legacy (Administradores
 * rango 7, Moderadores 6, X's 5, Gold/Silver 4). Todas las consultas usan
 * Eloquent parametrizado (sin SQLi).
 */
class StaffController extends Controller
{
    /**
     * Rangos del equipo y la etiqueta que el legacy pintaba para cada uno.
     * El orden coincide con el de staff.php (de mayor a menor rango).
     */
    private const TEAMS = [
        ['rank' => 7, 'heading' => 'Los Administradores', 'empty' => 'Ning&uacute;n Staff.', 'label' => '<i><b><u>A</u>dministrador</b>', 'default_mission' => 'Administrador a tu servicio :)'],
        ['rank' => 6, 'heading' => 'Los Moderadores', 'empty' => 'Ning&uacute;n moderador.', 'label' => '<i><b><u>M</u>oderador</b>', 'default_mission' => 'Moderador a tu servicio :)'],
        ['rank' => 5, 'heading' => "Los X's", 'empty' => "Ning&uacute;n X's.", 'label' => '<i><b><u>X</u></b></b>', 'default_mission' => 'X a tu servicio :)'],
        ['rank' => 4, 'heading' => 'Los Gold/Silver', 'empty' => 'Ning&uacute;n Gold/Silver.', 'label' => '<i><b><u>G</u>old</b>/<i><b><u>S</u>ilver</b></i>', 'default_mission' => 'Gold/Silver a tu servicio :)'],
    ];

    public function index(): View
    {
        // Cada grupo es una sección del listado: usuarios del rango exacto,
        // ordenados por nombre (igual que el legacy: WHERE rank = N ORDER BY name).
        $teams = (new Collection(self::TEAMS))->map(function (array $team) {
            $team['members'] = User::query()
                ->where('rank', $team['rank'])
                ->orderBy('name')
                ->get(['name', 'mission', 'rank', 'lastvisit', 'figure', 'sex', 'id', 'online', 'badge_status']);

            return $team;
        });

        return view('staff.index', compact('teams'));
    }
}
