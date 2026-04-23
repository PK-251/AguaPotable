<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MeController extends Controller
{
    public function show(Request $request): JsonResponse
    {
        // Pendiente: usuario o vínculo padrón
        return response()->json(['message' => 'Pendiente de implementar'], 501);
    }
}
