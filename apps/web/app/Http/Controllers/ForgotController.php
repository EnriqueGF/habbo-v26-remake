<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Support\HoloHash;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

/**
 * Recuperación de contraseña NATIVA (reemplaza legacy/forgot.php).
 *
 * El legacy no envía email realmente (la función mail() no está operativa en este
 * entorno): genera una contraseña aleatoria, reescribe el hash del usuario y
 * muestra la nueva contraseña en pantalla ($teh_pass). Reproducimos ese
 * comportamiento. La búsqueda y la actualización usan Query Builder parametrizado
 * (sin SQLi) y el hash se calcula con App\Support\HoloHash. Vista en
 * `layouts.guest`.
 */
class ForgotController extends Controller
{
    public function show(): View
    {
        return view('auth.forgot');
    }

    public function submit(Request $request): View
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:50'],
            'email' => ['required', 'email', 'max:100'],
        ]);

        /** @var User|null $user */
        $user = User::query()
            ->where('name', $validated['name'])
            ->where('email', $validated['email'])
            ->first();

        if ($user === null) {
            return view('auth.forgot', [
                'error' => '&iexcl;Nombre de usuario o email no v&aacute;lido!',
            ]);
        }

        // Contraseña aleatoria alfanumérica (8-10 caracteres), como el legacy.
        $newPassword = Str::password(random_int(8, 10), letters: true, numbers: true, symbols: false);

        DB::table('users')->where('name', $user->name)->update([
            'password' => HoloHash::make($newPassword),
        ]);

        return view('auth.forgot', [
            'newPassword' => $newPassword,
            'username' => $user->name,
        ]);
    }
}
