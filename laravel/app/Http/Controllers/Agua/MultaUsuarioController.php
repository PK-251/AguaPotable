<?php

namespace App\Http\Controllers\Agua;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class MultaUsuarioController extends Controller
{
    public function index(): View
    {
        return view('agua.multas-usuario.index');
    }
}
