# 03 — Reorganización de la estructura de carpetas

## Problemas de la estructura actual

```
Habbo-V26/
├── CMS/            # legacy + assets mezclados
├── DB/             # 1 .sql
├── DCRS/           # 65 MB de assets binarios
├── dirplayer-rs/   # 1.5 GB, su propio .git, 90 % artefactos
├── docker/         # dockerfiles + compose disperso
├── EMU/            # emulador C#
├── docker-compose.yml
├── README.md
└── LEEME-DOCKER.md
```

- Todo cuelga de la raíz sin agrupación lógica (app vs servicios vs assets vs infra).
- **Sin `.gitignore` raíz** → se versionan `node_modules/`, `target/`, `EMU/bin`,
  `holo.exe`, `.playwright-mcp/`… (≈1.5 GB innecesarios).
- `dirplayer-rs/` tiene su propio `.git` anidado: hoy es un repo dentro del repo, no un
  submódulo limpio.
- No hay sitio natural donde colocar la nueva app Laravel.

## Estructura objetivo (monorepo)

```
Habbo-V26/
├── apps/
│   └── web/                  # ⬅️ LARAVEL — la app que sirve TODO
│       ├── app/ config/ public/ resources/ routes/ database/ tests/
│       ├── legacy/           # HoloCMS apartado (antes /CMS)  ← servido por Laravel
│       ├── composer.json  artisan  .env(.example)
│       └── ...
├── services/
│   ├── emulator/             # antes /EMU            (C#/.NET + Mono)
│   ├── ws-proxy/             # antes /docker/proxy   (Node TCP↔WS)
│   └── dirplayer/            # antes /dirplayer-rs   (submódulo git)
├── assets/
│   └── dcr/                  # antes /DCRS           (assets Shockwave)
├── database/
│   ├── seed/holodb.sql       # antes /DB/holodb.sql
│   └── fixups/               # antes /docker/db/*.sql
├── docker/                   # solo Dockerfiles + compose
│   ├── compose.yaml          # antes /docker-compose.yml
│   ├── web/ db/ dcr/ emulator/ ws-proxy/ dirplayer-builder/   # "web" (PHP 8.3) sirve Laravel + legacy
├── docs/
├── .gitignore                # ⬅️ NUEVO (raíz)
└── README.md  (+ LEEME-DOCKER.md fusionado o movido a docs/)
```

Criterio: **`apps/`** (lo que produce HTML/HTTP) · **`services/`** (procesos de soporte)
· **`assets/`** (estáticos pesados) · **`database/`** (esquema/semilla) · **`docker/`**
(infra) · **`docs/`** (esto).

> Alternativa más conservadora si se prefiere el layout estándar de Laravel: dejar
> Laravel **en la raíz** y meter el legacy en `./legacy`. Es válido, pero al haber
> varios servicios no-PHP hermanos, el monorepo `apps/services/assets` escala mejor
> (que es justo el objetivo). Los nombres son ajustables; lo importante es la agrupación.

## Tabla de mapeo origen → destino

| Origen actual | Destino propuesto | Método |
|---------------|-------------------|--------|
| `CMS/` | `apps/web/legacy/` | `git mv` |
| `EMU/` | `services/emulator/` | `git mv` |
| `dirplayer-rs/` | `services/dirplayer/` | **submódulo** (ver abajo) |
| `DCRS/` | `assets/dcr/` | `git mv` |
| `DB/holodb.sql` | `database/seed/holodb.sql` | `git mv` |
| `docker/db/*.sql` | `database/fixups/*.sql` | `git mv` |
| `docker/cms/` (PHP 5.6) | **eliminado** — se sustituye por `docker/web/` (PHP 8.3) | borrar tras Fase 1 |
| `docker/proxy/` | `docker/ws-proxy/` + código a `services/ws-proxy/` | `git mv` |
| `docker/dcrs/`, `docker/emu/`, `docker/dirplayer-builder/` | `docker/dcr/`, `docker/emulator/`, `docker/dirplayer-builder/` | `git mv` |
| `docker-compose.yml` | `docker/compose.yaml` | `git mv` |
| `LEEME-DOCKER.md` | `docs/operacion-docker.md` | `git mv` |
| (nuevo) | `docker/web/` (Dockerfile nginx+php-fpm 8.3) | crear en Fase 1 |
| (nuevo) | `.gitignore` raíz | crear en Fase 0 |

