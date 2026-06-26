<?php

namespace App\Http\Middleware;

use App\Legacy\LegacySession;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Guarda el panel de housekeeping. Revalida la sesión de administración contra la
 * BD (rango > 5) en cada petición, reproduciendo la comprobación del legacy
 * `housekeeping/index.php`, y expone el administrador vía $request->user().
 *
 * Acepta un parámetro de rango mínimo: `housekeeping.auth:7` exige rango > 6 para
 * los módulos sensibles (contenido, servidor, logs, edituser…), igual que el legacy
 * degradaba esos `p` a `access_denied` si $user_rank < 7.
 */
class HousekeepingAuth
{
    public function __construct(private readonly LegacySession $legacy) {}

    public function handle(Request $request, Closure $next, ?string $minRank = null): Response
    {
        $admin = $this->legacy->adminUser();

        if ($admin === null) {
            // Sesión inválida o caducada: limpia y manda al login del panel.
            $this->legacy->adminLogout();

            return redirect()->route('hk.login');
        }

        if ($minRank !== null && (int) $admin->rank < (int) $minRank) {
            // Equivalente a la pantalla access_denied del legacy para rango insuficiente.
            abort(403, 'Acceso denegado: rango insuficiente.');
        }

        $request->setUserResolver(fn () => $admin);
        auth()->setUser($admin);

        return $next($request);
    }
}
