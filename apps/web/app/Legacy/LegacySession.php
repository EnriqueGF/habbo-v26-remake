<?php

namespace App\Legacy;

use App\Models\User;

/**
 * Puente con la sesión PHP nativa que usa el HoloCMS legacy.
 *
 * Durante la transición, la sesión PHP nativa (`$_SESSION`) es la fuente de verdad
 * de "quién está logueado", porque la mayoría de páginas siguen siendo legacy y la
 * leen (core.php comprueba $_SESSION['username'] y $_SESSION['password'] = HoloHash).
 *
 * Las rutas nativas escriben/leen aquí para interoperar sin pedir doble login.
 * Cuando el legacy se retire (Fase 5), esto se reemplaza por la sesión de Laravel.
 */
class LegacySession
{
    public function start(): void
    {
        if (PHP_SAPI === 'cli') {
            // En tests no hay cabeceras HTTP; $_SESSION es un array normal.
            if (! isset($_SESSION)) {
                $_SESSION = [];
            }

            return;
        }

        if (session_status() === PHP_SESSION_NONE) {
            @session_start();
        }
    }

    /** Marca al usuario como logueado tal y como lo espera el legacy. */
    public function login(User $user): void
    {
        $this->start();
        $_SESSION['username'] = $user->name;
        $_SESSION['password'] = $user->password; // hash HoloHash ya almacenado
    }

    public function logout(): void
    {
        $this->start();
        unset($_SESSION['username'], $_SESSION['password']);
        unset($_SESSION['acp'], $_SESSION['hkusername'], $_SESSION['hkpassword']);
    }

    /**
     * Inicia sesión en el housekeeping escribiendo las MISMAS variables que
     * comprueba el legacy `housekeeping/index.php` ($_SESSION['acp'], 'hkusername',
     * 'hkpassword'). Así los módulos del panel aún no migrados, servidos por el
     * LegacyRunner vía `index.php?p=...`, siguen autenticados. También deja iniciada
     * la sesión del sitio si no lo estaba (igual que el legacy login.php).
     */
    public function adminLogin(User $user): void
    {
        $this->start();
        $_SESSION['acp'] = true;
        $_SESSION['hkusername'] = $user->name;
        $_SESSION['hkpassword'] = $user->password; // hash HoloHash ya almacenado

        if (empty($_SESSION['username'])) {
            $_SESSION['username'] = $user->name;
            $_SESSION['password'] = $user->password;
        }
    }

    /** Cierra solo la sesión de housekeeping (no la del sitio), como el legacy. */
    public function adminLogout(): void
    {
        $this->start();
        unset($_SESSION['acp'], $_SESSION['hkusername'], $_SESSION['hkpassword']);
    }

    public function isAdminAuthenticated(): bool
    {
        $this->start();

        return ! empty($_SESSION['acp'])
            && ! empty($_SESSION['hkusername'])
            && ! empty($_SESSION['hkpassword']);
    }

    /**
     * Resuelve el administrador actual revalidando las credenciales de la sesión
     * acp contra la BD y exigiendo rango > 5 (staff), igual que el legacy
     * `housekeeping/index.php`. Devuelve null si no es válido.
     */
    public function adminUser(): ?User
    {
        if (! $this->isAdminAuthenticated()) {
            return null;
        }

        return User::query()
            ->where('name', $_SESSION['hkusername'])
            ->where('password', $_SESSION['hkpassword'])
            ->where('rank', '>', 5)
            ->first();
    }

    public function isAuthenticated(): bool
    {
        $this->start();

        return ! empty($_SESSION['username']) && ! empty($_SESSION['password']);
    }

    /** Resuelve el usuario actual a partir de la sesión legacy (o null). */
    public function user(): ?User
    {
        if (! $this->isAuthenticated()) {
            return null;
        }

        return User::query()
            ->where('name', $_SESSION['username'])
            ->where('password', $_SESSION['password'])
            ->first();
    }
}
