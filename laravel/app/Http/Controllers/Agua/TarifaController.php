<?php

namespace App\Http\Controllers\Agua;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class TarifaController extends Controller
{
    public function index(): View
    {
        return view('agua.tarifas.index');
    }
}
