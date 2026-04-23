<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DeudaController extends Controller
{
    public function show(Request $request): JsonResponse
    {
        // Pendiente: deuda del usuario/padrón autenticado
        return response()->json(['message' => 'Pendiente de implementar'], 501);
    }
}
