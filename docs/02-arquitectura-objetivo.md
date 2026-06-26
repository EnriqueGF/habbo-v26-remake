# 02 — Arquitectura objetivo

> **Decisión tomada:** **runtime único PHP 8.x** y **migración completa de versión de
> PHP**. NADA de gateway/proxy a un PHP 5.6 vivo en paralelo. El CMS legacy se **porta**
> a PHP 8, queda **apartado dentro del proyecto Laravel** y Laravel lo ejecuta
> **en el mismo proceso** (in-process), no por HTTP. Cuando cada módulo se reescribe en
> Laravel nativo, el legacy correspondiente se retira; al final no queda legacy.

## Por qué esto es viable (cuantificado)

El miedo razonable es "386 ficheros incompatibles con PHP 8". La realidad medida sobre
`CMS/` es que el muro se concentra en muy pocos puntos, **centralizables en un único
fichero de compatibilidad**:

| Rotura PHP 7/8 | Apariciones | Cómo se resuelve | ¿Edita call-sites? |
|----------------|-------------|------------------|--------------------|
| `mysql_*` (eliminada en PHP 7.0) | **1.789 llamadas**, solo ~9 funciones nativas | **Un shim** sobre `mysqli` | **No** |
| Barewords-constante fatales en PHP 8.0 (`session_is_registered(username)`, `$_SERVER[REMOTE_ADDR]`, `function_exists(SendMUSData)`) | **~101** | `define('username','username')…` en el shim | **No** |
| `session_is_registered/register/unregister` | (las 74 de arriba) | Shim ya existente en `_compat.php` | No |
| `each()` | **3** | Edición manual → `foreach`/`key+current` | Sí (3) |
| Offsets con llaves `$x{...}` | **2** | Edición manual → `$x[...]` | Sí (2) |
| `ereg`/`split`/`create_function`/`magic_quotes` | **0** | — | — |

Es decir: **1 fichero de shims + ~5 ediciones manuales** hacen que el legacy arranque en
PHP 8. El resto (warnings de PHP 8 por claves/variables indefinidas) **no es fatal**: el
legacy ya corre con `error_reporting = E_ERROR`, así que las páginas siguen renderizando.

Riesgo residual a vigilar (no fatal, pero sí de comportamiento): `htmlspecialchars()` /
`htmlentities()` cambiaron flags y encoding por defecto (PHP 8.1 usa UTF-8 +
`ENT_QUOTES|ENT_SUBSTITUTE`). Con datos **latin1** hay que fijar encoding explícito o
mantener `default_charset=ISO-8859-1` hasta la Fase 4 (utf8mb4). Ver doc 05.

## Diagrama de la arquitectura de transición (runtime único)

```
                         ┌─────────────────────────────────────────────┐
   Navegador ──:8090──▶  │  Servicio "web"  (nginx + php-fpm 8.3)        │
                         │  ÚNICO runtime PHP, ÚNICA sesión              │
                         │                                              │
                         │  public/index.php  = front controller Laravel│
                         │   routes/web.php:                            │
                         │    • rutas migradas → controladores Laravel  │
                         │      (Blade, Eloquent)                       │
                         │    • catch-all      → LegacyRunner           │
                         │        require legacy/<ruta>.php  (in-process)│
                         │        sobre el compat-layer (mysql_* shim…)  │
                         └───────────────┬──────────────────────────────┘
                                         │
        ┌────────────────┬───────────────┴───────────┬───────────────────┐
        ▼                ▼                           ▼                   ▼
   MariaDB (v26)    Emulador (MUS/SSO          DCRs (nginx)        Proxy TCP↔WS
   compartida       :30000 / :1232)            assets .dcr          (cliente)
```

No hay segundo contenedor de PHP. No hay proxy HTTP. Laravel es el **front controller
único** y, para lo no migrado, **incluye el script legacy en el mismo proceso PHP 8.3**.

## Cómo Laravel "sirve" el legacy sin gateway: el `LegacyRunner`

Una ruta catch-all de Laravel (la última en `routes/web.php`) delega en
`App\Legacy\LegacyRunner`, que:
1. Mapea el path de la URL al fichero legacy correspondiente en `legacy/` (con allow-list
   de extensiones y normalización de rutas — sin path traversal).
2. Prepara el entorno que el legacy espera (`chdir` al dir legacy, `define('IN_HOLOCMS')`,
   carga del compat-layer que ya está como `auto_prepend_file`).
3. `require` del script con _output buffering_ y devuelve el resultado como `Response`.

Esto es **ejecución in-process** en el runtime PHP 8.3, no un proxy. Cumple "no gateway"
(no hay segundo runtime ni reenvío HTTP) y "todo servido por Laravel" (el router de
Laravel es quien dispatcha cada request, incluido el legacy).

