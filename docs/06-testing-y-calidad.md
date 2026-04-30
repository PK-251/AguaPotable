# Estrategia de testing y calidad

## Objetivo

Garantizar que el software que gestiona **dinero, padrón y comprobantes** en J.A.S.S. QUILCATA sea **correcto, auditable y seguro** frente a regresiones. La calidad no es una fase final: **cada módulo con lógica de negocio se implementa en paralelo con sus pruebas**.

## Regla de oro: desarrollo y pruebas en paralelo

- **Toda** nueva regla de negocio, servicio, comando crítico o flujo de persistencia **debe** incluir en el mismo entregable el **paquete mínimo de pruebas** acorde al riesgo.
- **Prohibido** dejar un módulo “hecho” y posponer sus tests a un “sprint de calidad al final”.
- Al diseñar un **Service** o **Job** relevante, definir de inmediato: *qué tests fallarían si el cálculo o la transacción se rompieran*.

## Tipos de pruebas (definición operativa en este proyecto)

### 1. Pruebas unitarias

**Qué son:** Comprueban **una unidad aislada** (clase o método) sin I/O real: cálculo de monto, reglas de periodo, formateo de dinero, validación de DTO, helpers.

**Herramientas:** PHPUnit; mocks cuando el costo de aislary es alto.

**Cuándo (obligatorio):**

- Cálculo de **cobro** (RF-04): desglose cuota, deuda, multas, redondeo.
- Reglas de **cierre** o agregación numérica.
- Cualquier **Rule** o transformación pura reutilizable.

**Ubicación:** `tests/Unit/...` (por servicio, `Support/`, `Rules/`, etc.).

### 2. Pruebas feature (HTTP)

**Qué son:** Simulan un **request HTTP** al kernel de Laravel: rutas, middleware, `FormRequest`, respuesta, **side effects básicos** (sesión, redirecciones, JSON).

**Cuándo (obligatorio):**

- **Login y acceso** (RF-01).
- **Formularios** de pago, padrón, multas, egresos, cierre.

**Herramientas:** `RefreshDatabase` cuando haya DB; `actingAs` para usuarios.

**Ubicación:** `tests/Feature/Web/...`.

### 3. Pruebas de integración (base de datos, storage, colas)

**Qué son:** Verifican **varias capas reales** acopladas: Eloquent, transacciones, discos, jobs encolados (en sync o fake).

**Cuándo (obligatorio para crítico):**

- **Registro de pago** con transacción: persistencia, estado, enlace a padrón, no estado inconsistente ante fallo simulado.
- **Cierre mensual** idempotente: no duplicar cierres; datos coherentes.
- **Generación de PDF** con `Storage::fake()`: ruta presente, archivo generado, policy de acceso.
- Emisión de eventos o escritura en `ActivityLog` cuando sea requisito.

**Ubicación:** `tests/Integration/Database/`, `tests/Integration/Filesystem/`, etc.

### 4. Pruebas E2E (navegador / flujo completo)

**Qué son:** Ejecutan un **navegador** controlado o cliente que recorre flujos completos: login → búsqueda → pago → comprobante.

**Cuándo:** Tras **estabilizar** flujos críticos; no sustituyen feature/API para lógica. Priorizar: cobro completo, cierre y emisión de comprobantes.

**Herramientas:** p. ej. Cypress, Playwright; **atributos `data-cy`** en elementos clave (ver reglas de frontend).

**Ubicación / CI:** script dedicado, opcional en pipeline.

### 5. Pruebas de rendimiento o carga

**Qué son:** Miden **latencia, throughput o uso** bajo carga: búsqueda en padrón grande, generación de reporte, lote de PDFs.

**Cuándo:** Fases de crecimiento o antes de puesta en producción; entorno aislado; **no** en cada commit salvo acuerdo.

**Herramientas:** k6, JMeter, o scripts con datasets sintéticos.

**Ubicación:** `tests/Performance/` o jobs externos de CI.

## Qué pruebas mínimas por módulo crítico (checklist)


| Módulo / dominio                | Unit                   | Feature                | Integración                         | E2E (posterior) | Performance (opc.)    |
| ------------------------------- | ---------------------- | ---------------------- | ----------------------------------- | --------------- | --------------------- |
| Autenticación y roles (RF-01)   | opc. helpers           | **sí**                 | Si hay persistencia de tokens/roles | login flujo     | —                     |
| Padrón y búsqueda (RF-02/03)    | reglas/unicidad        | **sí** (CRUD + filtro) | si FK complejas                     | búsqueda caja   | búsqueda con volumen  |
| Cálculo y pago (RF-04/05/06)    | **sí** (cálculo)       | **sí** (registro)      | **sí** (transacción)                | flujo cajero    | picos de concurrencia |
| Comprobante PDF + QR (RF-07/08) | plantilla/serie (opc.) | **sí** (descarga)      | **sí** (Storage)                    | entrega         | generación masiva     |
| Multas y tarifas (RF-11)        | reglas monto (opc.)    | **sí**                 | impacto en cobro                    | —               | —                     |
| Egresos (RF-12)                 | —                      | **sí**                 | con reporte (opc.)                  | —               | —                     |
| Reporte mensual (RF-09/10)      | totales, periodo       | **sí** (flujo)         | **sí** (doble cierre)               | aprobación      | cierre bajo carga     |
| Auditoría                       | —                      | visibilidad admin      | trazas en `ActivityLog` (opc.)      | —               | —                     |


## Convenciones

- Nombres de test en **español** o **inglés** pero **coherentes** y **descriptivos** (`test_pago_rollback_si_falla_multa`).
- Reemplazar o eliminar `**ExampleTest`** de Laravel: no aporta al dominio.
- Usar **factories/seed** controlados; no depender de orden global de tests.
- Evitar aserciones frágiles a textos de UI no esenciales; asertar estructura, códigos HTTP, y datos de BD/JSON.

## Integración con CI (recomendado)

- Cada **merge / PR** ejecuta: `php artisan test` (y `pint` o lint acordado).
- Añadir pruebas E2E o performance según criterio del equipo, no bloqueo inicial mínimo.

## Referencias

- `05-roadmap-modulos.md` — pruebas por fase.
- `04-arquitectura-aplicacion.md` — dónde colocar lógica testeable.
- `.cursor/rules/05-reglas-testing.mdc` — reglas breves para el asistente.