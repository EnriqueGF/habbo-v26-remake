<?php

namespace App\Http\Controllers;

use App\Models\Alert;
use App\Models\Friendship;
use App\Models\Group;
use App\Models\GroupMembership;
use App\Models\News;
use App\Models\Room;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * Dashboard del usuario logueado (reemplaza legacy/me.php), la "home" tras el
 * login. Es sobre todo DISPLAY de varias secciones (mensajes, etiquetas, feed de
 * alertas, usuarios online, amigos, grupos, recomendados y salas) con una acción
 * pequeña: borrar un elemento del feed. Requiere login (middleware legacy.user);
 * el usuario lo aporta $request->user() y los datos del chrome el ChromeComposer.
 *
 * Todas las consultas usan Eloquent / Query Builder parametrizado (sin SQLi).
 */
class MeController extends Controller
{
    public function index(Request $request): View|RedirectResponse
    {
        $user = $request->user();

        if ($user === null) {
            return redirect('/');
        }

        $id = (int) $user->id;

        // Nº de mensajes recibidos en el minimail (cabecera "Mis mensajes").
        $messageCount = (int) DB::table('cms_minimail')->where('to_id', $id)->count();

        // Etiquetas del usuario (máx. 20, como el legacy).
        $tags = Tag::query()
            ->where('ownerid', $id)
            ->limit(20)
            ->get();

        // Feed / alertas del usuario (notificaciones y mensajes del staff).
        $alerts = Alert::query()
            ->where('userid', $id)
            ->orderBy('id')
            ->get();

        // Usuarios en línea (online > 0 capta tanto el flag 1 como timestamps).
        // TODO: cuando `online` sea siempre un timestamp, usar (time() - 601) como corte.
        $onlineUsers = User::query()
            ->where('online', '>', 0)
            ->orderByDesc('online')
            ->limit(18)
            ->get();

        // Amigos del usuario (messenger_friendships: aparece como userid o friendid).
        $friendCount = (int) Friendship::query()
            ->where('userid', $id)
            ->orWhere('friendid', $id)
            ->count();

        // Mis grupos: pertenencias del usuario unidas a los detalles del grupo.
        $myGroups = GroupMembership::query()
            ->join('groups_details', 'groups_memberships.groupid', '=', 'groups_details.id')
            ->where('groups_memberships.userid', $id)
            ->select('groups_details.*')
            ->get();

        // Grupos recomendados (cms_recommended type='group' -> groups_details).
        $recommendedGroups = Group::query()
            ->join('cms_recommended', 'cms_recommended.rec_id', '=', 'groups_details.id')
            ->where('cms_recommended.type', 'group')
            ->orderBy('cms_recommended.id')
            ->select('groups_details.*')
            ->get();

        // Salas aleatorias con propietario (como en community.php).
        $rooms = Room::query()
            ->whereNotNull('owner')
            ->where('owner', '!=', '')
            ->inRandomOrder()
            ->limit(5)
            ->get();

        // Titulares recientes para la promo de noticias (columna derecha).
        $news = News::query()
            ->orderByDesc('num')
            ->limit(4)
            ->get();

        return view('me.index', compact(
            'user',
            'messageCount',
            'tags',
            'alerts',
            'onlineUsers',
            'friendCount',
            'myGroups',
            'recommendedGroups',
            'rooms',
            'news',
        ));
    }

    /**
     * Borra un elemento del feed (alerta) del usuario. Es POST + CSRF (más seguro
     * que el GET ?do=RemoveFeedItem del legacy) y solo afecta a alertas propias.
     */
    public function removeFeedItem(Request $request): RedirectResponse
    {
        $user = $request->user();

        if ($user === null) {
            return redirect('/');
        }

        DB::table('cms_alerts')
            ->where('userid', $user->id)
            ->where('id', $request->input('key'))
            ->delete();

        return redirect()->route('me');
    }
}
