<?php

namespace App\Http\Controllers\Agua;

use App\Http\Controllers\Controller;
use App\Http\Requests\Agua\StoreTarifaRequest;
use App\Http\Requests\Agua\UpdateTarifaRequest;
use App\Models\Tarifa;
use App\Services\Agua\TarifaService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TarifaController extends Controller
{
    public function __construct(
        private readonly TarifaService $tarifas,
    ) {
    }

    public function index(Request $request): View
    {
        $filtros = ['q' => $request->string('q')->toString()];
        $listado = $this->tarifas->paginar($filtros);

        return view('agua.tarifas.index', [
            'tarifas' => $listado,
            'filtros' => $filtros,
        ]);
    }

    public function show(Tarifa $tarifa): View
    {
        $tarifa->loadCount('padronUsuarios');

        return view('agua.tarifas.show', ['tarifa' => $tarifa]);
    }

    public function create(): View
    {
        return view('agua.tarifas.create');
    }

    public function store(StoreTarifaRequest $request): RedirectResponse
    {
        $tarifa = $this->tarifas->crear($request->validated());

        return redirect()
            ->route('agua.tarifas.show', $tarifa)
            ->with('status', 'Tarifa registrada correctamente.');
    }

    public function edit(Tarifa $tarifa): View
    {
        return view('agua.tarifas.edit', ['tarifa' => $tarifa]);
    }

    public function update(UpdateTarifaRequest $request, Tarifa $tarifa): RedirectResponse
    {
        $this->tarifas->actualizar($tarifa, $request->validated());

        return redirect()
            ->route('agua.tarifas.show', $tarifa)
            ->with('status', 'Tarifa actualizada correctamente.');
    }
}
