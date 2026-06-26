<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/** Sala del hotel — tabla `rooms`. */
class Room extends Model
{
    protected $table = 'rooms';

    public $timestamps = false;

    protected $guarded = [];
}
