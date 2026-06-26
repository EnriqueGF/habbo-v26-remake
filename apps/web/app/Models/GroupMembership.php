<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/** Pertenencia a grupo — tabla `groups_memberships`. */
class GroupMembership extends Model
{
    protected $table = 'groups_memberships';

    public $timestamps = false;

    protected $guarded = [];
}
