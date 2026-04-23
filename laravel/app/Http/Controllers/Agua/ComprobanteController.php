<?php

namespace App\Http\Controllers\Agua;

use App\Http\Controllers\Controller;
use Illuminate\Http\Response;

class ComprobanteController extends Controller
{
    public function show(int $pagoId): Response
    {
        // Pendiente: autorización y emisión PDF/stream
        return response('Pendiente: comprobante', 200)->header('Content-Type', 'text/plain');
    }
}
