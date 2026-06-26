<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/** Amistad del messenger — tabla `messenger_friendships` (sin clave primaria propia). */
class Friendship extends Model
{
    protected $table = 'messenger_friendships';

    public $timestamps = false;

    protected $guarded = [];
}
