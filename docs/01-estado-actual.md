# 01 — Estado actual del proyecto

Inventario real obtenido del código fuente (no de la documentación). Sirve de base
para todas las decisiones del plan.

## 1. Topología en ejecución (Docker Compose)

El stack vive en `docker-compose.yml` con bind-mounts a las carpetas de la raíz:

| Servicio | Imagen / build | Puerto host | Monta | Rol |
|----------|----------------|-------------|-------|-----|
| `db` | `mariadb:10.6` (latin1) | `3307→3306` | `DB/holodb.sql`, `docker/db/*.sql` | Base de datos `v26` |
| `cms` | `docker/cms` (PHP 5.6 + Apache) | `8090→80` | `./CMS` | HoloCMS legacy (web pública) |
| `dcrs` | `nginx:alpine` | `8091→80` | `./DCRS` (ro) | Assets Shockwave (`.dcr`, `vars.txt`) |
| `proxy` | `docker/proxy` (Node + `ws`) | `8092`, `8093` | — | Puente TCP↔WebSocket hacia el emulador |
| `emu` | `docker/emu` (Ubuntu + Mono + ODBC) | `1232`, `30000` | `./EMU` | Emulador del juego (C#/.NET compilado con `mcs`) |

> `docker/dirplayer-builder` es solo un contenedor de build (Rust + wasm-pack), no un
> servicio en marcha.

**Implicación para el plan:** todo se sirve hoy con bind-mounts a rutas concretas
(`./CMS`, `./DCRS`, `./EMU`…). Cualquier reorganización de carpetas obliga a actualizar
estas rutas **en el mismo paso** y a un reinicio controlado (ver doc 03).

## 2. El CMS (HoloCMS) — el corazón de la migración

- **Origen:** HoloCMS v3.1.1.53 "ATLANTA" (2008, autor Meth0d). PHP procedural puro.
- **Tamaño:** **386 ficheros `.php`** (65 páginas en `CMS/` raíz + el resto en subdirs),
  **~36.000 líneas** de PHP.
- **Sin tooling moderno:** no hay `composer.json`, ni `vendor/`, ni `.env`, ni
  namespaces, ni clases (salvo el captcha). Configuración hardcodeada en `CMS/config.php`.

### Patrón de cada página
Cada `.php` es a la vez ruta, controlador y vista:
```
include('core.php');     // arranca sesión, conecta BD, valida usuario, define globals
session_start();
... lógica + SQL inline ...
include('templates/.../header.php'); // HTML embebido
```

### Núcleo (`CMS/core.php`, 699 líneas)
Define todo como **variables y funciones globales** (`$logged_in`, `$my_id`,
`$user_rank`, `FetchServerSetting()`, `getContent()`, `HCDaysLeft()`, `GiveHC()`…).
Arranca sesión, conecta a MySQL, revalida la sesión contra la tabla `users` en cada
carga de página.

### Deuda técnica crítica (motiva la migración)
- **API `mysql_*`** en todo el código — **eliminada en PHP 7.0**. Solo corre en PHP 5.6.
- **`session_is_registered()` / `session_register()`** — eliminadas en PHP 5.4/7.0;
  hoy parcheadas con un shim (`CMS/_compat.php`, cargado vía `auto_prepend_file`).
- **SQL injection generalizada:** queries construidas por concatenación de strings
  (`"... WHERE name = '".$username."'"`). El filtrado (`FilterText`) es parcial.
- **Hash de contraseñas débil:** `HoloHash()` = `md5("235x17aXCaRb" . $password)`
  (sal única global, sin bcrypt) — `CMS/includes/inc.crypt.php`.
- **Sin CSRF, sin rate-limiting, sin HTTPS forzado.** Cookie "remember me" en claro.
- **Ficheros en latin1 (ISO-8859-1)**; `php.ini` fuerza `default_charset=ISO-8859-1`
  para evitar mojibake.

### Cuantificación de roturas para PHP 8 (medido sobre `CMS/`)
Relevante para el enfoque elegido (port a runtime único PHP 8.x). El grueso se centraliza
en shims, no en editar ficheros:

| Rotura | Apariciones | Tratamiento |
|--------|-------------|-------------|
| `mysql_*` (eliminada PHP 7.0) | **1.789 llamadas** (solo ~9 funciones nativas: `mysql_query` 759, `mysql_error` 421, `mysql_fetch_assoc` 338, `mysql_num_rows` 214, `mysql_fetch_array` 20, `mysql_result` 8, `mysql_real_escape_string` 3, `mysql_insert_id`, `mysql_affected_rows`) | **Un shim** sobre `mysqli` — cero ediciones de call-sites |
| Barewords-constante fatales en PHP 8.0 | **~101** (`session_is_registered(bareword)` 74, `function_exists(bareword)` 19, `$_SERVER[BAREWORD]`/`$_GET[…]` 8) | `define('username','username')…` en el shim — sin tocar código |
| `each()` | **3** | Edición manual |
| Offsets con llaves `$x{...}` | **2** | Edición manual |
| `ereg`/`split`/`create_function`/`magic_quotes` | **0** | — |

Los warnings de PHP 8 (claves/variables indefinidas) **no son fatales** porque el legacy
ya corre con `error_reporting = E_ERROR`. Riesgo a vigilar:
`htmlspecialchars`/`htmlentities` con datos latin1 en PHP 8.1+ (cambió encoding/flags por
defecto). Ver [02-arquitectura-objetivo.md](02-arquitectura-objetivo.md) y doc 05.

### Subsistemas del CMS
| Carpeta | Nº PHP | Rol |
|---------|--------|-----|
| `CMS/` (raíz) | 65 | Páginas: `index`, `me`, `account` (~940 ln), `register`, `client_dp`, `login`, `community`, `club`, `badges`, `forum`… |
| `CMS/housekeeping` | ~77 | Panel de administración (rank > 5): usuarios, baneos, contenido, config, logs. |
| `CMS/habblet` | ~74 | Endpoints AJAX (amigos, grupos, coleccionables, minimail, widgets). |
| `CMS/templates` | ~32 | Cabeceras/pies/layouts HTML por contexto. |
| `CMS/locale` | ~23 | i18n: arrays PHP por idioma (en, de, fr, nl). |
| `CMS/myhabbo` | ~19 | Home/perfil del usuario y widgets. |
| `CMS/iot`, `CMS/minimail`, `CMS/habbo-imaging` | ~28 | Módulos menores: contenido, mensajería, render de avatares/insignias. |
| `CMS/includes` | 8 | `mysql.php`, `session.php`, `mus.php`, `sso.php`, `inc.crypt.php`, `version.php`, `news_headlines.php`. |
| `CMS/dirplayer`, `CMS/ruffle`, `CMS/flash` | binarios | Reproductores del cliente (WASM/Flash) + assets. |

### Autenticación (clave para la migración)
- Login en `index.php` → guarda `$_SESSION['username']` y `$_SESSION['password']`
  (el hash HoloHash). `core.php` revalida sesión vs BD en cada request.
- `security_check.php`, `reauthenticate.php` para acciones sensibles.
- **SSO con el juego:** `includes/sso.php` genera un `ticket_sso` (`ST-…-holo-fe`) que
  se pasa al cliente (`client_dp.php`) como parámetro; el emulador lo valida al conectar.

### Integración con el emulador (MUS)
`CMS/includes/mus.php` → `SendMUSData($data)` abre un socket TCP a `cms_system.ip` +
puerto `system_config.server_mus_port` (el `emu:30000`) y envía comandos ASCII crudos
(`UPRS{userid}`, `UPRC{userid}`…) para refrescar rango/créditos en caliente. Sin
autenticación ni reintentos. **Es un punto de integración pequeño y bien acotado** →
fácil de portar a un servicio Laravel.

## 3. Base de datos

- **`DB/holodb.sql`** (~635 KB): **78 tablas**, **todas MyISAM + latin1**.
- MyISAM ⇒ **sin claves foráneas ni transacciones** (relevante para Eloquent).
- Tablas clave: `users`, `users_badges`, `users_bans`, `users_club`, `rooms`,
  `furniture`, `catalogue_*`, `groups_details`, `groups_memberships`,
  `messenger_friendships`, `cms_*` (news, content, minimail, transactions, homes…),
  `system`, `system_config`, `cms_system`.
- `docker/db/02-fixups.sql` ajusta host/puerto del cliente; `03-admin.sql` crea `admin`.

## 4. Componentes no-CMS de la raíz

| Carpeta | Qué es | Tamaño / notas |
|---------|--------|----------------|
| `DCRS/` | Assets del cliente Shockwave (`.dcr/.cct/.cst`), servidos por nginx. | ~65 MB, ~3.981 ficheros binarios. Fuente. |
| `EMU/` | Emulador del juego en **C#/.NET 4.0**, recompilado con **Mono** al arrancar. | ~10 MB, ~43 `.cs` en `Source/`. `holo.exe` se genera en runtime. |
| `dirplayer-rs/` | Reproductor de Shockwave en **Rust+WASM** (proyecto upstream vendado). Tiene **su propio `.git`** (no es submódulo). | **~1.5 GB** de los cuales `node_modules` ~864 MB y `target/` ~451 MB son **artefactos**. |
| `docker/` | Dockerfiles + scripts + configs por servicio. | ~44 KB. |
| `DB/` | `holodb.sql` (esquema + datos semilla). | ~635 KB. |
| Raíz | `docker-compose.yml`, `README.md`, `LEEME-DOCKER.md`. | — |

**Problema de higiene del repo:** **no hay `.gitignore` en la raíz**, así que se están
versionando artefactos de build (`node_modules/`, `target/`, `EMU/bin`, `holo.exe`,
`.playwright-mcp/`…). El `git status` muestra ~263 ficheros modificados, en su mayoría
**re-codificaciones latin1** y cambios de credenciales/rutas para Docker — no cambios
funcionales.

## 5. Fuera del alcance de esta migración
- **El cliente del juego (Shockwave)**: requiere PaleMoon+plugin o el WASM de
  dirplayer-rs. La migración a Laravel **no lo cambia**; Laravel solo sirve el lanzador
  (`client_dp.php`) y los assets DCR.
- **El emulador C#/.NET**: sigue igual. Laravel solo habla con él por MUS/SSO.
