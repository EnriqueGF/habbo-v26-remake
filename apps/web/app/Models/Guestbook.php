<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Entrada del libro de visitas de un widget de home — tabla `cms_guestbook`.
 * Cada entrada se asocia a un widget (`widget_id`) y a su autor (`userid`).
 *
 * Solo lectura en Laravel (escribir/borrar entradas sigue en el legacy).
 */
class Guestbook extends Model
{
    protected $table = 'cms_guestbook';

    public $timestamps = false;

    protected $guarded = [];
}
