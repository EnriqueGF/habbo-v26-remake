<?php

namespace App\Http\Controllers\Housekeeping;

use App\Http\Controllers\Controller;
use App\Legacy\LegacySession;
use App\Models\User;
use App\Support\HoloHash;
use App\Support\StaffLog;
use Illuminate\Http\Request;

/**
 * Login/logout del panel de housekeeping. Reproduce housekeeping/login.php del
 * legacy pero con Eloquent parametrizado (sin la SQLi del original) y CSRF.
 */
class AuthController extends Controller
{
    public function __construct(private readonly LegacySession $legacy) {}

    /** /housekeeping : si ya hay sesión de admin va al panel; si no, al login. */
    public function index()
    {
        if ($this->legacy->adminUser() !== null) {
            return redirect()->route('hk.dashboard');
        }

        return $this->showLogin('No se ha encontrado ninguna sesi&oacute;n de administraci&oacute;n');
    }

    public function showLogin(string $msg = '')
    {
        if ($this->legacy->adminUser() !== null) {
            return redirect()->route('hk.dashboard');
        }

        return view('housekeeping.login', ['msg' => $msg]);
    }

    public function login(Request $request)
    {
        $name = trim((string) $request->input('username'));
        $password = (string) $request->input('password');

        if ($name === '' || $password === '') {
            return $this->showLogin('Rellena todos los campos');
        }

        $user = User::query()
            ->where('name', $name)
            ->where('password', HoloHash::make($password))
            ->where('rank', '>', 5)
            ->first();

        if ($user === null) {
            return $this->showLogin('Contrase&ntilde;a incorrecta');
        }

        $this->legacy->adminLogin($user);

        StaffLog::record(
            'Housekeeping',
            $user->name.' authenticated from '.$request->ip(),
            'login.php',
            (int) $user->id,
        );

        return redirect()->route('hk.dashboard');
    }

    public function logout()
    {
        $this->legacy->adminLogout();

        return $this->showLogin('Sesi&oacute;n cerrada correctamente');
    }
}
