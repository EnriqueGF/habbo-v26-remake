# Plan de modernización — Habbo V26 → Laravel

Este directorio contiene el análisis y el plan de implementación para migrar el
hotel retro **Habbo V26** a una arquitectura moderna basada en **Laravel**, en la
que Laravel pasa a servir **todo** el tráfico web y la parte legacy (HoloCMS 2008)
queda **apartada pero contenida dentro del proyecto Laravel**, para ser
estrangulada (reescrita módulo a módulo) de forma incremental y sin downtime.

> **Enfoque elegido (decisión del equipo):** **runtime único PHP 8.x con migración
> completa de versión de PHP**. NO se usa un gateway/proxy a un PHP 5.6 vivo en paralelo.
> El legacy se **porta** a PHP 8 (vía un compat-layer) y Laravel lo ejecuta **in-process**
> mientras se estrangula. Ver [02-arquitectura-objetivo.md](02-arquitectura-objetivo.md).

> Estado del proyecto al redactar este plan: **encendido y en marcha** vía Docker
> Compose (CMS PHP 5.6, MariaDB 10.6, emulador .NET/Mono, DCRs en nginx, proxy
> TCP↔WebSocket). Todo el plan está diseñado para no romper el entorno vivo.

## Veredicto rápido de viabilidad

**Sí es viable, y la estrategia es _Strangler Fig_ sobre un runtime único PHP 8.x.**
Se hace una **migración completa de versión de PHP**: el legacy se porta a PHP 8 con un
compat-layer centralizado y Laravel se coloca como **front controller único**, sirviendo
lo reescrito y ejecutando el legacy **in-process** (no por HTTP) para lo aún no migrado.
Cada módulo reescrito se retira del legacy; cuando migra el último, el legacy y el
compat-layer desaparecen.

**Lo que NO se hace:** ni un gateway/proxy a un PHP 5.6 vivo en paralelo, ni una
reescritura "big-bang" (386 ficheros / 78 tablas de golpe).

**El port a PHP 8 es tratable** porque se centraliza en shims, no en editar 386 ficheros
(medido sobre el código real):
- **1.789 llamadas `mysql_*`** (solo ~9 funciones nativas) → **un shim** sobre `mysqli`,
  cero ediciones de call-sites.
- **~101 barewords fatales en PHP 8** → se neutralizan con `define('username','username')…`
  en el shim, sin tocar código.
- Solo **~5 ediciones manuales** (3 `each()`, 2 offsets `{}`). Nada de `ereg`,
  `create_function` ni `magic_quotes`.
- El legacy ya corre con `error_reporting=E_ERROR`, así que los warnings de PHP 8 no
  tumban páginas.

Detalle técnico en [01-estado-actual.md](01-estado-actual.md) y
[02-arquitectura-objetivo.md](02-arquitectura-objetivo.md).

## Índice de documentos

| # | Documento | Contenido |
|---|-----------|-----------|
| 01 | [Estado actual](01-estado-actual.md) | Inventario real del código, BD, integraciones y deuda técnica. |
| 02 | [Arquitectura objetivo](02-arquitectura-objetivo.md) | Laravel como gateway + legacy estrangulado. Auth, sesión, charset, MUS/SSO. |
| 03 | [Reorganización de carpetas](03-reorganizacion-carpetas.md) | Nueva estructura monorepo, tabla origen→destino y cambios en `docker-compose`. |
| 04 | [Plan por fases](04-plan-fases.md) | Fases 0→6 con tareas concretas, criterios de aceptación y orden de estrangulamiento. |
| 05 | [Decisiones y riesgos](05-decisiones-riesgos.md) | Puntos de decisión abiertos, riesgos, mitigaciones y preguntas para el equipo. |
| 06 | [Estado de implementación](06-estado-implementacion.md) | **Lo que YA está hecho y funcionando** (Fases 0–2 + slice de la 3). |

> **Estado (2026-06-26):** Fases 0, 1 y 2 **completas y verificadas** + primer slice de
> la Fase 3. El hotel se sirve íntegramente por **Laravel 13 / PHP 8.3** (runtime único),
> con el legacy portado y ejecutándose in-process. Detalle en
> [06-estado-implementacion.md](06-estado-implementacion.md).

## Resumen ejecutivo (1 minuto)

1. **Fase 0 — Preparación** (sin tocar el runtime): `.gitignore` raíz para dejar de
   versionar 1.5 GB de artefactos, backup de BD, fijar decisiones.
2. **Fase 1 — Port del legacy a PHP 8**: compat-layer (shim `mysql_*` + `define()` de
   barewords) + ~5 ediciones; el HoloCMS funciona igual sobre **PHP 8.3** que sobre 5.6,
   validado en aislamiento. **Es la fase que de-riska todo.**
3. **Fase 2 — Laravel front controller + LegacyRunner + reorg**: Laravel (PHP 8.3) como
   servicio público `web`; **se elimina el contenedor PHP 5.6**; el legacy se ejecuta
   in-process vía `LegacyRunner`. **Resultado: todo se sirve por Laravel en un runtime
   único.**
4. **Fase 3 — Estrangulamiento por módulos**: login → cuenta → housekeeping →
   comunidad/grupos → ajax habblet → perfil → cliente. Auth unificada (guard + verificador
   de hash legacy + re-hash a bcrypt). Cada módulo arregla de paso sus SQLi/CSRF.
5. **Fase 4 — Datos**: MyISAM→InnoDB, latin1→utf8mb4, migraciones como fuente de verdad.
6. **Fase 5 — Retiro del legacy y del compat-layer**: borrar `legacy/`, `LegacyRunner`,
   shims y `auto_prepend_file`.

El valor (sitio moderno, seguro, mantenible) se entrega de forma incremental desde la
Fase 2; no hay que esperar al final. Al ser un **único proceso/sesión**, desaparece el
puente de sesión entre runtimes que era el mayor riesgo del enfoque gateway.