> Alternativa más simple si se acepta relajar "todo lo dispatcha Laravel": servir los
> `.php` legacy directamente con el mismo nginx+php-fpm 8.3 (fallback de nginx). Mismo
> runtime único, pero Laravel deja de ser el dispatcher del legacy. Recomendado el
> `LegacyRunner` para mantener un único front controller.

## El compat-layer (`legacy/_compat.php`, ampliado)

Se carga vía `auto_prepend_file` (ya se hace hoy). Contiene:
- **Shim `mysql_*` sobre `mysqli`**: `mysql_connect`, `mysql_select_db`, `mysql_query`,
  `mysql_fetch_assoc`, `mysql_fetch_array`, `mysql_fetch_row`, `mysql_num_rows`,
  `mysql_result`, `mysql_error`, `mysql_insert_id`, `mysql_affected_rows`,
  `mysql_real_escape_string`. (Mantiene una conexión global como el original.)
- **`define()` de barewords** usados como string sin comillas:
  `username`, `hkusername`, `hkpassword`, `acp`, `REMOTE_ADDR`, `SendMUSData`, etc. →
  cada uno definido a su propio nombre, para que el bareword resuelva al string esperado.
- **Shims de sesión** (`session_is_registered/register/unregister`) — ya presentes.
- Constantes/flags de encoding para `htmlspecialchars`/`htmlentities` si se opta por
  fijarlas globalmente.

> El compat-layer es **temporal**: muere en la Fase 5, cuando ya no queda legacy.

## Identidad y sesión: simplificación clave de este enfoque

Al haber **un solo runtime y una sola sesión PHP**, **desaparece el puente de sesión
entre runtimes** que era el riesgo nº1 del enfoque gateway. El legacy usa `$_SESSION`
nativo; Laravel, en las rutas migradas, lee/escribe la misma sesión.

Detalle a resolver (config, no arquitectura): evitar el choque entre el middleware de
sesión de Laravel y el `session_start()` nativo del legacy dentro del mismo proceso. Se
elige una de:
- Las rutas legacy pasan por un pipeline mínimo sin el `StartSession` de Laravel y usan
  sesión nativa; o
- Laravel usa el driver de sesión nativo y el `session_start()` legacy se hace idempotente.

La autenticación se unifica en la Fase 3: guard de Laravel que valida el hash legacy
(`md5("235x17aXCaRb".$password)`) y **re-hashea a bcrypt** al loguear (migración de
credenciales transparente).

## Servicios portados a Laravel (pequeños y autocontenidos)
- `App\Services\EmulatorClient` ← `includes/mus.php` (socket TCP a `emu:30000`).
- `App\Services\SsoService` ← `includes/sso.php` + `GenerateTicket()`.
- `App\Support\HoloHash` ← `includes/inc.crypt.php` (verificación + rehash).
- Config vía `.env` + `config/*.php` (sustituye `CMS/config.php` y lecturas de
  `cms_system`/`system_config`).

## Charset: latin1 ahora, utf8mb4 después
- **Hasta Fase 3:** Laravel y el shim conectan con `charset=latin1`; `default_charset`
  del runtime sigue en ISO-8859-1 para no romper el legacy ni el render de acentos.
- **Fase 4:** conversión a **utf8mb4** + **InnoDB** (con cuidado: datos almacenados como
  latin1). Necesario para transacciones/FKs de Eloquent.

## Eloquent y esquema
- **Fases 1–3:** Eloquent mapea las 78 tablas existentes tal cual (`$table`,
  `$primaryKey`, `$timestamps=false`, casts). Sin migraciones que alteren el esquema vivo.
- **Fase 4:** migraciones de Laravel como fuente de verdad, FKs, InnoDB.

## Stack objetivo
- **Laravel** última estable (12.x/13.x — confirmar en laravel.com) sobre **PHP 8.3/8.4**,
  **nginx + php-fpm** (un solo servicio web).
- **Blade** (las plantillas legacy se reescriben a componentes Blade al estrangular).
- **Eloquent** sobre MariaDB (latin1 → utf8mb4 en Fase 4).
- **Vite** para la UI nueva; assets DCR servidos como estáticos.
- **Pint / PHPStan / Pest / Rector** desde el día 1 (Rector ayuda en modernización
  progresiva del legacy ya portado).

## Estado final (post-Fase 5)
```
Navegador ──:8090──▶ Laravel (nginx+php-fpm 8.3) ──▶ MariaDB (utf8mb4/InnoDB)
                          ├─▶ EmulatorClient (MUS/SSO) ──▶ emu
                          └─▶ assets DCR (estáticos) + proxy WS
```
Sin legacy, sin compat-layer, sin `mysql_*`, sin SQLi, con CSRF/validación/bcrypt y un
único runtime PHP 8.x.
