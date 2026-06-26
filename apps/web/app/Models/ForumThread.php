<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Hilo del foro global (legacy/forum.php + viewthread.php). Mapea la tabla
 * `cms_forum_threads` del HoloCMS. Columnas reales:
 *   id, type, title, author, date, lastpost_author, lastpost_date,
 *   views, posts, unix, forumid.
 *
 * Semántica de `type` (replicada del legacy):
 *   - type < 3  → hilo normal (1 = abierto, 2 = cerrado).
 *   - type > 2  → sticky / destacado (3 = sticky abierto, 4 = sticky cerrado).
 * `forumid` = 0 identifica el foro global.
 *
 * @property int $id
 * @property int $type
 * @property string $title
 * @property string $author
 * @property string $date
 * @property string $lastpost_author
 * @property string $lastpost_date
 * @property int $views
 * @property int $posts
 * @property string $unix
 * @property int|null $forumid
 */
class ForumThread extends Model
{
    protected $table = 'cms_forum_threads';

    public $timestamps = false;

    /** @var list<string> */
    protected $guarded = ['id'];
}
