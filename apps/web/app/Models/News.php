<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Noticia del hotel — tabla `cms_news` de HoloCMS.
 */
class News extends Model
{
    protected $table = 'cms_news';

    protected $primaryKey = 'num';

    public $incrementing = true;

    public $timestamps = false;

    protected $guarded = [];
}
