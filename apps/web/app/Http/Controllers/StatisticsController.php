<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;
use Throwable;

/**
 * Estadísticas del hotel (reemplaza legacy/statistics.php). Página de solo
 * lectura de la sección COMUNIDAD (pageid="10"): muestra los datos de la cuenta
 * del usuario logueado y las cifras globales del hotel. Reproduce los COUNT del
 * legacy (users, rooms, furniture, groups_details, users_bans) con Query Builder
 * parametrizado y lee los ajustes del servidor de `system_config` (igual que la
 * función FetchServerSetting del legacy).
 */
class StatisticsController extends Controller
{
    /** Versión del emulador (legacy/includes/version.php: $holocms version + stable). */
    private const SERVER_VERSION = '3.1.1.53 STABLE';

    /** Rangos del legacy (statistics.php) → etiqueta legible. */
    private const RANKS = [
        1 => 'Usuario',
        2 => 'Miembro del Club',
        3 => 'Habbo X',
        4 => 'Habbo Silver',
        5 => 'Habbo Gold',
        6 => 'Moderador',
        7 => 'Administrador',
    ];

    public function index(): View
    {
        // Cifras globales del hotel (réplica de los mysql_evaluate del legacy).
        $usersCount = DB::table('users')->count();
        $roomsCount = DB::table('rooms')->count();
        $publicRooms = DB::table('rooms')->whereNull('owner')->count();
        $furnitureCount = DB::table('furniture')->count();
        $groupsCount = DB::table('groups_details')->count();
        $bansCount = DB::table('users_bans')->count();

        // Ajustes del servidor: equivalente a FetchServerSetting (tabla system_config).
        $recycler = $this->serverSwitch('recycler_enable');
        $trading = $this->serverSwitch('trading_enable');
        $maxConnections = $this->serverSetting('server_game_maxconnections');

        // Rango del usuario logueado → etiqueta legible.
        $user = request()->user();
        $rankLabel = self::RANKS[(int) ($user->rank ?? 0)] ?? '';

        return view('statistics.index', [
            'usersCount' => $usersCount,
            'roomsCount' => $roomsCount,
            'publicRooms' => $publicRooms,
            'furnitureCount' => $furnitureCount,
            'groupsCount' => $groupsCount,
            'bansCount' => $bansCount,
            'recycler' => $recycler,
            'trading' => $trading,
            'maxConnections' => $maxConnections,
            'rankLabel' => $rankLabel,
            'serverVersion' => self::SERVER_VERSION,
        ]);
    }

    /** Lee un ajuste de system_config (skey → sval), como FetchServerSetting(). */
    private function serverSetting(string $key): string
    {
        try {
            return (string) (DB::table('system_config')->where('skey', $key)->value('sval') ?? '');
        } catch (Throwable) {
            return '';
        }
    }

    /** Igual que FetchServerSetting con switch=true: "1" → Enabled, resto → Disabled. */
    private function serverSwitch(string $key): string
    {
        return $this->serverSetting($key) === '1' ? 'Enabled' : 'Disabled';
    }
}
