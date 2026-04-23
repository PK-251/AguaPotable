<?php

namespace App\Http\Controllers\Agua;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class PagoController extends Controller
{
    public function index(): View
    {
        return view('agua.cobros.index');
    }
}
