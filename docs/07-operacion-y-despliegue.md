# Operación y despliegue — Agua Potable (J.A.S.S. QUILCATA)

## Alcance

Este documento orienta el **despliegue y operación** cuando el **padrón, el tráfico y los almacenamientos** crecen. No sustituye runbooks concretos del servidor, pero fija **principios** alineados al dominio: privacidad de archivos, continuidad, trazas y crecimiento.

## Almacenamiento (privado por defecto)

- **Comprobantes PDF, reportes generados, exportaciones con datos personales, archivos aceptados tras validación** deben resguardarse en **rutas bajo almacenamiento no público** (p. ej. `storage/app/private/...` y discos definidos en `config/filesystems.php`).
- **No** publicar comprobantes con URL directa fija a archivos con datos sensibles. La entrega pasa por **rutas con autenticación, autorización (policy)** y, si aplica, registro en auditoría.
- **Temporales** (subidas en curso, fragmentos, PDFs en generación) viven bajo `storage/app/temp/...` con **retención** y purga (comando o job) para no llenar disco.
- En producción, valorar **S3 o equivalente** solo si hay política de respaldo; la app debe abstraer vía discos de Laravel.

## Colas y workers

- Operaciones **pesadas o largas** (importación de padrón, lotes de PDF, regeneración de reportes) **no** deben bloquear el request interactivo: deben ir a **cola** con **Job** reintentable y logging de fallo.
- En **producción** con volumen: `QUEUE_CONNECTION=redis` (o equivalente) y **workers** dedicados; en desarrollo puede usarse `database` o `sync` con criterio.
- **Supervisión** de workers (systemd, Supervisor, orquestador) para **reinicio** ante caída.

## Tareas programadas (scheduler)

- **Cierre o consolidación mensual** de reporte: comando Artisan + entrada en el **schedule** de Laravel (`schedule:work` o cron que ejecute `schedule:run`).
- **Purge de temporales** y, si aplica, rotación de logs locales.
- Verificar en despliegue la **zona horaria** (`APP_TIMEZONE`) y la **hora de ventana** de cierres (evitar cierre a mitad de jornada de cobro salvo criterio de negocio).

## Copias de seguridad (backups)

- **Base de datos:** volcados periódicos (p. ej. `mysqldump`), almacenamiento **externo** al servidor principal, cifrado en tránsito y en reposo según posibilidad.
- **Almacenamiento de archivos privados:** copia o snapshot de `storage` relevante, coherente con el volumen; verificar **restauración** (ensayo) al menos semestralmente.
- **No** versionar credenciales, `.env` ni claves de API en el repositorio; usar secretos en el orquestador o vault del equipo.
- **Retención y RGPD/LOPD** locales: alinear con la junta (tiempo de conservación de comprobantes y logs).

## Crecimiento operativo (escala poblacional)

- **Base de datos:** revisar **índices** acorde a búsquedas reales; monitorear consultas lentas; evitar N+1 en listados.
- **Aplicación:** OPcache, límites de `memory` y `max_execution_time` adecuados a jobs; `client_max_body_size` en reverse proxy para importaciones.
- **Horizontal:** múltiples workers de cola, réplicas de solo lectura **solo** si el dominio y el presupuesto lo justifican (no es requisito inicial).

## Trazabilidad, logs y monitoreo básico

- **Laravel / PHP:** `storage/logs` con rotación; en producción, **enviar errores** a un servicio (Sentry, Flare, u otro) acordado.
- **Auditoría de negocio:** `ActivityLog` o equivalente; revisar qué se registra en pago, anulación, cierre, cambios de tarifa.
- **Métricas mínimas:** disponibilidad HTTP, tasa 5xx, cola atrasada (jobs pendientes), espacio en disco, uso de CPU en workers.
- **Acceso:** restringir **Telescope** u otras UIs de debug a entornos no productivos o con IP/Auth fuerte.

## Contenedores y referencia

- En el repositorio puede existir carpeta `docker/` con **plantillas** (Nginx, PHP-FPM, MariaDB) para alinear despliegues; ajustar versiones, variables y secretos en el entorno real.

## Resumen

| Área            | Criterio |
|-----------------|----------|
| Archivos        | Discos **privados**, entrega por rutas autenticadas |
| Carga de trabajo| **Jobs** + cola en producción |
| Tiempo          | **Scheduler** para cierres y mantenimiento |
| Datos           | **Backups** de BD y archivos, prueba de restauración |
| Crecimiento     | **Índices, workers, monitoreo** sin adelantar arquitectura innecesaria |
| Trazas          | **Logs** + **auditoría** + alertas básicas |

## Documentos relacionados

- `02-stack-y-reglas-tecnicas.md`
- `03-modelo-datos.md`
- `04-arquitectura-aplicacion.md`
