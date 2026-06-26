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
| Layout Blade `layouts.community` (chrome logueado) + `layouts.guest` | ✅ |
| `ChromeComposer` (datos del chrome: usuario, créditos, online, banners) | ✅ |
| Modelo `User`, `LegacySession`, middleware `legacy.user` | ✅ |
| BD de test `v26_test` (InnoDB) + CI (Pint + PHPUnit) | ✅ |

## Núcleo / cuenta (player-facing)
| Ruta | Legacy | Estado | Tests |
|------|--------|--------|-------|
| `POST /` (login), `/logout.php` | index.php / logout.php | ✅ | ✅ |
| `/news.php` | news.php | ✅ | ✅ |
| `/help.php` | help.php | ✅ | ✅ |
| `/credits.php` | credits.php | ✅ | ✅ |
| `/disclaimer.php`, `/privacy.php` | disclaimer/privacy.php | ✅ | ✅ |
| `/account.php` (+ /profile, /password) | account.php | ✅ | ✅ |
| `/me.php` (dashboard; + POST feed/remove) | me.php | ✅ | ✅ |
| `/community.php` | community.php | ✅ | ✅ |
| `/club.php` (+ /purchase) | club.php | ✅ | ✅ |
| `/badges.php` | badges.php | ⬜ | |
| `/register.php` | register.php | ⬜ | |
| `/forgot.php` | forgot.php | ⬜ | |
| `/user_profile.php` (render solo lectura; edición widgets sigue en legacy) | user_profile.php | ✅ | ✅ |

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
