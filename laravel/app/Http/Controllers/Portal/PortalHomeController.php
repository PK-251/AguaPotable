<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class PortalHomeController extends Controller
{
    public function __invoke(): View
    {
        return view('portal.home');
    }
}
