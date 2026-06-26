<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\DB;

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

    /**
     * ¿Está conectado? El campo `online` guarda el último timestamp visto; el
     * legacy considera online si `online + 10800 >= time()` (IsUserOnline).
     */
    public function isOnline(): bool
    {
        return ((int) ($this->online ?? 0)) + 10800 >= time();
    }

    /**
     * Insignia personal actual (badgeid de users_badges con iscurrent = 1), o
     * null. Reproduce GetUserBadge() del legacy de forma parametrizada y solo
     * si el usuario tiene badge_status habilitado.
     */
    public function currentBadge(): ?string
    {
        if ((string) $this->badge_status !== '1') {
            return null;
        }

        $badge = DB::table('users_badges')
            ->where('userid', $this->id)
            ->where('iscurrent', '1')
            ->value('badgeid');

        return $badge !== null ? (string) $badge : null;
    }

    /** Grupo actual del usuario (groups_memberships con is_current = 1), o null. */
    public function currentGroupId(): ?int
    {
        $groupId = DB::table('groups_memberships')
            ->where('userid', $this->id)
            ->where('is_current', '1')
            ->value('groupid');

        return $groupId !== null ? (int) $groupId : null;
    }

    /** Insignia del grupo actual (groups_details.badge), o null. */
    public function currentGroupBadge(): ?string
    {
        $groupId = $this->currentGroupId();

        if ($groupId === null) {
            return null;
        }

        $badge = DB::table('groups_details')
            ->where('id', $groupId)
            ->value('badge');

        return $badge !== null && $badge !== '' ? (string) $badge : null;
    }
}
