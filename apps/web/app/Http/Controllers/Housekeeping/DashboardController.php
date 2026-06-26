<?php

namespace App\Http\Controllers\Housekeeping;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Support\CmsSettings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * Panel principal del housekeeping (dashboard.php del legacy), con Eloquent
 * parametrizado. El administrador actual lo resuelve el middleware housekeeping.auth.
 */
class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // Guardar notas del administrador (form POST del panel).
        if ($request->isMethod('post') && $request->has('notes')) {
            DB::table('cms_system')->limit(1)->update([
                'admin_notes' => (string) $request->input('notes'),
            ]);
        }

        $onlineCutOff = time() - 601;

        $stats = [
            'members' => User::count(),
            'online' => User::where('online', '>', $onlineCutOff)->count(),
            'rooms' => DB::table('rooms')->count(),
            'public_rooms' => DB::table('rooms')->whereNull('owner')->count(),
            'furniture' => DB::table('furniture')->count(),
            'groups' => DB::table('groups_details')->count(),
            'stafflog' => DB::table('system_stafflog')->count(),
            'bans' => DB::table('users_bans')->count(),
        ];

        $server = [
            'game_port' => CmsSettings::server('server_game_port'),
            'mus_port' => CmsSettings::server('server_mus_port'),
            'max_connections' => CmsSettings::server('server_game_maxconnections'),
            'trading' => CmsSettings::server('trading_enable', true),
            'recycler' => CmsSettings::server('recycler_enable', true),
            'wordfilter' => CmsSettings::server('wordfilter_enable', true),
            'wordfilter_censor' => CmsSettings::server('wordfilter_censor'),
        ];

        $admins = DB::table('users')
            ->select('id', 'name', 'email')
            ->where('rank', '>', 6)
            ->orderBy('name', 'asc')
            ->limit(20)
            ->get();

        return view('housekeeping.dashboard', [
            'tab' => 1,
            'adminNotes' => (string) CmsSettings::cms('admin_notes'),
            'stats' => $stats,
            'server' => $server,
            'admins' => $admins,
        ]);
    }
}
