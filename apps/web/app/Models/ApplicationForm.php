<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Formulario de solicitud de staff — tabla `cms_application_forms` de HoloCMS.
 *
 * Las columnas booleanas (username, realname, birth, sex, country,
 * general_information, experience, education, additional_information,
 * show_disclaimer) son int(1) y deciden qué campos pinta el formulario.
 * `enabled` marca las solicitudes abiertas; `deleted` las ocultas (no se borran
 * de la tabla para no romper solicitudes ya enviadas). Sin timestamps.
 */
class ApplicationForm extends Model
{
    protected $table = 'cms_application_forms';

    public $timestamps = false;

    protected $guarded = ['id'];

    protected $casts = [
        'hconly' => 'integer',
        'username' => 'integer',
        'realname' => 'integer',
        'birth' => 'integer',
        'sex' => 'integer',
        'country' => 'integer',
        'general_information' => 'integer',
        'experience' => 'integer',
        'education' => 'integer',
        'additional_information' => 'integer',
        'show_disclaimer' => 'integer',
        'enabled' => 'integer',
        'deleted' => 'integer',
    ];
}
