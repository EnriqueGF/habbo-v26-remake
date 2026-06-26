<?php

namespace App\Http\Controllers;

use App\Services\EmulatorClient;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Throwable;

/**
 * "Vacía tu mano" (reemplaza legacy/deletehand.php + deletehandfinish.php).
 *
 * La página muestra el confirm; la acción borra todos los muebles que el usuario
 * tiene en la mano (furniture con roomid='0' y ownerid del usuario) y avisa al
 * emulador con "UPRH{id}" para refrescar la mano en caliente, igual que el legacy.
 * Toda la mutación se hace con Query Builder parametrizado dentro de una
 * transacción de BD. Requiere estar conectado.
 */
class DeleteHandController extends Controller
{
    public function index(Request $request): View|RedirectResponse
    {
        $user = $request->user();

        if ($user === null) {
            return redirect()->to('/');
        }

        return view('deletehand.index');
    }

    public function empty(Request $request): RedirectResponse
    {
        $user = $request->user();

        if ($user === null) {
            return redirect()->to('/');
        }

        $userId = (int) $user->id;

        // Réplica de: DELETE FROM furniture WHERE roomid='0' AND ownerid=$my_id.
        DB::transaction(function () use ($userId): void {
            DB::table('furniture')
                ->where('roomid', '0')
                ->where('ownerid', $userId)
                ->delete();
        });

        // Refrescar la mano en el emulador (best-effort, no rompe el vaciado).
        try {
            app(EmulatorClient::class)->send('UPRH'.$userId);
        } catch (Throwable) {
            // Emulador caído: ignorar, los muebles ya están borrados.
        }

        return redirect()->route('deletehand')
            ->with('status', '¡Tu mano ha sido vaciada! Recarga el hotel si no es el caso.');
    }
}
