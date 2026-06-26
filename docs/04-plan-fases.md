# 04 — Plan de implementación por fases

> Enfoque elegido: **runtime único PHP 8.x + port completo del legacy** (sin gateway).
> Ver [02-arquitectura-objetivo.md](02-arquitectura-objetivo.md).

Principios:
- **Estrangulador (Strangler Fig):** el legacy (ya portado a PHP 8) sigue sirviendo hasta
  que cada trozo se reescribe en Laravel nativo.
- **Siempre desplegable:** al final de cada fase el hotel funciona.
- **La seguridad viaja con la reescritura:** cada módulo migrado arregla sus SQLi/CSRF.

Estimaciones **indicativas** (1 desarrollador) para ordenar, no para comprometer fechas.

---

## Fase 0 — Preparación (sin tocar el runtime) · ~2–3 días

- [ ] **`.gitignore` raíz** + `git rm -r --cached` de artefactos (node_modules, target,
      holo.exe, .playwright-mcp). Ver doc 03.
- [ ] **Backup del volumen `dbdata`** (`mysqldump --default-character-set=latin1`) y
      volcado del **esquema** (`--no-data`) como base de las futuras migraciones.
- [ ] Decidir `dirplayer-rs` → submódulo vs polyfill vendado (doc 03).
- [ ] Cerrar decisiones del doc 05 (ubicación Laravel/compose, charset, mecanismo de
      sesión legacy↔Laravel).
- [ ] Confirmar Laravel última estable + PHP objetivo (8.3/8.4).

**Aceptación:** repo limpio, backup verificado, decisiones registradas.

---

## Fase 1 — Port del legacy a PHP 8 (runtime único) · ~1–2 semanas

Objetivo: que **el CMS legacy arranque y funcione tal cual sobre PHP 8.3**, en su propio
contenedor de prueba, **antes** de meterlo bajo Laravel. Esta fase de-riska todo el plan.

- [ ] Construir el **compat-layer** ampliado (`legacy/_compat.php`, vía `auto_prepend_file`):
  - [ ] **Shim `mysql_*`→`mysqli`** (las ~9 funciones nativas que cubren las 1.789 llamadas).
  - [ ] **`define()` de barewords** fatales (`username`, `hkusername`, `hkpassword`, `acp`,
        `REMOTE_ADDR`, `SendMUSData`, …) → neutraliza los ~101 sitios sin editar código.
  - [ ] Mantener shims de sesión existentes.
- [ ] **~5 ediciones manuales**: 3 `each()` → `foreach`/`key()+current()`; 2 offsets
      `$x{...}` → `$x[...]`.
- [ ] Crear un **contenedor PHP 8.3** (nginx+php-fpm) que sirva `CMS/` directamente para
      validar el port de forma aislada (puerto temporal).
- [ ] Verificar **encoding**: `htmlspecialchars`/`htmlentities` con datos latin1 en PHP
      8.1+ (mantener `default_charset=ISO-8859-1` y/o fijar encoding explícito).
- [ ] Mantener `error_reporting=E_ERROR` para que los warnings de PHP 8 no tumben páginas.
- [ ] **Pasada de regresión funcional** del legacy en PHP 8.3 vs PHP 5.6 (login, registro,
      housekeeping, perfil, AJAX habblet, lanzador del cliente).

**Aceptación:** el HoloCMS legacy funciona **igual** sobre PHP 8.3 que sobre 5.6, sin el
contenedor de PHP 5.6. (El compat-layer es temporal; muere en Fase 5.)

---

## Fase 2 — Laravel como front controller + LegacyRunner + reorg · ~1–2 semanas

Objetivo: **todo se sirve a través de Laravel**, en el runtime PHP 8.3 ya validado, con
el legacy ejecutándose in-process. Se hace junto con la reorg para tocar `compose` una vez.

- [ ] Ejecutar la **reorganización de carpetas** (doc 03) en ventana de mantenimiento.
- [ ] `composer create-project laravel/laravel apps/web` (última estable).
- [ ] El servicio **`web`** (nginx + php-fpm 8.3) pasa a ser el público en `:8090`; **se
      elimina el contenedor `cms`/PHP 5.6** (ya no hace falta tras la Fase 1).
- [ ] Mover el legacy a `apps/web/legacy/` y cargar el compat-layer vía `auto_prepend_file`.
- [ ] Conectar Laravel a MariaDB existente (`'charset'=>'latin1'`, **sin migraciones de
      esquema**).
- [ ] Implementar **`App\Legacy\LegacyRunner`** + ruta catch-all: mapea URL→fichero legacy
      (allow-list, sin path traversal), prepara entorno (`chdir`, `IN_HOLOCMS`), `require`
      con output buffering, devuelve `Response`. **In-process, sin proxy HTTP.**
