<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PagoController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        // Pendiente: listado con paginación
        return response()->json(['message' => 'Pendiente de implementar'], 501);
    }
}
