<?php

namespace App\Http\Controllers\Agua;

use App\Http\Controllers\Controller;
use App\Http\Requests\Agua\StorePadronRequest;
use App\Http\Requests\Agua\UpdatePadronRequest;
use App\Models\PadronUsuario;
use App\Services\Agua\PadronService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PadronController extends Controller
{
    public function __construct(
        private readonly PadronService $padron,
    ) {
    }

    public function index(Request $request): View
    {
        $filtros = [
            'q' => $request->string('q')->toString(),
            'estado' => $request->string('estado')->toString(),
            'tarifa_id' => $request->input('tarifa_id'),
        ];

        $residentes = $this->padron->paginar($filtros);
        $tarifas = $this->padron->listarTarifasOrdenadas();

        return view('agua.padron.index', [
            'residentes' => $residentes,
            'tarifas' => $tarifas,
            'filtros' => $filtros,
        ]);
    }

    public function create(): View
    {
        return view('agua.padron.create', [
            'tarifas' => $this->padron->listarTarifasOrdenadas(),
        ]);
    }

    public function store(StorePadronRequest $request): RedirectResponse
    {
        $this->padron->crear($request->validated());

        return redirect()
            ->route('agua.padron.index')
            ->with('status', 'Usuario del padrón registrado correctamente.');
    }

    public function show(PadronUsuario $padron): View
    {
        $padron->load([
            'tarifa:id,nombre,monto,descripcion',
            'pagos' => static fn ($q) => $q->orderByDesc('created_at')->limit(25),
            'pagos.user:id,name',
        ]);

        $deudaPendiente = (string) $padron->pagos()
            ->where('estado', 'pendiente')
            ->sum('monto_total');

        return view('agua.padron.show', [
            'residente' => $padron,
            'deudaPendiente' => $deudaPendiente,
        ]);
    }

    public function edit(PadronUsuario $padron): View
    {
        return view('agua.padron.edit', [
            'residente' => $padron,
            'tarifas' => $this->padron->listarTarifasOrdenadas(),
        ]);
    }

    public function update(UpdatePadronRequest $request, PadronUsuario $padron): RedirectResponse
    {
        $this->padron->actualizar($padron, $request->validated());

        return redirect()
            ->route('agua.padron.show', $padron)
            ->with('status', 'Datos actualizados correctamente.');
    }
}
