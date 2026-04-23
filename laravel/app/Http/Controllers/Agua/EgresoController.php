<?php

namespace App\Http\Controllers\Agua;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class EgresoController extends Controller
{
    public function index(): View
    {
        return view('agua.egresos.index');
    }
}
