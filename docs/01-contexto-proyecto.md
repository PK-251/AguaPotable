# Contexto del proyecto Agua Potable

## Nombre y organización

- **Nombre del sistema:** Sistema Web de Gestión de Pagos de Agua Potable – **J.A.S.S. QUILCATA**
- **Organización:** J.A.S.S. QUILCATA, Sara-Sara, Lucanas, Ayacucho, Perú

## Alcance y escala

El sistema está pensado **para atender a toda la población bajo el padrón del servicio**, no para un reducido grupo de prueba. La arquitectura, el modelado de datos, las consultas, el almacenamiento de comprobantes y reportes, y la operación (colas, copias, monitoreo) deben prepararse **desde el inicio** para crecer con el padrón, el número de transacciones mensuales y la generación de documentos.

## Problema real (proceso actual)

Hoy el cobro del agua potable se apoya en un proceso **manual y fragmentado**:

- **Talonarios y recibos en papel** (falta de trazabilidad y estandarización).
- **Cuaderno de campo** (registro lento, riesgo de extravío, difícil de auditar).
- **Transcripción mensual a Excel** (duplicidad de trabajo, errores al copiar).

Esto provoca, entre otros riesgos:

1. **Errores de cálculo** al sumar cuotas, deudas, meses y multas.
2. **Búsqueda lenta** de vecinos, historial y deuda.
3. **Pérdida o deterioro de información** (papel, archivos sueltos).
4. **Doble carga** de datos hacia hojas de cálculo.
5. **Verificaciones y cruces manuales** que consumen tiempo.
6. **Comprobantes no estandarizados** y sin un respaldo digital homogéneo (sin QR, sin serie única clara, etc.).

## Objetivo del sistema

**Digitalizar y automatizar** el cobro mensual del servicio de agua potable: registrar pagos en **tiempo real**, **calcular importes** de forma coherente con tarifas, deuda y multas, **generar comprobantes PDF** (incl. **código QR**), gestionar **multas, tarifas y egresos**, y producir **reportes mensuales consolidados** con trazabilidad y base para la rendición de cuentas.

## Dominio funcional (resumen)

- Padrón de usuarios del servicio (vecinos)
- Tarifas y su vigencia
- Multas (catálogo) y multas asignadas por usuario
- Pagos (registro, estados, comprobante)
- Egresos de la junta
- Reportes mensuales (cierre, validación, documentos)
- Comprobantes PDF generados desde el panel interno y entregados por canales acordados (impresión en caja, descarga del operador, etc.)
- Auditoría de acciones relevantes

## Requisitos funcionales (referencia)


| Código | Nombre                                                  |
| ------ | ------------------------------------------------------- |
| RF-01  | Autenticación y control de acceso                       |
| RF-02  | Carga / visualización de usuarios con deuda             |
| RF-03  | Búsqueda digital de usuario (padrón)                    |
| RF-04  | Cálculo automático del monto a cobrar                   |
| RF-05  | Registro digital del pago                               |
| RF-06  | Registro y seguimiento de pendientes (deuda / periodos) |
| RF-07  | Generación de comprobante PDF (numeración, QR)          |
| RF-08  | Entrega / descarga del comprobante al usuario           |
| RF-09  | Generación de reporte mensual automático                |
| RF-10  | Validación del reporte y apoyo a rendición de cuentas   |
| RF-11  | Gestión de multas y tarifas                             |
| RF-12  | Gestión de egresos                                      |


## Actores

- **Administrador JASS** — configuración, usuarios del sistema, cierres sensibles, validación de reportes (según política).
- **Operador / cajero JASS** — cobro en ventanilla, búsqueda, registro de pagos, emisión o reimpresión controlada.
- **Usuario del servicio (vecino)** — receptor del servicio; recibe sus comprobantes a través del operador en caja o por los canales acordados con la junta. **No** accede al sistema (no existe portal de vecinos).
- **Sistema automático** — colas, cierres programados, generación de PDFs pesados, purga de temporales, notificaciones.

## Estado actual del proyecto (referencia a código)

A fecha de la documentación en este repositorio, el producto se apoya en:

- **Laravel 12** con estructura por capas (Services, FormRequests, Policies, etc.) alineada al dominio.
- **Migraciones** como fuente oficial del esquema; **modelos** de dominio existentes: `User`, `PadronUsuario`, `Tarifa`, `Multa`, `MultaUsuario`, `Pago`, `Egreso`, `ReporteMensual`, `ActivityLog`.
- **Blade + Bootstrap 5.3** y **Vite**; **Sanctum** y **DomPDF** en el stack previsto.
- **Rutas, vistas, reglas de negocio y pruebas** se completan módulo a módulo; **cada módulo con lógica debe implementarse en paralelo con sus pruebas** (ver `06-testing-y-calidad.md` y `05-roadmap-modulos.md`).

Trabajo pendiente de producto (no exhaustivo): completar autenticación y autorización finas, lógica de negocio en servicios, jobs para cargas/PDF, y batería de pruebas por módulo.

## Principios rectoras para el desarrollo

1. Tratar el sistema como una **plataforma real** en uso, no un ejercicio.
2. Favorecer **mantenibilidad, claridad, separación de responsabilidades** y **trazabilidad**.
3. Alinear toda funcionalidad nueva con el **flujo real de cobro** y la organización **J.A.S.S. QUILCATA**.
4. **No** posponer las pruebas a una “fase final”: van **en paralelo** a cada módulo con lógica.

Documentación complementaria: `02-stack-y-reglas-tecnicas.md`, `04-arquitectura-aplicacion.md`, `06-testing-y-calidad.md`.