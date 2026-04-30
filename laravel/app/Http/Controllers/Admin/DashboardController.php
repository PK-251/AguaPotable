<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\Agua\DashboardService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(Request $request, DashboardService $dashboard): View
    {
        $resumen = $dashboard->construirResumen($request->user());

        return view('admin.dashboard', [
            'resumen' => $resumen,
        ]);
    }
}
