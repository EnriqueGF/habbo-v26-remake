<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

/**
 * Usuario del hotel — mapea la tabla `users` existente de HoloCMS (v26).
 *
 * Sin timestamps (el esquema legacy no los tiene). La contraseña se almacena con
 * el hash legacy HoloHash (md5+sal) mientras coexista el login legacy; se migrará
 * a bcrypt cuando la auth legacy se retire (Fase 5). Ver App\Support\HoloHash.
 */
class User extends Authenticatable
{
    protected $table = 'users';

    public $timestamps = false;

    protected $guarded = ['id'];

    protected $hidden = ['password', 'ticket_sso'];

    protected $casts = [
        'rank' => 'integer',
        'credits' => 'integer',
        'tickets' => 'integer',
    ];

    /** ¿Es staff con acceso al housekeeping? (rango > 5 en HoloCMS). */
    public function isStaff(): bool
    {
        return (int) $this->rank > 5;
    }
}