- [ ] Reconciliar **sesión** Laravel ↔ `$_SESSION` nativo del legacy en el mismo proceso
      (pipeline sin `StartSession` para rutas legacy, o driver nativo idempotente).
- [ ] Página `/up` + smoke test e2e (login y navegación legacy a través de Laravel).
- [ ] Tooling: Pint, PHPStan, Pest, Rector, CI mínima.

**Aceptación:** el hotel funciona idéntico, **servido por Laravel en un único runtime
PHP 8.3**; ya no existe contenedor PHP 5.6; DCRs/emulador intactos.

---

## Fase 3 — Estrangulamiento por módulos (iterativo) · el grueso del trabajo

Cada módulo: rutas + controladores + Blade + Eloquent en Laravel, se **retira del
catch-all** (deja de incluirse el legacy), y se arreglan sus SQLi/CSRF/validación.
Como hay **runtime y sesión únicos**, no hay puente que mantener.

Orden por valor y riesgo creciente:
1. **Auth & cuenta** — `login`, `logout`, `register`, `forgot`, `reauthenticate`,
   `account`. Guard de Laravel + verificador de hash legacy + **re-hash a bcrypt** al
   loguear. (Alto valor de seguridad.)
2. **Páginas públicas** — `index`, `news`, `community`, `help`, `privacy`, estáticas.
3. **Housekeeping (admin)** — usuarios, baneos, contenido, config, logs. (Superficie de
   SQLi crítica; autorización por rango con Policies/Gates.)
4. **Comunidad** — grupos (`group_profile`, `groups_*`), foros, aplicaciones, invitaciones.
5. **AJAX `habblet/`** — amigos, grupos, coleccionables, minimail, widgets → rutas
   API/JSON de Laravel. (~74 ficheros casi-plantilla → buen candidato a generación
   semi-automática.)
6. **Perfil / MyHabbo / minimail / imaging** — `me`, `myhabbo/*`, render de insignias.
7. **Lanzador del cliente** — `client_dp`, integración SSO con el juego (último, por su
   acoplamiento con emulador/assets DCR).

Por cada módulo (definición de "hecho"):
- [ ] Rutas Laravel registradas; entrada eliminada del catch-all del LegacyRunner.
- [ ] Eloquent/prepared statements (cero concatenación).
- [ ] CSRF, validación, autorización por rango (Policies).
- [ ] Vistas Blade (componentes reutilizables: header/footer/habblets).
- [ ] Tests Pest (happy-path + regresión de seguridad).
- [ ] Paridad visual/funcional verificada contra el legacy.

**Aceptación de la fase:** el catch-all del LegacyRunner queda vacío.

---

## Fase 4 — Modernización de datos · ~1 semana

- [ ] Migraciones Laravel como **fuente de verdad** del esquema (desde el volcado de F0).
- [ ] **MyISAM → InnoDB**; **latin1 → utf8mb4** (conversión cuidadosa de datos latin1).
- [ ] FKs/índices que faltan; relaciones Eloquent completas.
- [ ] Validar que el **emulador C#** (ODBC) sigue conectando bien con InnoDB/utf8mb4.
- [ ] Retirar `default_charset=ISO-8859-1`; pasar a UTF-8 extremo a extremo.

**Aceptación:** esquema por migraciones, UTF-8 end-to-end, emulador OK.

---

## Fase 5 — Retiro del legacy y del compat-layer · ~2–3 días

- [ ] Borrar `apps/web/legacy/`, el `LegacyRunner` y el catch-all.
- [ ] Eliminar el **compat-layer** (`_compat.php`, shim `mysql_*`, `define()` de barewords)
      y `auto_prepend_file`.
- [ ] `web` (Laravel) queda como **único** servicio web.

**Aceptación:** arquitectura final del doc 02; sin `mysql_*`, sin SQLi, sin shims,
runtime único PHP 8.x.

---

## Fase 6 — Mejora continua (post-migración)
- HTTPS/headers de seguridad, rate-limiting, 2FA opcional.
- Colas para tareas pesadas (imaging, notificaciones MUS).
- Observabilidad (logs estructurados, Sentry), tests de carga.
- Rediseño progresivo de UI (Blade components / SPA) si se desea.

---

## Dependencias entre fases
```
F0 ──▶ F1 (port a PHP 8) ──▶ F2 (Laravel + LegacyRunner + reorg) ──▶ F3 (iterativo) ──▶ F4 ──▶ F5 ──▶ F6
                                                                       ▲
                              F1 valida el legacy en PHP 8 ANTES de meterlo bajo Laravel
F4 puede solaparse con el final de F3 (datos de módulos ya migrados).
```
