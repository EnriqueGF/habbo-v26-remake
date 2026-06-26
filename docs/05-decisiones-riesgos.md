# 05 — Decisiones abiertas, riesgos y preguntas

## Decisiones ya tomadas

| # | Decisión | Resultado |
|---|----------|-----------|
| D1 | Estrategia de runtime | **Runtime único PHP 8.x + port completo del legacy. SIN gateway/proxy.** El legacy se porta a PHP 8 vía compat-layer y Laravel lo ejecuta in-process (`LegacyRunner`). |
| D4 | Charset en Fases 1–3 | **latin1** (match con la BD); utf8mb4 en Fase 4. |

## Decisiones que conviene cerrar antes de la Fase 2

| # | Decisión | Opciones | Recomendación |
|---|----------|----------|---------------|
| D2 | Ubicación de Laravel | a) `apps/web/` (monorepo) · b) raíz del repo | **a** para escalar con varios servicios; b si se prefiere layout estándar |
| D3 | Ubicación de `compose` | a) `docker/compose.yaml` · b) raíz | **b** (menos cambios de paths) salvo querer todo bajo `docker/` |
| D5 | `dirplayer-rs` | submódulo vs polyfill vendado vs igual | **submódulo** + versionar solo el polyfill |
| D6 | Sesión legacy↔Laravel (mismo proceso) | a) rutas legacy sin `StartSession` y `$_SESSION` nativo · b) Laravel con driver de sesión nativo + `session_start()` idempotente | Elegir en Fase 2 lo más simple que funcione (a suele ser más limpio) |
| D7 | Mecanismo de "servir" el legacy | a) `LegacyRunner` in-process (Laravel dispatcha) · b) fallback de nginx a los `.php` legacy (mismo php-fpm) | **a** para mantener Laravel como front controller único |
| D8 | Alcance del rediseño UI | mantener look V26 vs rediseñar | Paridad visual al estrangular; rediseño en Fase 6 |

## Riesgos principales y mitigaciones

| Riesgo | Impacto | Mitigación |
|--------|---------|------------|
| **Shim `mysql_*` con comportamiento sutilmente distinto** a la API original (tipos de retorno, NULL, `mysql_result`, charset de conexión) | Alto — afecta a 1.789 llamadas | Implementar el shim con fidelidad y **batería de regresión en Fase 1** comparando PHP 5.6 vs 8.3; cubrir `mysql_fetch_array` (MYSQL_BOTH), `mysql_num_rows`, `mysql_result` |
| **`htmlspecialchars`/`htmlentities` + latin1 en PHP 8.1+** (flags/encoding por defecto cambiaron → posible mojibake o string vacío) | Medio-Alto | Mantener `default_charset=ISO-8859-1` hasta Fase 4 y/o fijar encoding explícito; validar acentos en páginas clave durante la Fase 1 |
| **Barewords no detectados** (alguno fuera del patrón medido) provoca fatal en PHP 8 | Medio | PHPStan + pasada de regresión por todas las páginas en Fase 1; ampliar la lista de `define()` según aparezcan |
| **SQL injection** mientras el legacy sigue activo | Alto | El legacy ya no se expone como servicio aparte (corre dentro de Laravel); priorizar housekeeping/auth en Fase 3; validación de entrada en el LegacyRunner |
| **Choque de sesión** Laravel ↔ `$_SESSION` nativo en el mismo proceso | Medio | Resolver D6 en Fase 2 con un par de páginas antes de generalizar |
| **Conversión latin1→utf8mb4** corrompe acentos | Alto | Fase 4 con backup, en staging, validando muestras; datos en latin1 real (no doble-codificados) |
| **Romper el entorno vivo** al mover carpetas | Medio | Ventana de mantenimiento, `down` antes de mover, backup de `dbdata`, commit único de reorg |
| **Emulador C#** depende de charset/engine de la BD | Medio | Validar InnoDB/utf8mb4 con sus queries ODBC antes de aplicar en prod |
| **Esfuerzo del estrangulamiento** (386 ficheros) subestimado | Medio | Agrupar por patrón (los ~74 `habblet/ajax_*` son casi plantillas); medir velocidad tras 2 módulos |

> Nota: respecto al plan anterior (gateway), este enfoque **elimina** el riesgo del
> puente de sesión entre runtimes (al ser un único proceso/sesión) pero **añade** el
> riesgo del shim `mysql_*` y del encoding latin1 en PHP 8.1+. El de-risking se concentra
> en la **Fase 1** (port + regresión) antes de meter nada bajo Laravel.

## Preguntas para el equipo / negocio

1. **¿Hay staging** (o se puede crear)? Muy recomendable para la regresión de Fase 1, D6
   y la conversión de charset.
2. **¿Conservar el aspecto clásico V26** o rediseñar al estrangular?
3. **¿Qué módulos importan más** hoy (housekeeping, comunidad, cliente)? Ajusta el orden
   de la Fase 3.
4. **¿Idiomas a soportar** en la i18n de Laravel? (Hoy en/de/fr/nl en `locale/`.)
5. **¿Re-hash oportunista** de contraseñas a bcrypt (transparente) vs reset forzado?
   (Recomendado lo primero.)
6. **¿Mono-repo** o separar emulador/dirplayer a repos propios? (Afecta a D2/D5.)
7. **¿Versión de Laravel y PHP** aprobadas (última estable + PHP 8.3/8.4)?

## Qué NO está en este plan (fuera de alcance)
- Reescribir el **emulador C#/.NET** (sigue en Mono; solo MUS/SSO).
- Sustituir **Shockwave/dirplayer** como tecnología de cliente.
- Migrar la **infraestructura** fuera de Docker Compose (k8s/cloud) — posible Fase 6+.
