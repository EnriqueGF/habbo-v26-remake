# Habbo Hotel V26 — Stack Dockerizado (operación)

Todo corre dentro de Docker. La web la sirve **Laravel 13 sobre PHP 8.3** (runtime
único); el HoloCMS legacy quedó portado a PHP 8 y se ejecuta **in-process** dentro de
Laravel mientras se estrangula (ver [06-estado-implementacion.md](06-estado-implementacion.md)).
El emulador `.NET` se recompila con **Mono** y se conecta a MariaDB por **ODBC**.

## Arrancar / parar

```bash
cd /var/home/enrique/Escritorio/Habbo-V26

docker compose up -d --build   # arranca todo (1ª vez compila emulador e imagen web)
docker compose ps              # estado
docker compose logs -f web     # logs de Laravel/Apache (front controller)
docker compose logs -f emu     # consola del emulador
docker compose down            # parar (la BD persiste en el volumen dbdata)
docker compose down -v         # parar y BORRAR la BD (reinstala desde cero)
```

## URLs y puertos

| Servicio        | URL / Puerto                | Notas                                   |
|-----------------|-----------------------------|-----------------------------------------|
| **Web**         | http://localhost:8090       | Laravel (front controller) + legacy in-process |
| Housekeeping    | http://localhost:8090/housekeeping/ | Panel de administración (rank > 5) |
| Salud (nativo)  | http://localhost:8090/_health · /_status | Rutas Laravel nativas |
| DCR's (cliente) | http://localhost:8091       | Assets Shockwave (`habbo.dcr`, `vars.txt`) |
| Emulador juego  | `127.0.0.1:1232` (TCP)      | Donde conecta el cliente Shockwave      |
| Emulador MUS    | `127.0.0.1:30000` (TCP)     | Canal interno servidor/CMS              |
| MariaDB         | `127.0.0.1:3307`            | usuario `habbo` / `habbo`, BD `v26`     |

> Puertos 8090/8091 elegidos porque 8080-8083 estaban ocupados por otro proyecto.

## Cuenta de administrador

| Usuario | Contraseña | Rango |
|---------|------------|-------|
| `admin` | `admin`    | 7 (admin) |

## Estructura del repo

```
apps/web/            Laravel 13 (front controller)
apps/web/legacy/     HoloCMS legacy (servido in-process por App\Legacy\LegacyRunner)
apps/web/public/     docroot; symlinks a los assets estáticos del legacy
services/emulator/   emulador .NET (Mono)
services/dirplayer/  reproductor Shockwave Rust/WASM (upstream)
assets/dcr/          assets del cliente Shockwave (nginx :8091)
database/seed/       holodb.sql · database/fixups/ ajustes + admin
docker/              imágenes: web, dcr, emulator, ws-proxy, dirplayer-builder
docs/                análisis y plan de migración
```

## ⚠️ Para jugar de verdad necesitas Shockwave

El cliente V26 es **Adobe/Macromedia Shockwave** (`.dcr`), que ningún navegador moderno
ejecuta. Para entrar al hotel necesitas **[PaleMoon](https://www.palemoon.org/)** con el
plugin de Shockwave (o el reproductor WASM de `services/dirplayer`). Toda la parte de
servidor (web, login, BD, emulador, entrega de DCR's) está montada y funcionando.

## Cómo se sirve el legacy (resumen técnico)

Laravel es el único front controller. Las rutas ya migradas se atienden de forma nativa;
el resto cae en `App\Legacy\LegacyRunner`, que ejecuta el script legacy en el mismo
proceso PHP 8.3 cargando `apps/web/legacy/_compat.php` (shim `mysql_*`→mysqli, `define()`
de barewords, shims de sesión/magic_quotes) y restaurando `error_reporting(E_ERROR)`.
Los assets estáticos pesados los sirve Apache directamente vía symlinks en `public/`.
No hay segundo runtime ni proxy HTTP. Detalle en los docs 02 y 06.
