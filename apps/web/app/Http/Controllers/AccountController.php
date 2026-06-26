<?php

namespace App\Http\Controllers;

use App\Legacy\LegacySession;
use App\Models\User;
use App\Support\HoloHash;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * Preferencias de cuenta NATIVAS (reemplaza el núcleo de legacy/account.php).
 *
 * Migra las pestañas de alto valor del legacy: ver preferencias, editar el perfil
 * básico (lema/mission y email) y el cambio de contraseña (pestañas 2/3/4). Todas
 * las escrituras usan consultas parametrizadas (Eloquent / Query Builder, sin SQLi)
 * y la verificación de credenciales usa App\Support\HoloHash (no bcrypt, ya que el
 * login legacy coexiste). El usuario lo aporta el middleware legacy.user.
 */
class AccountController extends Controller
{
    public function __construct(private readonly LegacySession $legacy) {}

    public function index(Request $request): View|RedirectResponse
    {
        $user = $request->user();

        if ($user === null) {
            return redirect('/');
        }

        return view('account.index', compact('user'));
    }

    /** Actualiza datos básicos del perfil: lema (mission) y email. */
    public function updateProfile(Request $request): RedirectResponse
    {
        $user = $request->user();

        if ($user === null) {
            return redirect('/');
        }

        $validated = $request->validate([
            'mission' => ['nullable', 'string', 'max:50'],
            'email' => ['required', 'email', 'max:100'],
        ]);

        DB::table('users')->where('id', $user->id)->update([
            'mission' => (string) ($validated['mission'] ?? ''),
            'email' => $validated['email'],
        ]);

        return redirect()->route('account')->with('status', 'Tu perfil ha sido actualizado.');
    }

    /** Cambia la contraseña verificando la actual; reescribe la sesión legacy. */
    public function updatePassword(Request $request): RedirectResponse
    {
        $user = $request->user();

        if ($user === null) {
            return redirect('/');
        }

        $validated = $request->validate([
            'current_password' => ['required', 'string'],
            'new_password' => ['required', 'string', 'min:6', 'confirmed'],
        ]);

        if (! HoloHash::check($validated['current_password'], (string) $user->password)) {
            return back()->withErrors([
                'current_password' => 'La contraseña actual no es correcta.',
            ]);
        }

        DB::table('users')->where('id', $user->id)->update([
            'password' => HoloHash::make($validated['new_password']),
        ]);

        // Recargar el usuario y reescribir $_SESSION['password'] con el nuevo hash
        // para no desloguear al usuario en las páginas legacy (ver LegacySession).
        $refreshed = User::query()->find($user->id);
        if ($refreshed !== null) {
            $this->legacy->login($refreshed);
        }

        return redirect()->route('account')->with('status', 'Tu contraseña ha sido cambiada.');
    }
}
