<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/** Suscripción Habbo Club — tabla `users_club` (PK userid). */
class UserClub extends Model
{
    protected $table = 'users_club';

    protected $primaryKey = 'userid';

    public $incrementing = false;

    public $timestamps = false;

    protected $guarded = [];
}
