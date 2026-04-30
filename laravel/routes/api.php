<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| El sistema interno de J.A.S.S. QUILCATA no expone, por ahora, endpoints
| públicos de API. Sanctum permanece instalado para integraciones futuras
| con sistemas administrativos o herramientas internas, y este archivo se
| deja como punto de entrada limpio para registrar nuevas rutas cuando se
| diseñen formalmente con su FormRequest, Resource y pruebas mínimas.
|
*/

Route::prefix('v1')->name('api.v1.')->group(function () {
    //
});
