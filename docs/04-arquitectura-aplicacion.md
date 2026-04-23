# Arquitectura de la aplicación

## Visión

La aplicación es un **sistema de información transaccional** para el dominio de cobro de agua potable en J.A.S.S. QUILCATA: debe escalar con el padrón, soportar **auditoría** y **cambios de reglas** sin reescribir toda la base. La arquitectura se organiza en **capas** con **responsabilidades claras**; la lógica difícil **no** vive en controladores ni en plantillas.

## Mapa de capas

### 1. HTTP — Controladores (delgados)

**Responsabilidad:** Aceptar la petición HTTP, autorizar a alto nivel, delegar a servicios, devolver **vista Blade** o **JSON** (API).

**Debe contener:** Orquestación mínima, inyección de servicios, respuesta HTTP, redirects.

**No debe contener:** Reglas de cálculo de cobros, generación de PDF, cierres contables, SQL complejo repetido, ni reglas de negocio de varios pasos (eso va a Services).

### 2. FormRequest

**Responsabilidad:** **Validación y autorización por petición** (reglas, mensajes, `authorize()` acorde al usuario).

**No debe** duplicar toda la regla de negocio: solo entradas y precondiciones; el Service valida invariantes y consistencia con el estado de la base.

### 3. Services (lógica de negocio)

**Responsabilidad:** Casos de uso del dominio: **cálculo de cobro**, **registro de pago** (con transacción), **series de comprobante**, **generación/ubicación de PDF**, **cierre o generación de reporte mensual**, **importación de padrón**, **exportaciones**, **auditoría estructurada**.

Para operaciones críticas: **`DB::transaction`**, reintentos o idempotencia donde aplique, y emisión de **Events** si otro módulo debe reaccionar (notificaciones, registro de auditoría).

### 4. Repositories (opcional, selectivo)

**Responsabilidad:** Encapsular **consultas pesadas**, listados con filtros, o acceso a datos reutilizable que no aporta semántica al modelo.

**Usar cuando:** Volumen alto, agregaciones, exportaciones por lotes, o la misma consulta se repite en varios servicios.

**No usar** como anemic CRUD de todo: Eloquent y query builder ya resuelven muchos casos.

### 5. Policies (y gates)

**Responsabilidad:** **Quién** puede anular pago, generar cierre, editar tarifa, acceder a comprobante de un vecino, etc.

Alinear con roles **administrador / operador** y, si aplica, reglas del portal y API.

### 6. Modelos Eloquent

**Responsabilidad:** Relaciones, **casts** (incl. decimales), **scopes** reutilizables, mutadores puntuales, constantes/enum vinculados al esquema.

**No** convertir al modelo en un “Dios” con cientos de líneas: la orquestación compleja vive en Services.

### 7. Vistas Blade + Bootstrap 5.3

**Responsabilidad:** Presentación, formularios, estados, mensajes de error de validación, tablas paged.

**No:** Cálculo de importes, decisiones de negocio, ni acceso directo a reglas de cobro. Solo mostrar lo que el controlador/servicio prepara (DTO, ViewModel o primitivos).

### 8. API Resources (JSON)

**Responsabilidad:** **Forma estable** de la respuesta REST para el portal o integraciones: solo campos necesarios, fechas e importes con formato claro, sin filtrar datos sensibles.

### 9. Jobs y colas

**Responsabilidad:** Tareas **lentas o voluminosas**: PDF masivos, importaciones, regeneración de reportes, reenvío de comprobantes. Deben ser **reintentables** y loguear fallos.

### 10. Events + Listeners

**Responsabilidad:** Efectos colaterales: **auditoría** tras pago, **notificaciones**, métricas. El núcleo del pago queda en el Service; el listener no debe reintentar toda la transacción sin diseño (considerar colas, idempotencia).

### 11. Almacenamiento

**Responsabilidad:** Comprobantes, reportes generados, exports e imports validados bajo **discos privados** (`config/filesystems` + directorios bajo `storage/app/private/...` según el proyecto). No servir archivos con URLs públicas fijas a archivos con datos personales o financieros.

## Servicios orientativos (nombres alineados al repo)

- Cobro / pagos: `CobroService`, `PagoService`, `ComprobanteService` (o equivalentes)
- Comprobante y series: `SerieComprobanteService`, `PlantillaComprobanteRenderer` o capa PDF
- Reportes: `ReporteMensualService`
- Padrón / multas: `PadronService`, `MultaService`, `TarifaService`, `EgresoService`
- Integridad y trazas: `AuditoriaService` (escritura en `ActivityLog` o evento homologable)

Ajustar nombres a la implementación real sin duplicar responsabilidades.

## Pruebas: parte del diseño, no un apéndice

**Cada módulo o feature que introduzca o modifique lógica de negocio debe entregarse con las pruebas mínimas acordes** (unitarias, feature, integración) en el **mismo ciclo de implementación** — no al cierre del proyecto. Ver `05-roadmap-modulos.md` (pruebas por fase) y `06-testing-y-calidad.md` (tipos y criterio).

## Referencias

- `02-stack-y-reglas-tecnicas.md`
- `03-modelo-datos.md`
- `.cursor/rules/02-reglas-backend-laravel.mdc`, `05-reglas-testing.mdc`
