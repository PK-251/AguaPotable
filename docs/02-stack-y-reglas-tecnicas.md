# Stack y reglas técnicas del proyecto

## Stack oficial (obligatorio salvo decisión documentada)


| Capa / uso                                        | Tecnología                                                                                                |
| ------------------------------------------------- | --------------------------------------------------------------------------------------------------------- |
| Framework                                         | **Laravel 12**                                                                                            |
| Lenguaje                                          | **PHP 8.2+**                                                                                              |
| Base de datos                                     | **MariaDB / MySQL**                                                                                       |
| UI servidor                                       | **Blade**                                                                                                 |
| UI componentes y layout                           | **Bootstrap 5.3**                                                                                         |
| CSS/JS en frontend                                | Vite; estilos propios mínimos y coherentes con Bootstrap                                                  |
| Autenticación API / tokens                        | **Laravel Sanctum**                                                                                       |
| Comprobantes y reportes PDF                       | **barryvdh/laravel-dompdf (DomPDF)**                                                                      |
| Códigos QR en PDF o enlaces                       | Integración dedicada (paquete o servicio) cuando se implemente; **debe formar parte del diseño** de RF-07 |
| Pruebas backend                                   | **PHPUnit** con convenciones de Laravel                                                                   |
| Pruebas E2E (fase posterior a estabilizar flujos) | A definir (p. ej. Cypress, Playwright); no sustituyen tests automatizados de API/feature                  |
| Carga / estrés puntuales                          | p. ej. k6, JMeter (entornos controlados)                                                                  |
| Control de versiones                              | Git (remoto acorde al equipo)                                                                             |


**Sanctum** y **DomPDF** forman parte del enfoque oficial: **no deben eliminarse** ni sustituirse sin acuerdo y documentación (ADR o nota en este repositorio).

## Frontend: Blade + Bootstrap 5.3

- El **frontend principal** del sistema (panel operador, administración, portal Blade) es **Blade + Bootstrap 5.3**.
- **Tailwind no es la solución principal** de estilos ni de layout. No añadir Tailwind al pipeline por defecto; si existiera rastro histórico, no expandirlo.
- Priorizar **claridad para operador y administrador**: formularios legibles, tablas con buena jerarquía, mensajes de error visibles, accesibilidad razonable en escritorio/tablet (contexto de caja y oficina).

## Fuente de verdad del esquema

- Las **migraciones Laravel** son la **única fuente oficial** del esquema relacional. Cualquier documento, diagrama o `schema.sql` auxiliar no debe desmentir a las migraciones.

## Lógica de negocio y capas

- La **lógica de negocio importante** reside en **Services** (u orquestación explícia equivalente), no en controladores ni en vistas.
- La **validación de entrada** se concentra en **FormRequest** (reglas, autorización básica por request).
- **Operaciones críticas** (pagos, cierres, asignación de multas con efecto en cobro, anulaciones): **transacciones de base de datos**, trazas en **auditoría** (`ActivityLog` u otra vía alineada al modelo) cuando corresponda.

## Mantenibilidad

- Código **claro, cohesivo, con nombres del dominio** (español razonable en clases y métodos de negocio).
- **Evitar duplicar** reglas de cálculo o de negocio en varias capas; un solo lugar de verdad por regla, reutilizable y testeable.
- **Consultas** legibles: Eloquent y scopes; **Repositories** solo cuando la consulta sea compleja, reutilizable o de alto volumen.
- Trabajo con **dinero**: tipos/ casts `decimal` coherentes, sin floats para persistir importes; redondeo explícito donde el negocio lo exija.

## Calidad: pruebas obligatorias en el mismo ciclo que la feature

- **Toda lógica nueva de negocio o persistencia crítica debe ir acompañada de pruebas** (unitarias, feature o integración según el caso). No dejar un “hito de pruebas al final” del proyecto; ver `06-testing-y-calidad.md` y el roadmap.
- Al proponer un **servicio o módulo** nuevo, proponer también el **conjunto mínimo de pruebas** que lo cubra.

## Referencias

- `04-arquitectura-aplicacion.md` — capas y responsabilidades.
- `06-testing-y-calidad.md` — tipos de pruebas y criterio por módulo.
- `.cursor/rules/` — reglas breves para el asistente y el equipo.