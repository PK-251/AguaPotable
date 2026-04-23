# Modelo de datos del dominio

## Fuente oficial

- **Las migraciones de Laravel** (`laravel/database/migrations/`) definen de forma **autoritativa** tablas, columnas, claves, índices y restricciones.
- Antes de proponer columnas, tablas o relaciones nuevas, **revisar migraciones y modelos existentes**. No inventar nombres de campos en documentación o código que contradigan el esquema real.

## Tablas del dominio (negocio)

| Tabla / entidad lógica | Propósito |
|------------------------|-----------|
| **tarifas** | Valores de la cuota y metadatos de vigencia; base para calcular el componente de cuota del cobro. |
| **padron_usuarios** | Padrón de vecinos: identificación, datos de contacto/ubicación según el esquema, estado del servicio, tarifa asignada. **Tabla de mayor volumen** y foco de búsquedas. |
| **multas** | Catálogo de multas o recargos configurables (concepto, monto, reglas de aplicación según el modelo). |
| **multas_usuario** | Vínculo entre padrón y multas aplicadas; estado y montos a incorporar al cobro o historial. |
| **pagos** | Un registro por operación de cobro: periodo, desglose de montos, estado, relación con operador, referencia a PDF/comprobante. **Entidad transaccional crítica.** |
| **egresos** | Gastos u otros movimientos de salida de la administración, asociados a operador y, si aplica, a periodo o reporte. |
| **reportes_mensuales** | Cierre o consolidado mensual: agregación de ingresos, egresos, balances, estados y trazas de aprobación según el esquema. |
| **activity_logs** | **Auditoría y trazabilidad**: acciones sensibles (pagos, anulaciones, cierres, cambios de tarifa, etc.) con payload coherente para reconstrucción. |

## Tablas de soporte (framework o técnica)

- **users** — Operadores y administradores del sistema (autenticación del panel), con rol o permisos según implementación.
- **cache**, **jobs**, **sessions** (si aplica), **migrations** — Según despliegue y colas.
- **personal_access_tokens** — **Sanctum** para la API (portal, integraciones).
- (Opcional) **failed_jobs** — Si se usan colas y fallos persistidos.
- (Opcional) **notifications** — Si se almacenan notificaciones en base de datos.

## Modelos Eloquent existentes (referencia de código)

- `App\Models\Tarifa`
- `App\Models\Multa` / `App\Models\MultaUsuario` (o nombre de relación alineado al esquema)
- `App\Models\PadronUsuario` → tabla `padron_usuarios`
- `App\Models\Pago`
- `App\Models\Egreso`
- `App\Models\ReporteMensual`
- `App\Models\ActivityLog`
- `App\Models\User`

## Reglas y observaciones

### Dinero e integridad

- Importes: usar **tipos adecuados** en migraciones (`decimal` con precisión definida) y **casts** en modelos. Los cálculos de negocio no deben depender de float para persistir.
- **Claves foráneas** y restricciones deben alinearse con el negocio (p. ej. no eliminar padrón con pagos sin política clara).
- Un **pago** debe ser **rastreable** hasta padrón, operador, periodo y comprobante (PDF path o equivalente en el esquema).

### Volumen poblacional (escala)

- **padron_usuarios** crecerá con la población: diseñar **búsquedas con índices** (código, nombres, estado) según las migraciones y consultas reales. Evitar `LIKE` sin índice en columnas no indexadas a gran escala; perfilar antes de asumir.
- **pagos** tendrá muchas filas: filtrar por **periodo, fecha, usuario** con índices adecuados; paginar listados; no cargar todo el padrón en memoria.
- **Reportes y exports** massivos: preferir **jobs** y almacenamiento en **disco privado** con limpieza de temporales.

### Trazabilidad

- Lo que afecta dinero, cierre o comprobante debe dejar rastro: **pago** persistido, **registro de auditoría** cuando el negocio lo requiera, y referencias a archivos bajo `storage` controlado.
- **Reporte mensual** debe basarse en **datos persistidos** (pagos, egresos, cierres), no en cálculos “a mano” solo en memoria o solo en hoja de cálculo.

### Reglas de negocio de referencia (sujetos a criterio de la junta y al esquema)

- El **monto a cobrar** agrega: componentes de **cuota / periodo**, **deuda o pendientes**, **multas activas** según reglas implementadas en Services (no en Blade).
- La **cuota de referencia** o montos fijos de ejemplo son orientativos hasta que tarifas y migraciones sean la fuente en cada entorno; documentar en código o en seeds según proceda.

## Documentos relacionados

- `04-arquitectura-aplicacion.md` — dónde vive la lógica.
- `07-operacion-y-despliegue.md` — backup, almacenamiento, colas.
- `06-testing-y-calidad.md` — pruebas de integridad y persistencia.
