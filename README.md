# Sistema Web de Gestión de Pagos de Agua Potable — J.A.S.S. QUILCATA

## Estructura del repositorio

| Ruta        | Contenido |
|------------|------------|
| `laravel/` | Aplicación **Laravel 12** (panel administrativo interno, Vite) |
| `docs/`    | Documentación de producto y operación |
| `.cursor/` | Reglas y contexto para el asistente (opcional) |
| `docker/`  | Plantillas de contenedores (Nginx, PHP, MariaDB) — referencia |
| `scripts/` | Backups, import/export, utilidades (a completar) |
| `Database/`| **Histórico / referencia**; el esquema oficial son las **migraciones** en `laravel/database/migrations` |

## Inicio rápido (desarrollo)

1. `cd laravel`
2. `composer install`
3. Copiar `.env` y `php artisan key:generate`
4. `php artisan migrate`
5. `npm install` y `npm run dev` (Vite) en paralelo a `php artisan serve`

## Stack

- Laravel 12, PHP 8.2+, MariaDB/MySQL, Blade, **Bootstrap 5.3**, Vite, Sanctum, DomPDF.

Más detalle: `docs/02-stack-y-reglas-tecnicas.md` y `docs/07-operacion-y-despliegue.md`.
