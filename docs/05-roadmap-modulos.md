# Roadmap de implementación por fases

El orden busca: **cimentar acceso y datos**, luego **padrón y cobro** (núcleo), después **comprobantes y reporte**, y **saturar calidad** con pruebas en **paralelo** a cada fase, no al final.

La columna **“Pruebas mínimas (en paralelo)”** es obligatoria: definen el contrato mínimo de calidad al cerrar la fase (ajustar nombres de tests al código real).

---

## Fase 0 — Contexto, convenios y límite de deuda técnica

- Completar documentación y reglas en `docs/` y `.cursor/`.
- Asegurar build frontend (Vite, Bootstrap) y convención de nombres de módulos.
- Eliminar o reemplazar pruebas plantilla que no aporten (p. ej. `ExampleTest`); sustituir por un test de humo de la app si se desea (ruta pública, health).

**Pruebas mínimas (en paralelo):**  
- Un test de **smoke** (p. ej. ruta pública 200) si se mantiene cobertura mínima de arranque.  
- Alinear `phpunit.xml` y estructura de `tests/`.

---

## Fase 1 — Autenticación, roles y acceso (RF-01)

- Login (operadores), sesión, políticas de acceso a rutas de administración.
- Diferenciar flujos administrador / operador según el modelo de `User` (columna `role` o permisos).
- Sanctum listo para API; sin lógica de negocio en controladores de auth.

**Pruebas mínimas (en paralelo):**  
- **Feature:** acceso a ruta protegida sin sesión → redirección; con usuario válido → 200.  
- **Feature:** credenciales inválidas → rechazo.  
- **Unit (opc):** mapeo de enum o helper de roles si existen.  
- **API (si aplica):** token o flujo básico Sanctum.

---

## Fase 2 — Padrón, tarifas y búsqueda (RF-02, RF-03, base RF-11)

- CRUD o gestión de padrón, tarifas vigentes, asignación de tarifa a vecino.
- Búsqueda por código, nombre, filtro de estado; paginación.
- Nada de cálculo de deuda falso en la vista: preparar servicios o queries preparadas.

**Pruebas mínimas (en paralelo):**  
- **Feature:** listado, filtro, creación/actualización con FormRequest.  
- **Unit:** reglas o helpers de búsqueda/unicidad (código padrón) si hay clase dedicada.  
- **Integración (si hay FK complejas):** integridad padrón–tarifa.

---

## Fase 3 — Cálculo de cobro y registro de pago (RF-04, RF-05, RF-06)

- `CobroService` / `PagoService`: cálculo conforme a tarifas, deudas, multas y periodo.
- Transacción en registro de pago; eventos; auditoría en operaciones sensibles.
- Actualización de estados o pendientes según el modelo (sin inventar columnas: revisar migraciones).

**Pruebas mínimas (en paralelo):**  
- **Unit:** múltiples casos de cálculo (sin multa, con multa, con deuda).  
- **Feature:** flujo completo de registro de pago (HTTP) con aserción en BD.  
- **Integración:** transacción: fallo a mitad y rollback donde corresponda.

---

## Fase 4 — Comprobante PDF, QR y entrega (RF-07, RF-08)

- Generación de PDF (DomPDF), almacenamiento en disco **privado**, numeración/serie, QR en documento o metadato según spec.
- Job si la generación es costosa; descarga autorizada (policy).

**Pruebas mínimas (en paralelo):**  
- **Unit (opc):** generación de datos para plantilla, checksum de plantilla.  
- **Feature:** endpoint de descarga con policy (403 sin permiso, 200 con permiso).  
- **Integración:** que el `pdf_path` o equivalente se persista y el archivo exista en disco de test (`Storage::fake`).

---

## Fase 5 — Multas y egresos (RF-11, RF-12)

- Catálogo de multas, aplicación a padrón, reflejo en cálculo de cobro o historial.
- Egresos con auditoría; vínculo a reporte o periodo según requerimientos y esquema.

**Pruebas mínimas (en paralelo):**  
- **Feature:** creación y aplicación de multa, impacto en un cobro simulado.  
- **Feature:** egresos y permisos.  
- **Unit:** totales o validaciones de montos.

---

## Fase 6 — Reporte mensual, cierre y validación (RF-09, RF-10)

- `ReporteMensualService`: cierre idempotente por periodo, sin duplicar cierres, PDF consolidado, estados (borrador, presentado, aprobado) según migración.
- Comando programado o job de cierre; permisos de aprobación.

**Pruebas mínimas (en paralelo):**  
- **Unit:** cálculo de totales a partir de fixtures.  
- **Integración:** doble cierre del mismo periodo → rechazo o no duplicado.  
- **Feature:** flujo de generación o aprobación con usuario admin.

---

## Fase 7 — Portal y API (portal vecino, integraciones)

- Vistas o SPA mínima según elección; **API REST** con **Resources** y **Sanctum** para deuda, comprobantes, historial (según alcance).
- Rate limiting y políticas: un vecino no accede a datos ajenos.

**Pruebas mínimas (en paralelo):**  
- **Feature API:** autenticación, `me`, deuda, listado de pagos con `actingAs` + token.  
- **Feature web portal:** acceso a rutas portal.

---

## Fase 8 — Observabilidad, rendimiento, endurecimiento

- Índices y optimización de consultas acorde a volumen; colas Redis en producción si aplica.
- Monitoreo de errores, logs estructurados, backups documentados.
- **Pruebas de rendimiento** puntuales (consultas a padrón, cierre) en entorno aislado.

**Pruebas mínimas (en paralelo):**  
- **Performance / carga (opc.):** escenarios con datasets grandes en CI nocturna o manual.  
- Revisar que no queden módulos críticos sin test asociado.

---

## Módulos RF ↔ fases (referencia rápida)

| RF   | Fases principales |
|------|---------------------|
| RF-01| 1, 7 |
| RF-02, RF-03 | 2 |
| RF-04, RF-05, RF-06 | 3 |
| RF-07, RF-08 | 4 |
| RF-11 (parte tarifas) | 2 |
| RF-11 (multas) | 5 |
| RF-12| 5 |
| RF-09, RF-10 | 6 |
| API / portal | 7 |

---

## Regla transversal

**Ninguna fase con lógica de negocio se da por “terminada” sin las pruebas mínimas de su columna** y sin revisar impacto en auditoría o datos sensibles. Ver `06-testing-y-calidad.md`.
