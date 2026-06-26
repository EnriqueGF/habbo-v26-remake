# 07 — Seguimiento de la migración de rutas a Laravel nativo

Documento vivo. Estado de cada ruta importante del HoloCMS legacy en su paso a
Laravel nativo (controlador + Eloquent + Blade + tests), siguiendo la receta de
[06-estado-implementacion.md](06-estado-implementacion.md).

Leyenda: ✅ migrada y probada · 🟡 en curso · ⬜ pendiente (servida por el legacy).

## Infraestructura compartida
| Pieza | Estado |
|-------|--------|
| Runtime único PHP 8.3 + Laravel front controller + LegacyRunner | ✅ |
| Rutas en `routes/web.php` (convencionales, nombradas, URLs limpias sin `.php`) | ✅ |
| Redirecciones 301 `*.php` → ruta limpia (compatibilidad con enlaces legacy) | ✅ |
| Layout Blade `layouts.community` (logueado) + `layouts.guest` (login/registro) | ✅ |
| `ChromeComposer` (datos del chrome: usuario, créditos, online, banners) | ✅ |
| Modelo `User`, `LegacySession`, middleware `legacy.user` | ✅ |
| BD de test `v26_test` (InnoDB) + CI (Pint + PHPUnit) | ✅ |

## Núcleo / cuenta (player-facing) — URLs limpias (`.php` redirige 301)
| Ruta nativa | Legacy | Estado | Tests |
|-------------|--------|--------|-------|
| `POST /` (login), `/logout` | index.php / logout.php | ✅ | ✅ |
| `/news` | news.php | ✅ | ✅ |
| `/help` | help.php | ✅ | ✅ |
| `/credits` | credits.php | ✅ | ✅ |
| `/privacy`, `/disclaimer` | privacy/disclaimer.php | ✅ | ✅ |
| `/account` (+ /account/profile, /account/password) | account.php | ✅ | ✅ |
| `/me` (dashboard; + POST /me/feed/remove) | me.php | ✅ | ✅ |
| `/community` | community.php | ✅ | ✅ |
| `/club` (+ /club/purchase) | club.php | ✅ | ✅ |
| `/shop` (+ /shop/purchase) | shop_furni.php | ✅ | ✅ |
| `/vip` | vip.php | ✅ | ✅ |
| `/badges` | badges.php | ✅ | ✅ |
| `/transactions` | transactions.php | ✅ | ✅ |
| `/deletehand` (+ POST /deletehand) | deletehand.php | ✅ | ✅ |
| `/collectables` | collectables.php | ✅ | ✅ |
| `/pixels` | pixels.php | ✅ | ✅ |
| `/statistics` | statistics.php | ✅ | ✅ |
| `/staff` | staff.php | ✅ | ✅ |
| `/tags` (display; alta de tag diferida) | tags.php | ✅ | ✅ |
| `/register` (+ POST) | register.php | ✅ | ✅ |
| `/forgot` (+ POST) | forgot.php | ✅ | ✅ |
| `user_profile.php` (myhabbo) | user_profile.php | ⬜ (diferido: motor de widgets JS; servido por legacy) | |

## Comunidad
| Ruta | Legacy | Estado |
|------|--------|--------|
| `/community.php`, `/forum.php`, `/group_*`, `/groups_*` | varios | ⬜ |
| `/applications.php`, `/invite.php` | varios | ⬜ |

## Housekeeping (admin) — máxima prioridad de seguridad
| Ruta | Legacy | Estado |
|------|--------|--------|
| `/housekeeping/*` (dashboard, users, bans, content, config, logs) | housekeeping/* | ⬜ |

## AJAX (habblet)
| Ruta | Legacy | Estado |
|------|--------|--------|
| `/habblet/ajax_*` (amigos, grupos, coleccionables, minimail, widgets) | habblet/* | ⬜ |

## Cliente / juego
| Ruta | Legacy | Estado |
|------|--------|--------|
| `/client_dp.php`, `/security_check.php`, SSO | client_dp/security_check | ⬜ |

---

## Fidelidad visual — CSS/JS por página (IMPORTANTE)
Cada página del legacy carga, además del set común del chrome (style/buttons/boxes/
tooltips/welcome/personal/group/rooms), **CSS/JS específicos** sin los cuales el contenido
sale **descuadrado**. Al migrar una página hay que reproducirlos vía `@push('head')`:
- `me` → `minimail.css` + `minimail.js` + `habboclub.js` (caja de mensajes).
- `account` → `settings.css` + `friendmanagement.css` + `settings.js` (editor de figura).
- `club` → `habboclub.js`.
- (community/credits/news/user_profile: con el set común basta.)
**Cómo detectarlos:** activar `DISABLE_PHP_REDIRECTS=1` en `.env`, abrir `pagina.php`
(sirve el legacy) y comparar su `<head>` con el del nativo. Otro gotcha: no duplicar
`#navi2-container` (lo añade el layout; la vista solo aporta el `<ul>` vía la partición).

## Fidelidad visual (chrome)
- `layouts.community` reproduce el chrome del legacy; la barra de 2º nivel **#navi2** se
  centraliza en `partials/navi2.blade.php` (secciones home/community/credits) y cada
  página la incluye vía `@section('subnav')` con su item activo.
- **Causa de los descuadres detectados** (me.php, account.php): las vistas omitían navi2
  y usaban una estructura de columnas equivocada. El legacy `account`/`club` son
  **ancho completo** (sin column1/column2); `news`/`community` son 3 columnas; `me`/
  `user_profile` 2 columnas. Ya corregido y verificado por navegador.
- **Pendiente de pulido (chrome distinto en el legacy, no roto):** `privacy.php` y
  `disclaimer.php` usan en el legacy la plantilla de *login* (chrome simple, sin nav
  logueada) — ahora usan `layouts.community`; conviene un `layouts.guest`. `help.php`
  es un *popup* (frame FAQ `fheader`) — conviene un `layouts.frame`.

## Notas de proceso
- Cada ruta migrada **deja de servirse por el LegacyRunner** automáticamente (la ruta
  nativa, registrada antes del catch-all, tiene precedencia).
- Las páginas que aún no se migran siguen funcionando por el legacy, así que el hotel
  permanece **operativo en todo momento**.
- URLs convencionales: `/me`, `/account`, `/credits`… (sin `.php`), rutas nombradas en
  `routes/web.php` (sin `routes/modules/`). Los `*.php` antiguos redirigen 301 a la URL
  limpia para no romper enlaces/marcadores del legacy.
- Gotcha de routing: `/algo.php` llega a Laravel como `algo.php`; pero `/index.php`
  llega como `/` (Apache ejecuta `public/index.php`).
- El re-hash de contraseñas a bcrypt y el paso a utf8mb4/InnoDB del esquema real se
  harán al retirar el legacy (Fases 4–5).
