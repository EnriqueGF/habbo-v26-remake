<?php

namespace App\Http\Controllers;

use App\Legacy\LegacySession;
use App\Models\User;
use App\Support\HoloHash;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Throwable;

/**
 * Registro de cuentas NATIVO (reemplaza legacy/register.php).
 *
 * Reproduce el formulario de registro del legacy (avatar, nombre, contraseña,
 * email, fecha de nacimiento y sexo) y la inserción del usuario en la tabla
 * `users` existente. Todas las escrituras usan Query Builder parametrizado (sin
 * SQLi), la contraseña se almacena con el hash legacy (App\Support\HoloHash) y al
 * terminar se escribe la sesión PHP nativa (LegacySession) para que las páginas
 * legacy aún no migradas reconozcan al usuario. Vista en `layouts.guest`.
 */
class RegisterController extends Controller
{
    /** Figura por defecto si el editor Flash no logró fijar bean_figure. */
    private const DEFAULT_FIGURE = 'hr-115-42.hd-190-10.ch-215-66.lg-285-77.sh-290-80';

    public function __construct(private readonly LegacySession $legacy) {}

    public function show(): View
    {
        return view('auth.register');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:24', 'regex:/^[a-z0-9]+$/i', 'unique:users,name'],
            'password' => ['required', 'string', 'min:6'],
            'email' => ['required', 'email', 'max:48'],
            'sex' => ['nullable', 'string'],
        ]);

        $name = $validated['name'];
        $sex = ($validated['sex'] ?? '') === 'F' ? 'F' : 'M';

        // Figura: el editor Flash postea bean_figure; si viene vacía, usar el
        // look por defecto, igual que hace el legacy.
        $figure = trim((string) $request->input('bean_figure', ''));
        if ($figure === '') {
            $figure = self::DEFAULT_FIGURE;
        }

        // Fecha de nacimiento (día-mes-año) tal y como la guarda el legacy.
        $dob = sprintf(
            '%s-%s-%s',
            (string) $request->input('bean_day', ''),
            (string) $request->input('bean_month', ''),
            (string) $request->input('bean_year', ''),
        );

        // Créditos iniciales: cms_system.start_credits (legacy FetchCMSSetting).
        $scredits = $this->startCredits();

        $today = date('d/m/Y');
        $now = date('d/m/Y H:i:s');

        DB::table('users')->insert([
            'name' => $name,
            'password' => HoloHash::make($validated['password']),
            'email' => $validated['email'],
            'birth' => $dob,
            'figure' => $figure,
            'sex' => $sex,
            'rank' => 1,
            'hbirth' => $today,
            'ipaddress_last' => (string) $request->ip(),
            'postcount' => 0,
            'tickets' => 0,
            'credits' => $scredits,
            'lastvisit' => $now,
            'screen' => 'wide',
            'rea' => 'enabled',
            'mission' => '',
            'consolemission' => '',
            'badge_status' => '1',
            'figure_swim' => '',
            'user' => '',
            'noob' => 0,
            'online' => 0,
            // Columnas NOT NULL sin default (PDO en modo estricto las exige).
            'guideavailable' => 0,
            'shockwaveid' => '',
            'guide' => 0,
            'guide-allowed' => 0,
            'window' => 0,
        ]);

        /** @var User|null $user */
        $user = User::query()->where('name', $name)->first();

        if ($user === null) {
            return back()->withErrors(['name' => 'No se pudo crear la cuenta. Inténtalo de nuevo.'])->withInput();
        }

        // Transacción de bienvenida (solo si hay créditos iniciales).
        if ($scredits > 0) {
            DB::table('cms_transactions')->insert([
                'userid' => $user->id,
                'date' => date('Y-m-d'),
                'amount' => $scredits,
                'descr' => 'Bienvenido a '.$this->sitename().'!',
            ]);
        }

        // Loguear al usuario en la sesión legacy y continuar como el legacy.
        $this->legacy->login($user);

        return $this->to($request, '/security_check.php?me');
    }

    /** Créditos iniciales (cms_system.start_credits); 0 si no se puede leer. */
    private function startCredits(): int
    {
        try {
            return (int) (DB::table('cms_system')->value('start_credits') ?? 0);
        } catch (Throwable) {
            return 0;
        }
    }

    /** Nombre del hotel (cms_system.sitename) para el descriptor de la transacción. */
    private function sitename(): string
    {
        try {
            return (string) (DB::table('cms_system')->value('sitename') ?: 'Habbo');
        } catch (Throwable) {
            return 'Habbo';
        }
    }

    /**
     * Redirige a una ruta absoluta limpia construida desde el host (igual que el
     * LoginController) para no arrastrar prefijos de SCRIPT_NAME del legacy.
     */
    private function to(Request $request, string $path): RedirectResponse
    {
        return redirect()->to(rtrim($request->getSchemeAndHttpHost(), '/').$path);
    }
}
