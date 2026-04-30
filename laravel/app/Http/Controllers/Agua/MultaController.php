<?php

namespace App\Http\Controllers\Agua;

use App\Http\Controllers\Controller;
use App\Http\Requests\Agua\StoreMultaRequest;
use App\Http\Requests\Agua\UpdateMultaRequest;
use App\Models\Multa;
use App\Services\Agua\MultaService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MultaController extends Controller
{
    public function __construct(
        private readonly MultaService $multas,
    ) {
    }

    public function index(Request $request): View
    {
        $filtros = [
            'q' => $request->string('q')->toString(),
            'activa' => $request->input('activa'),
        ];

        $listado = $this->multas->paginarCatalogo($filtros);

        return view('agua.multas.index', [
            'multas' => $listado,
            'filtros' => $filtros,
        ]);
    }

    public function show(Multa $multa): View
    {
        $multa->loadCount('multasUsuario');

        return view('agua.multas.show', ['multa' => $multa]);
    }

    public function create(): View
    {
        return view('agua.multas.create');
    }

    public function store(StoreMultaRequest $request): RedirectResponse
    {
        $multa = $this->multas->crear($request->validated());

        return redirect()
            ->route('agua.multas.show', $multa)
            ->with('status', 'Multa registrada en el catálogo.');
    }

    public function edit(Multa $multa): View
    {
        return view('agua.multas.edit', ['multa' => $multa]);
    }

    public function update(UpdateMultaRequest $request, Multa $multa): RedirectResponse
    {
        $this->multas->actualizar($multa, $request->validated());

        return redirect()
            ->route('agua.multas.show', $multa)
            ->with('status', 'Multa actualizada correctamente.');
    }
}
