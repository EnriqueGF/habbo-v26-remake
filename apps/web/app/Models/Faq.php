<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Entrada de la página de ayuda/FAQ — tabla `cms_faq` de HoloCMS.
 *
 * Las filas son de dos tipos: `type = 'cat'` (categorías) y `type = 'item'`
 * (preguntas, con `catid` apuntando al `id` de su categoría).
 */
class Faq extends Model
{
    protected $table = 'cms_faq';

    protected $primaryKey = 'id';

    public $incrementing = true;

    public $timestamps = false;

    protected $guarded = [];
}
