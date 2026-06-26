<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/** Etiqueta de usuario — tabla `cms_tags` de HoloCMS. */
class Tag extends Model
{
    protected $table = 'cms_tags';

    public $timestamps = false;

    protected $guarded = [];
}
