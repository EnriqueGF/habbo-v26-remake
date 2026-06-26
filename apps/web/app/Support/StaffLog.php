<?php

namespace App\Support;

use Illuminate\Support\Facades\DB;

/**
 * Registro de auditoría del staff (tabla system_stafflog), sustituyendo a los
 * INSERT concatenados que el legacy esparcía por el housekeeping.
 *
 * El timestamp reproduce el formato legacy $date_full: date('d/m/Y H:i:s').
 */
class StaffLog
{
    public static function record(string $action, string $message, string $note, int $userId, ?int $targetId = null): void
    {
        DB::table('system_stafflog')->insert([
            'action' => mb_substr($action, 0, 12),
            'message' => $message,
            'note' => $note,
            'userid' => $userId,
            'targetid' => $targetId, // columna nullable: null cuando la acción no tiene objetivo
            'timestamp' => date('d/m/Y H:i:s'),
        ]);
    }
}
