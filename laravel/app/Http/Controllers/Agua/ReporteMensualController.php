<?php

namespace App\Http\Controllers\Agua;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class ReporteMensualController extends Controller
{
    public function index(): View
    {
        return view('agua.reportes-mensuales.index');
    }
}
