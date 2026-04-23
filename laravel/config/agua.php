<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Prefijos y series (negocio)
    |--------------------------------------------------------------------------
    | Definir en implementación real alineado a comprobantes y series en BD.
    */
    'comprobante_serie' => [
        'prefijo' => env('AGUA_COMPROBANTE_PREFIJO', 'JASS'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Importaciones
    |--------------------------------------------------------------------------
    */
    'import' => [
        'max_size_kb' => (int) env('AGUA_IMPORT_MAX_KB', 5120),
    ],

    /*
    |--------------------------------------------------------------------------
    | Retención (días) — temporales; negocio final en servicios
    |--------------------------------------------------------------------------
    */
    'retencion' => [
        'temp_dias' => (int) env('AGUA_TEMP_RETENCION_DIAS', 7),
    ],

    /*
    |--------------------------------------------------------------------------
    | Rutas de almacenamiento bajo storage/app (referencia; discos en filesystems)
    |--------------------------------------------------------------------------
    */
    'storage' => [
        'comprobantes' => 'private/comprobantes',
        'reportes_mensuales' => 'private/reportes-mensuales',
        'exports' => 'private/exports',
        'imports' => 'private/imports',
        'qrcodes' => 'private/qrcodes',
        'temp' => 'temp',
    ],
];
