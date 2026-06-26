<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Sticker/widget posicionado del home de un usuario (sistema "myhabbo").
 * Mapea la tabla `cms_homes_stickers` existente de HoloCMS.
 *
 * Cada fila representa un elemento colocado en el #playground del perfil con sus
 * coordenadas x/y y orden z. La columna `type` distingue el tipo de elemento:
 *   1 = sticker, 2 = widget, 3 = stickie (nota), 4 = fondo (background).
 * Para los widgets (type 2), `subtype` indica cuál:
 *   1 = ProfileWidget, 2 = GroupsWidget, 3 = RoomsWidget, 4 = GuestbookWidget,
 *   5 = FriendsWidget, 6 = TraxPlayerWidget, 7 = HighScoresWidget, 8 = BadgesWidget.
 *
 * Solo lectura en Laravel: la edición (arrastrar/guardar) sigue en el legacy.
 */
class HomeSticker extends Model
{
    protected $table = 'cms_homes_stickers';

    public $timestamps = false;

    protected $guarded = [];
}
