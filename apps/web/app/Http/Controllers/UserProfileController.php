<?php

namespace App\Http\Controllers;

use App\Models\Guestbook;
use App\Models\HomeSticker;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

/**
 * Perfil/homepage de un usuario, sistema "myhabbo" (reemplaza legacy/user_profile.php).
 *
 * Render de SOLO LECTURA: muestra el #playground del usuario con sus widgets y
 * stickies posicionados (tabla cms_homes_stickers, con x/y/z). La EDICIÓN de
 * widgets (arrastrar, editar, guardar) permanece en el legacy de momento.
 *
 * Resuelve el usuario por ?name= (o ?tag=, la caja de búsqueda de homepages) o
 * por ?id=, siempre con Eloquent parametrizado (sin SQLi). No realiza escrituras
 * (el legacy hacía un UPDATE de group_linker al ver; aquí se omite).
 */
class UserProfileController extends Controller
{
    /** Mapa subtype -> nombre de widget (espejo del switch del legacy). */
    private const WIDGETS = [
        1 => 'ProfileWidget',
        2 => 'GroupsWidget',
        3 => 'RoomsWidget',
        4 => 'GuestbookWidget',
        5 => 'FriendsWidget',
        6 => 'TraxPlayerWidget',
        7 => 'HighScoresWidget',
        8 => 'BadgesWidget',
    ];

    public function show(Request $request): View
    {
        $profileUser = $this->resolveUser($request);

        if ($profileUser === null) {
            return view('user_profile.show', [
                'profileUser' => null,
                'stickers' => collect(),
                'background' => 'b_bg_pattern_abstract2',
                'groups' => collect(),
                'guestbooks' => collect(),
            ]);
        }

        // Fondo del home (type 4): si el usuario lo ha personalizado, se usa su
        // valor con prefijo "b_"; si no, el patrón por defecto del legacy.
        $bgRow = HomeSticker::query()
            ->where('userid', $profileUser->id)
            ->where('groupid', '-1')
            ->where('type', '4')
            ->value('data');
        $background = $bgRow !== null ? 'b_'.$bgRow : 'b_bg_pattern_abstract2';

        // Stickers/widgets/stickies posicionados (type < 4). groupid '-1' = home propio.
        $stickers = HomeSticker::query()
            ->where('userid', $profileUser->id)
            ->where('groupid', '-1')
            ->whereIn('type', ['1', '2', '3'])
            ->orderBy('z')
            ->limit(200)
            ->get();

        // Datos auxiliares de los widgets (pre-cargados para no consultar en la vista).
        $groups = $this->loadGroups((int) $profileUser->id);
        $guestbooks = $this->loadGuestbooks($stickers);

        return view('user_profile.show', [
            'profileUser' => $profileUser,
            'stickers' => $stickers,
            'background' => $background,
            'groups' => $groups,
            'guestbooks' => $guestbooks,
            'widgetNames' => self::WIDGETS,
        ]);
    }

    /** Resuelve el usuario por nombre (?name=/?tag=) o por id (?id=), parametrizado. */
    private function resolveUser(Request $request): ?User
    {
        $name = $request->query('name', $request->query('tag'));

        if ($name !== null && $name !== '') {
            return User::query()->where('name', (string) $name)->first();
        }

        if ($request->filled('id')) {
            return User::query()->where('id', (int) $request->query('id'))->first();
        }

        return null;
    }

    /**
     * Grupos no pendientes del usuario, con sus datos (nombre, badge, owner).
     * Reutiliza las tablas existentes groups_memberships / groups_details.
     */
    private function loadGroups(int $userId): Collection
    {
        return DB::table('groups_memberships as m')
            ->join('groups_details as d', 'd.id', '=', 'm.groupid')
            ->where('m.userid', $userId)
            ->where('m.is_pending', '0')
            ->select(
                'd.id',
                'd.name',
                'd.badge',
                'd.created',
                'd.ownerid',
                'm.member_rank',
                'm.is_current',
            )
            ->get();
    }

    /**
     * Entradas del libro de visitas para cada GuestbookWidget presente, indexadas
     * por widget_id. Incluye el nombre/figura del autor para el render.
     *
     * @param  Collection<int, HomeSticker>  $stickers
     */
    private function loadGuestbooks(Collection $stickers): Collection
    {
        $widgetIds = $stickers
            ->filter(fn ($s) => (int) $s->type === 2 && (int) $s->subtype === 4)
            ->pluck('id');

        if ($widgetIds->isEmpty()) {
            return collect();
        }

        return Guestbook::query()
            ->leftJoin('users', 'users.id', '=', 'cms_guestbook.userid')
            ->whereIn('cms_guestbook.widget_id', $widgetIds)
            ->orderByDesc('cms_guestbook.id')
            ->select(
                'cms_guestbook.id',
                'cms_guestbook.message',
                'cms_guestbook.time',
                'cms_guestbook.widget_id',
                'users.name as author_name',
                'users.figure as author_figure',
            )
            ->get()
            ->groupBy('widget_id');
    }
}
