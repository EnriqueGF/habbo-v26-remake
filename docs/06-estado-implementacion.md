# 06 — Estado de implementación (lo que YA está hecho y funcionando)

Este documento registra lo realmente construido y verificado, frente al plan
(docs 00–05). Fecha: 2026-06-26.

## Resumen

**Fases 0, 1 y 2 completas y verificadas, más el primer slice de la Fase 3.** El
hotel se sirve **íntegramente a través de Laravel 13 sobre un único runtime PHP 8.3**;
el HoloCMS legacy quedó portado a PHP 8 y se ejecuta in-process. Ya **no existe el
contenedor PHP 5.6**. La estructura de la raíz está reorganizada en monorepo.

Verificación visual: login y panel del usuario `admin` renderizan correctamente en
`http://localhost:8090` servidos por Laravel (capturas tomadas durante el cutover).

## Fase 0 — Red de seguridad ✅
- Backup de la BD viva: `backups/holodb-pre-migration.sql` (78 tablas, latin1).
- `.gitignore` raíz (artefactos, vendor, dirplayer, caché de imágenes, backups).
- Commit baseline del estado dockerizado funcional.

## Fase 1 — Port del legacy a PHP 8.3 ✅
Centralizado en `apps/web/legacy/_compat.php` (cargado por el LegacyRunner):
- **Shim `mysql_*` sobre `mysqli`** cubriendo las ~2.500 llamadas. Incluye un wrapper
  `HoloMysqlResult` con `__toString()` que imita el viejo `"Resource id #N"`, para que
  el código que (por bug) usaba el handle como string no fatale en PHP 8.
- **`define()` de barewords-constante** (`username`, `acp`, `hkusername`, `hkpassword`,
  `SendMUSData`, `REMOTE_ADDR`, `shortname`, claves de array…) — fatales en PHP 8.0.
- Shims de `session_is_registered/register/unregister` y de `get_magic_quotes_*`.
- `_compat.php` se mantiene **parseable también en PHP 5.6** (no se usó `??`, etc.).
- Correcciones de código fuente: `mktime()` sin el 7º argumento (core.php) y offsets
  `$x{...}` → `$x[...]` (housekeeping/vouchers.php).
- Validación: barrido de los 383 ficheros PHP en 8.3 con **cero regresiones**; los
  únicos 500 restantes son bugs preexistentes que también fallan en 5.6.

## Fase 2 — Laravel front controller + reorg + cutover ✅
- **Laravel 13.17** en `apps/web` (PHP 8.3, `platform.php` fijado a 8.3.31), conexión a
  MariaDB `v26` con `charset=latin1`, sesión/caché en fichero (no toca el esquema).
- **`App\Legacy\LegacyRunner`**: ejecuta el legacy in-process para toda ruta no migrada
  (catch-all `Route::any`). Detalles clave resueltos:
  - Restaura `error_reporting(E_ERROR)` durante el legacy (Laravel fuerza
    `error_reporting(-1)` y convierte cada warning en excepción fatal — rompería casi
    todas las páginas legacy).
  - `chdir` al directorio del script + `require` del compat-layer (replica mod_php).
  - Conserva cabeceras/cookies/redirects del legacy y fija `Content-Type` latin1.
  - Sirve assets estáticos; omite middleware de cookies/sesión/CSRF (el legacy usa
    sesión PHP nativa).
- **Symlinks** en `apps/web/public/` (`web-gallery`, `Badges`, `c_images`, `flash`,
  `dirplayer`, `ruffle`) → `../legacy/...`, para que **Apache** sirva los assets pesados
  directamente (no pasan por Laravel).
- **Reorganización monorepo** (ver doc 03): `apps/web` (Laravel+legacy), `services/`
  (emulator, dirplayer), `assets/dcr`, `database/` (seed, fixups), `docker/` (web, dcr,
  emulator, ws-proxy, dirplayer-builder), `docs/`.
- **Cutover** sin downtime relevante: stack reconstruido en los puertos reales
  (web :8090, dcrs :8091, emu 1232/30000, proxy 8092/8093).

