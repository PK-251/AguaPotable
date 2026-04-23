# Mockups del proyecto AguaPotable

Esta carpeta contiene los mockups y exportaciones visuales del proyecto **Sistema Web de Gestión de Pagos de Agua Potable – J.A.S.S. QUILCATA**.

## Propósito

Los archivos ubicados en `docs/mockups/` son la **referencia visual oficial** del proyecto para:

- estructura de pantallas
- jerarquía visual
- distribución de formularios
- tablas, cards, botones y navegación
- consistencia del entorno gráfico
- base de reutilización de estilos y componentes

## Regla de uso

El contenido exportado desde herramientas de diseño como Stitch **no debe copiarse directamente a producción**.

Debe usarse como:

- referencia visual
- referencia estructural
- fuente de componentes reutilizables
- guía para construir vistas limpias y organizadas

## Objetivo técnico

Todo mockup o export dentro de esta carpeta debe ser transformado a una implementación compatible con:

- Laravel 12
- Blade
- Bootstrap 5.3
- Vite

## Criterios de implementación

Al tomar estos mockups como referencia, se debe:

1. identificar layouts base reutilizables
2. identificar componentes UI repetidos
3. evitar duplicación de HTML y estilos
4. corregir código generado desordenado o redundante
5. adaptar el diseño a vistas Blade limpias
6. mantener consistencia visual entre módulos
7. priorizar mantenibilidad y claridad

## Regla de frontend del proyecto

- El frontend oficial del proyecto es **Blade + Bootstrap 5.3**
- **No usar Tailwind como solución principal**
- Los mockups deben reinterpretarse dentro de la arquitectura del sistema y no al revés

## Módulos que pueden existir en esta carpeta

- Login
- Dashboard
- Padrón de usuarios
- Cobros
- Tarifas
- Multas
- Egresos
- Reportes mensuales
- Portal del usuario
- Comprobantes
- Auditoría
- Gestión de usuarios internos

## Resultado esperado

A partir de estos mockups, el sistema debe construir:

- layouts compartidos
- componentes Blade reutilizables
- vistas por módulo
- estilos organizados
- frontend limpio, coherente y listo para conectarse con la lógica del backend

## Nota importante

Si dentro de esta carpeta existe código exportado por Stitch u otra herramienta, ese código debe tratarse como **material de referencia**, no como implementación final obligatoria.