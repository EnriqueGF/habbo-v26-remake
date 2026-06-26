<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Coleccionable del hotel — tabla `cms_collectables` de HoloCMS.
 */
class Collectable extends Model
{
    protected $table = 'cms_collectables';

    public $timestamps = false;

    protected $guarded = [];
}
