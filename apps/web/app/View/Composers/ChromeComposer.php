<?php

namespace App\View\Composers;

use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Throwable;

/**
 * Aporta los datos del "chrome" (cabecera/nav/pie) que el legacy calculaba en
 * core.php + templates: usuario actual, nombre del hotel, recuento online y
 * banners. Se enlaza a todas las vistas ('*') para que estén disponibles tanto
 * en el layout como en las secciones de las vistas hijas. Cacheado por request.
 */
class ChromeComposer
{
    private static ?array $data = null;

    public function compose(View $view): void
    {
        $view->with($this->data());
        // El usuario depende del request (no cacheable globalmente).
        $view->with([
            'chromeUser' => request()->user(),
            'loggedIn' => request()->user() !== null,
        ]);
    }

    /** @return array<string, mixed> */
    private function data(): array
    {
        if (self::$data !== null) {
            return self::$data;
        }

        try {
            $config = DB::table('cms_system')->first() ?: (object) [];
            $system = DB::table('system')->first() ?: (object) [];
            $banners = DB::table('cms_banners')->where('status', '1')->orderBy('id')->get();
        } catch (Throwable) {
            $config = (object) [];
            $system = (object) [];
            $banners = collect();
        }

        return self::$data = [
            'shortname' => $config->shortname ?? 'Habbo',
            'sitename' => $config->sitename ?? 'Habbo',
            'onlineCount' => (int) ($system->onlinecount ?? 0),
            'onlineStatus' => 'online',
            'banners' => $banners,
        ];
    }
}
