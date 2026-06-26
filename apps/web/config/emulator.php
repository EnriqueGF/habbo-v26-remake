<?php

return [
    /*
    | Conexión al MUS del emulador (canal interno servidor↔CMS). El legacy lo
    | usaba vía includes/mus.php; aquí queda como servicio nativo de Laravel
    | (App\Services\EmulatorClient) para los módulos ya migrados.
    */
    'mus_host' => env('EMU_MUS_HOST', 'emu'),
    'mus_port' => (int) env('EMU_MUS_PORT', 30000),
    'timeout' => (int) env('EMU_MUS_TIMEOUT', 2),
];
