<?php

namespace App\Support;

/**
 * Verificación del hash de contraseñas legacy (HoloCMS).
 *
 * El legacy almacena las contraseñas como md5("235x17aXCaRb" . $password) con
 * una sal global única (legacy/includes/inc.crypt.php). Esto permite a Laravel
 * autenticar cuentas existentes y rehashearlas a bcrypt al iniciar sesión
 * (migración de credenciales transparente — Fase 3, módulo de auth).
 */
class HoloHash
{
    private const SALT = '235x17aXCaRb';

    /** Calcula el hash legacy de una contraseña en claro. */
    public static function make(string $password): string
    {
        return md5(self::SALT.$password);
    }

    /** Comprueba una contraseña en claro contra un hash legacy almacenado. */
    public static function check(string $password, string $stored): bool
    {
        return hash_equals($stored, self::make($password));
    }

    /** ¿El hash almacenado parece un hash legacy (md5) y no un bcrypt? */
    public static function isLegacy(string $stored): bool
    {
        return (bool) preg_match('/^[a-f0-9]{32}$/i', $stored);
    }
}
