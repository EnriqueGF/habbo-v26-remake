<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Artículo del catálogo (tabla `catalogue_items` de HoloCMS v26). `tid` es la
 * clave primaria y `catalogue_id_page` enlaza con CataloguePage.indexid.
 */
class CatalogueItem extends Model
{
    protected $table = 'catalogue_items';

    protected $primaryKey = 'tid';

    public $timestamps = false;

    protected $guarded = [];
}