## `.gitignore` raíz (Fase 0 — hacerlo YA, antes de mover nada)

```gitignore
# Artefactos de build
**/node_modules/
**/target/
services/dirplayer/**            # si pasa a submódulo
apps/web/vendor/
apps/web/public/build/
apps/web/storage/*.key
apps/web/.env

# Emulador (se compila en runtime)
services/emulator/bin/
services/emulator/obj/
services/emulator/holo.exe
services/emulator/*.suo

# Herramientas locales
.playwright-mcp/
.DS_Store
```
> Tras añadirlo, `git rm -r --cached` de lo ya trackeado por error (node_modules,
> target, holo.exe…) para limpiar el índice sin borrar los ficheros del disco.

## `dirplayer-rs` → submódulo

`dirplayer-rs/` es un clon de un proyecto upstream con su **propio `.git`** y 1.5 GB de
artefactos. Recomendado:
1. Identificar el remoto/commit de upstream.
2. Quitarlo del árbol (`git rm -r --cached dirplayer-rs`), conservando el remoto.
3. Añadirlo como **submódulo** en `services/dirplayer/` fijado al commit que se usa.
4. El contenedor `dirplayer-builder` compila el WASM/polyfill que consume el cliente;
   se versiona **solo el polyfill resultante** (`CMS/dirplayer/…`, hoy en
   `apps/web/legacy/dirplayer/`), no las fuentes ni los artefactos.

Si se prefiere no usar submódulos, basta con vendar **solo el polyfill construido** y
mover el resto del proyecto fuera del repo.

## Cambios en `docker-compose` (paths de los bind-mounts)

Con la nueva estructura, las rutas de `volumes`/`build` cambian así (extracto):

```yaml
# docker/compose.yaml  (antes docker-compose.yml)
services:
  db:
    volumes:
      - ../database/seed/holodb.sql:/docker-entrypoint-initdb.d/01-holodb.sql:ro
      - ../database/fixups/02-fixups.sql:/docker-entrypoint-initdb.d/02-fixups.sql:ro
      - ../database/fixups/03-admin.sql:/docker-entrypoint-initdb.d/03-admin.sql:ro

  web:                      # NUEVO — Laravel (PHP 8.3), ÚNICO servicio web
    build: ./web            # nginx + php-fpm 8.3; sirve Laravel y, vía LegacyRunner,
    volumes: [ ../apps/web:/var/www/html ]   # el legacy in-process (apps/web/legacy)
    ports: [ "8090:80" ]
    depends_on: [ db ]
    # El antiguo contenedor "cms" (PHP 5.6) se ELIMINA: ya no hay segundo runtime.

  dcrs:
    volumes:
      - ../assets/dcr:/usr/share/nginx/html:ro
      - ./dcr/default.conf:/etc/nginx/conf.d/default.conf:ro
    ports: [ "8091:80" ]

  emu:
    build: ./emulator
    volumes: [ ../services/emulator:/emu ]

  proxy:
    build: ./ws-proxy
```
> Como `compose.yaml` se mueve a `docker/`, los paths relativos pasan a usar `../`.
> Alternativamente, mantener `compose.yaml` en la raíz para no tocar la base de los
> paths — es una decisión de gusto (ver doc 05).

## Cómo ejecutar la reorg sin romper el entorno vivo

El stack corre con `restart: unless-stopped` y bind-mounts a rutas concretas → **mover
carpetas con el stack levantado rompe los mounts**. Secuencia segura (ventana de
mantenimiento corta):

1. `git status` limpio o stasheado; **backup del volumen `dbdata`** (`mysqldump`).
2. `docker compose down` (los datos persisten en el volumen `dbdata`).
3. Hacer todos los `git mv` de la tabla de mapeo en un único commit.
4. Actualizar `docker/compose.yaml` con los nuevos paths.
5. `docker compose -f docker/compose.yaml up -d --build`.
6. Verificar: web (8090), DCRs (8091), login, conexión emulador.
7. Commit de la reorg + actualización de `README.md`/`docs`.

> Esta reorg se ejecuta dentro de la **Fase 1** (misma ventana que el andamiaje de
> Laravel), para tocar `compose` una sola vez. Ver doc 04.
