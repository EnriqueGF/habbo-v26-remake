<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/** Alerta/notificación del feed del usuario — tabla `cms_alerts` de HoloCMS. */
class Alert extends Model
{
    protected $table = 'cms_alerts';

    public $timestamps = false;

    protected $guarded = [];
}
