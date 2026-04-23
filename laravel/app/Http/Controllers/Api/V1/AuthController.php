<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function login(Request $request): JsonResponse
    {
        // Pendiente: validación y token Sanctum
        return response()->json(['message' => 'Pendiente de implementar'], 501);
    }

    public function logout(Request $request): JsonResponse
    {
        // Pendiente: revocar token actual
        return response()->json(['message' => 'Pendiente de implementar'], 501);
    }
}