## Fase 3 — Estrangulamiento (en curso) ✅ (auth migrado)
- Rutas nativas `/_health` y `/_status` (esta última lee la BD v26 vía Eloquent/latin1).
- `App\Services\EmulatorClient` (MUS), `App\Support\HoloHash`.
- **Módulo AUTH migrado a Laravel nativo:**
  - `POST /` (el formulario legacy hace POST a index.php, que Apache sirve por
    public/index.php, así que Laravel lo recibe como POST "/") → `LoginController@login`:
    Eloquent parametrizado (sin SQLi), rate-limiting, verificación HoloHash, chequeo de
    baneo. Escribe la sesión PHP nativa (`App\Legacy\LegacySession`) para que las páginas
    legacy reconozcan al usuario.
  - `GET|POST /logout.php` → `LoginController@logout`.
  - `App\Http\Middleware\BindLegacyUser` (alias `legacy.user`): resuelve el usuario actual
    desde la sesión legacy en rutas nativas (`$request->user()`).
  - Modelo `App\Models\User` sobre la tabla `users`.
- **Tooling y tests:** Pint (formato), PHPUnit. BD de test `v26_test` (copia InnoDB de
  v26 para transacciones), configurada en `phpunit.xml`. 8 tests verdes (unit HoloHash +
  feature login/logout). Verificado además por navegador (login/logout reales en :8090).
- **Nota de seguridad:** el re-hash a bcrypt se hará cuando la auth legacy se retire
  (Fase 5); de momento el hash sigue siendo HoloHash para coexistir con el legacy.

## Lo que queda (pendiente — Fase 3 completa → 5)
- Reescribir módulo a módulo en Laravel nativo (auth → cuenta → housekeeping →
  comunidad → habblet → perfil → cliente), retirando cada uno del LegacyRunner y
  arreglando sus SQLi/CSRF. Ver orden en doc 04.
- Fase 4: MyISAM→InnoDB, latin1→utf8mb4, migraciones como fuente de verdad.
- Fase 5: retirar `apps/web/legacy`, el LegacyRunner y el compat-layer.
- `services/dirplayer`: convertir en submódulo git (hoy movido pero aún repo embebido).

## Receta para migrar el siguiente módulo (repetible)

Patrón establecido con el módulo auth; cada módulo sigue estos pasos:

1. **Modelo(s) Eloquent** sobre las tablas implicadas (`$table`, `$timestamps=false`,
   `$guarded`/`$casts`). Reutiliza `App\Models\User`.
2. **Controlador(es)** en `app/Http/Controllers/...` con consultas Eloquent/parametrizadas
   (cero concatenación SQL), validación, autorización (Policies por rango para
   housekeeping) y CSRF cuando la vista se migre a Blade con `@csrf`.
3. **Rutas nativas** en `routes/web.php` ANTES del catch-all. Recuerda: una URL legacy
   `algo.php` llega a Laravel como `algo.php`, pero `/index.php` llega como `/`.
4. **Vista Blade** que extienda un layout común (extraído de `legacy/templates/`),
   usando los assets vía symlinks de `public/`. Usa el middleware `legacy.user` para
   `$request->user()`.
5. **Interoperar con el legacy**: escribe/lee `App\Legacy\LegacySession` para que las
   páginas aún no migradas reconozcan el estado de sesión.
6. **Tests** (PHPUnit) en `tests/Feature/...` contra `v26_test` con `DatabaseTransactions`;
   unit para la lógica pura. `php artisan test` y `./vendor/bin/pint`.
7. La ruta deja de caer en el `LegacyRunner` automáticamente al existir la ruta nativa.

**CI:** `.github/workflows/ci.yml` levanta MariaDB, ejecuta `database/setup-test-db.sh`
(carga `holodb.sql` + fixups y convierte a InnoDB), y corre Pint + PHPUnit en cada push/PR.

## Cómo operar (rápido)
```bash
docker compose up -d --build     # levanta el stack (web Laravel + db + dcrs + emu + proxy)
docker compose ps                # estado
docker compose logs -f web       # logs de Laravel/Apache
docker compose down              # parar (la BD persiste en el volumen dbdata)
```
- Web: http://localhost:8090  ·  Housekeeping: http://localhost:8090/housekeeping/
- DCRs: http://localhost:8091  ·  Emulador: 1232 (juego) / 30000 (MUS)
- Admin: `admin` / `admin`.
- Rutas nativas de comprobación: `/_health`, `/_status`.
