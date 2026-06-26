<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/** Mensaje del minimail — tabla `cms_minimail` de HoloCMS. */
class Minimail extends Model
{
    protected $table = 'cms_minimail';

    public $timestamps = false;

    protected $guarded = [];
}
