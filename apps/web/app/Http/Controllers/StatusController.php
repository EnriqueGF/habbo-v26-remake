<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

/**
 * Endpoints de salud/estado del front controller (nativos).
 */
class StatusController extends Controller
{
    public function health(): JsonResponse
    {
        return response()->json([
            'app' => config('app.name'),
            'served_by' => 'laravel',
            'laravel' => app()->version(),
            'php' => PHP_VERSION,
            'db' => config('database.connections.'.config('database.default').'.charset'),
            'status' => 'ok',
        ]);
    }

    public function status(): JsonResponse
    {
        return response()->json([
            'served_by' => 'laravel',
            'users_total' => (int) DB::table('users')->count(),
            'online_now' => (int) optional(DB::table('system')->first())->onlinecount,
            'hotel' => optional(DB::table('cms_system')->first())->sitename,
        ]);
    }
}
