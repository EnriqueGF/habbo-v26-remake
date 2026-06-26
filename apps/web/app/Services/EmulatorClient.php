<?php

namespace App\Services;

/**
 * Cliente del MUS del emulador (reemplazo nativo de legacy/includes/mus.php).
 *
 * Envía comandos ASCII crudos al socket MUS del emulador (p.ej. "UPRS{id}",
 * "UPRC{id}") para refrescar rango/créditos en caliente. Best-effort: nunca
 * lanza si el emulador está caído, solo devuelve false.
 */
class EmulatorClient
{
    public function send(string $data): bool
    {
        if (! function_exists('socket_create')) {
            return false;
        }

        $host = (string) config('emulator.mus_host');
        $port = (int) config('emulator.mus_port');
        $timeout = (int) config('emulator.timeout', 2);

        if ($host === '' || $port <= 0) {
            return false;
        }

        $sock = @socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
        if ($sock === false) {
            return false;
        }

        @socket_set_option($sock, SOL_SOCKET, SO_SNDTIMEO, ['sec' => $timeout, 'usec' => 0]);

        try {
            if (@socket_connect($sock, $host, $port) === false) {
                return false;
            }
            @socket_send($sock, $data, strlen($data), MSG_DONTROUTE);

            return true;
        } finally {
            @socket_close($sock);
        }
    }

    /** Refrescar rango/suscripción de un usuario en el emulador. */
    public function updateUserStatus(int $userId): bool
    {
        return $this->send('UPRS'.$userId);
    }

    /** Refrescar créditos de un usuario en el emulador. */
    public function updateUserCredits(int $userId): bool
    {
        return $this->send('UPRC'.$userId);
    }
}
