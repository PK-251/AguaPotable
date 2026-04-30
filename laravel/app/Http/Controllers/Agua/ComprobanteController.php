<?php

namespace App\Http\Controllers\Agua;

use App\Http\Controllers\Controller;
use App\Models\Pago;
use Illuminate\Http\Response;

class ComprobanteController extends Controller
{
    public function show(Pago $pago): Response
    {
        // Pendiente: autorización por policy y emisión PDF/stream (DomPDF).
        return response('Pendiente: comprobante PDF', 200)->header('Content-Type', 'text/plain; charset=UTF-8');
    }
}
