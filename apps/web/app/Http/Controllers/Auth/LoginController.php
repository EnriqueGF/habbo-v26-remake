<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Legacy\LegacySession;
use App\Models\User;
use App\Support\HoloHash;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\RateLimiter;

/**
 * Procesado de login NATIVO (reemplaza la lógica de login de legacy/index.php).
 *
 * Mejoras frente al legacy: consultas parametrizadas (Eloquent, sin SQLi),
 * rate-limiting, verificación del hash mediante App\Support\HoloHash. Escribe la
 * sesión PHP nativa para que las páginas legacy aún no migradas reconozcan al
 * usuario (ver App\Legacy\LegacySession).
 */
class LoginController extends Controller
{
    public function __construct(private readonly LegacySession $legacy) {}

    public function login(Request $request): RedirectResponse
    {
        // Validación ligera (el formulario es el landing legacy, sin sesión/CSRF de
        // Laravel): campos vacíos -> mismo comportamiento que el legacy.
        $username = trim((string) $request->input('username'));
        $password = (string) $request->input('password');

        if ($username === '' || $password === '' || mb_strlen($username) > 50) {
            return $this->to($request, '/?error=1');
        }

        $throttleKey = 'login:'.$request->ip().'|'.mb_strtolower($username);
        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            return $this->to($request, '/?error=throttle');
        }

        /** @var User|null $user */
        $user = User::query()->where('name', $username)->first();

        if ($user === null || ! HoloHash::check($password, (string) $user->password)) {
            RateLimiter::hit($throttleKey, 60);

            return $this->to($request, '/?error=1');
        }

        if ($this->isBanned($user, (string) $request->ip())) {
            return $this->to($request, '/?error=banned');
        }

        RateLimiter::clear($throttleKey);

        // Refrescar últimas visitas como hacía el legacy.
        DB::table('users')->where('id', $user->id)->update([
            'lastvisit' => date('d/m/Y H:i:s'),
            'ipaddress_last' => (string) $request->ip(),
        ]);

        $this->legacy->login($user);

        // El legacy continúa el flujo en security_check.php (genera ticket SSO, etc.).
        return $this->to($request, '/security_check.php');
    }

    /**
     * Redirige a una ruta absoluta limpia. Cuando el formulario hace POST a
     * /index.php, el SCRIPT_NAME es /index.php y el generador de URLs de Laravel
     * prefijaría las rutas con "/index.php"; construimos la URL desde el host.
     */
    private function to(Request $request, string $path): RedirectResponse
    {
        return redirect()->to(rtrim($request->getSchemeAndHttpHost(), '/').$path);
    }

    /** Chequeo de baneo equivalente al de core.php (por userid o IP). */
    private function isBanned(User $user, string $ip): bool
    {
        $ban = DB::table('users_bans')
            ->where('userid', $user->id)
            ->orWhere('ipaddress', $ip)
            ->first();

        if ($ban === null) {
            return false;
        }

        $expire = isset($ban->date_expire) ? strtotime((string) $ban->date_expire) : false;

        // Si no hay fecha válida la tratamos como ban permanente; si ya expiró, no banea.
        if ($expire === false) {
            return true;
        }

        return $expire > time();
    }

    public function logout(Request $request): RedirectResponse
    {
        $this->legacy->logout();

        return $this->to($request, '/');
    }
}
