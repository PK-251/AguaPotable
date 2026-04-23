<?php

namespace App\Http\Controllers\Agua;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class PadronController extends Controller
{
    public function index(): View
    {
        return view('agua.padron.index');
    }
}
