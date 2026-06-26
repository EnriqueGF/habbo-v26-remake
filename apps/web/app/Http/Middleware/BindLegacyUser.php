<?php

namespace App\Http\Middleware;

use App\Legacy\LegacySession;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Resuelve el usuario actual desde la sesión legacy compartida y lo expone en las
 * rutas nativas vía $request->user() y auth()->user(), sin persistir en la sesión
 * de Laravel (la fuente de verdad sigue siendo la sesión PHP nativa del legacy).
 */
class BindLegacyUser
{
    public function __construct(private readonly LegacySession $legacy) {}

    public function handle(Request $request, Closure $next): Response
    {
        $user = $this->legacy->user();

        if ($user !== null) {
            $request->setUserResolver(fn () => $user);
            auth()->setUser($user);
        }

        return $next($request);
    }
}
