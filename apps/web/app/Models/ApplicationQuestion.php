<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Pregunta dinámica de un formulario de solicitud — tabla
 * `cms_application_questions` de HoloCMS.
 *
 * Estructura inferida del uso en legacy/applications.php (la tabla no existe en
 * la BD de esta instalación, pero reproducimos el modelo por fidelidad):
 *  - `aid`  : id del formulario (cms_application_forms.id) al que pertenece.
 *  - `aoq`  : "is a question" (= 1) frente a las filas que son opciones.
 *  - `qid`  : id de la pregunta padre (en las filas de opciones).
 *  - `text` : enunciado de la pregunta o de la opción.
 *  - `type` : nombre del campo (atributo name del input).
 *  - `sort` : 1 -> input radio; cualquier otro valor -> checkbox.
 *
 * Sin timestamps; guarded por id.
 */
class ApplicationQuestion extends Model
{
    protected $table = 'cms_application_questions';

    public $timestamps = false;

    protected $guarded = ['id'];

    protected $casts = [
        'aid' => 'integer',
        'aoq' => 'integer',
        'qid' => 'integer',
        'sort' => 'integer',
    ];
}
