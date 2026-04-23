<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class PortalEstadoCuentaController extends Controller
{
    public function __invoke(): View
    {
        return view('portal.estado-cuenta');
    }
}
