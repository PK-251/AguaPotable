<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class PortalComprobanteController extends Controller
{
    public function index(): View
    {
        return view('portal.comprobantes.index');
    }
}
