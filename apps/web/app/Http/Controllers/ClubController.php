<?php

namespace App\Http\Controllers;

use App\Services\EmulatorClient;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Throwable;

/**
 * Habbo Club (reemplaza legacy/club.php). Muestra los paquetes de suscripción y
 * permite comprarlos: deduce créditos, otorga HC (users_club + rango + placa) y
 * registra la transacción. Toda la lógica de compra reproduce GiveHC()/HCDaysLeft()
 * de legacy/core.php pero con consultas parametrizadas (Eloquent/Query Builder,
 * sin SQLi) y de forma atómica dentro de una transacción de BD.
 */
class ClubController extends Controller
{
    /**
     * Paquetes válidos: meses => precio en créditos (idéntico al legacy club.php).
     *
     * @var array<int, int>
     */
    private const PACKAGES = [1 => 20, 3 => 50, 6 => 80];

    public function index(Request $request): View|RedirectResponse
    {
        $user = $request->user();

        // Comprar HC requiere estar logueado; el legacy mostraba un texto, aquí
        // redirigimos al landing (login) como pide el patrón nativo.
        if ($user === null) {
            return redirect()->to('/');
        }

        $isMember = false;
        $daysLeft = 0;

        $club = DB::table('users_club')->where('userid', $user->id)->first();
        if ($club !== null) {
            $daysLeft = $this->daysLeft((int) $club->months_left, (string) $club->date_monthstarted);
            $isMember = $daysLeft > 0;
        }

        return view('club.index', [
            'packages' => self::PACKAGES,
            'isMember' => $isMember,
            'daysLeft' => $daysLeft,
        ]);
    }

    public function purchase(Request $request): RedirectResponse
    {
        $user = $request->user();

        if ($user === null) {
            return redirect()->to('/');
        }

        $months = (int) $request->input('months');

        if (! array_key_exists($months, self::PACKAGES)) {
            return back()->with('error', 'Debes elegir entre 1, 3 o 6 meses.');
        }

        $price = self::PACKAGES[$months];
        $userId = (int) $user->id;

        $error = null;

        DB::transaction(function () use ($userId, $months, $price, &$error): void {
            // Recargar el usuario dentro de la transacción (con bloqueo) para evitar
            // doble gasto / créditos obsoletos.
            $fresh = DB::table('users')->where('id', $userId)->lockForUpdate()->first();

            if ($fresh === null || (int) $fresh->credits < $price) {
                $error = 'No tienes suficientes créditos para suscribirte.';

                return;
            }

            // 1. Deducir créditos.
            DB::table('users')->where('id', $userId)->decrement('credits', $price);

            // 2. users_club: sumar meses o crear la fila (réplica de GiveHC).
            $club = DB::table('users_club')->where('userid', $userId)->first();
            if ($club !== null) {
                DB::table('users_club')->where('userid', $userId)
                    ->increment('months_left', $months);
            } else {
                DB::table('users_club')->insert([
                    'userid' => $userId,
                    'date_monthstarted' => date('d-m-Y'),
                    'months_expired' => 0,
                    'months_left' => $months,
                ]);
            }

            // 3. Rango HC (2) solo si era usuario normal (rank == 1). No tocar staff.
            if ((int) $fresh->rank === 1) {
                DB::table('users')->where('id', $userId)->where('rank', 1)
                    ->update(['rank' => 2]);
            }

            // 4. Placa HC1: si no la tiene, deseleccionar las demás e insertarla.
            $hasBadge = DB::table('users_badges')
                ->where('userid', $userId)
                ->where('badgeid', 'HC1')
                ->exists();

            if (! $hasBadge) {
                DB::table('users_badges')->where('userid', $userId)
                    ->update(['iscurrent' => '0']);
                DB::table('users_badges')->insert([
                    'userid' => $userId,
                    'badgeid' => 'HC1',
                    'iscurrent' => '1',
                ]);
                DB::table('users')->where('id', $userId)
                    ->update(['badge_status' => '1']);
            }

            // 5. Registrar la transacción (cms_transactions.date es varchar; el
            //    legacy usa la fecha completa, aquí Y-m-d como pide el patrón).
            DB::table('cms_transactions')->insert([
                'userid' => $userId,
                'amount' => -$price,
                'date' => date('Y-m-d'),
                'descr' => 'Club subscription',
            ]);
        });

        if ($error !== null) {
            return back()->with('error', $error);
        }

        // Refrescar rango/créditos en el emulador (best-effort, no rompe la compra).
        try {
            $emulator = app(EmulatorClient::class);
            $emulator->updateUserStatus($userId);
            $emulator->updateUserCredits($userId);
        } catch (Throwable) {
            // Emulador caído: ignorar, la compra ya está persistida.
        }

        return redirect()->route('club')->with(
            'status',
            'Has adquirido correctamente una suscripción de '.$months.' mes(es) de Club.'
        );
    }

    /**
     * Días HC restantes (réplica de HCDaysLeft de legacy/core.php): 31 días por
     * mes restante menos los días ya consumidos del mes en curso.
     */
    private function daysLeft(int $monthsLeft, string $monthStarted): int
    {
        $parts = explode('-', $monthStarted);
        if (count($parts) !== 3) {
            return $monthsLeft * 31;
        }

        [$day, $month, $year] = array_map('intval', $parts);
        $then = mktime(0, 0, 0, $month, $day, $year);

        if ($then === false) {
            return $monthsLeft * 31;
        }

        $difference = max(0, time() - $then);
        $daysExpired = (int) floor($difference / 86400);

        return ($monthsLeft * 31) - $daysExpired;
    }
}
