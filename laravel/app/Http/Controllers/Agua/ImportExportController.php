<?php

namespace App\Http\Controllers\Agua;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class ImportExportController extends Controller
{
    public function padron(): View
    {
        return view('agua.import-export.padron');
    }
}
