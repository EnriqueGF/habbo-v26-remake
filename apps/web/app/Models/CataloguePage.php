<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Categoría del catálogo (tabla `catalogue_pages` de HoloCMS v26). Cada página
 * tiene un `minrank` que controla qué rango de usuario puede verla.
 */
class CataloguePage extends Model
{
    protected $table = 'catalogue_pages';

    protected $primaryKey = 'indexid';

    public $incrementing = false;

    public $timestamps = false;

    protected $guarded = [];
}
