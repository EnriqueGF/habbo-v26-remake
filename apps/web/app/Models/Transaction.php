<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/** Movimiento de créditos — tabla `cms_transactions`. */
class Transaction extends Model
{
    protected $table = 'cms_transactions';

    public $timestamps = false;

    protected $guarded = [];
}
