<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Solicitud de staff enviada por un usuario — tabla `cms_applications` de HoloCMS.
 *
 * Columnas reales: rankname, username, realname, birth, sex, country,
 * general_information, experience, education, additional_information,
 * accepted_disclaimer, admin_reacted, admin_read, admin_deleted. Sin timestamps.
 */
class Application extends Model
{
    protected $table = 'cms_applications';

    public $timestamps = false;

    protected $guarded = ['id'];
}
