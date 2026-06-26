<?php

namespace App\Support;

use Illuminate\Support\Facades\DB;

/**
 * Acceso parametrizado a los ajustes del HoloCMS, sustituyendo a las funciones
 * legacy FetchServerSetting()/FetchCMSSetting()/getContent() de core.php (que
 * concatenaban directamente en SQL).
 *
 * Las claves son siempre constantes del código (no entrada de usuario), por lo que
 * usar el nombre de columna en cms()/value() es seguro.
 */
class CmsSettings
{
    /** Ajuste del emulador (tabla system_config, skey -> sval). */
    public static function server(string $key, bool $switch = false): string
    {
        $value = DB::table('system_config')->where('skey', $key)->value('sval');

        if (! $switch) {
            return (string) ($value ?? '');
        }

        return ((string) $value === '1') ? 'Enabled' : 'Disabled';
    }

    /** Ajuste del CMS: una columna concreta de la fila única de cms_system. */
    public static function cms(string $column): mixed
    {
        return DB::table('cms_system')->value($column);
    }

    /** Valor de contenido del sitio (cms_content, contentkey -> contentvalue). */
    public static function content(string $key): ?string
    {
        $value = DB::table('cms_content')->where('contentkey', $key)->value('contentvalue');

        return $value !== null ? (string) $value : null;
    }
}
