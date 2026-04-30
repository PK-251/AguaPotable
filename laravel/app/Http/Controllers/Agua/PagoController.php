<?php

namespace App\Http\Controllers\Agua;

use App\Http\Controllers\Controller;
use App\Http\Requests\Agua\StoreCobroRequest;
use App\Models\PadronUsuario;
use App\Services\Agua\CobroService;
use App\Services\Agua\PagoService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class PagoController extends Controller
{
    public function __construct(
        private readonly CobroService $cobros,
        private readonly PagoService $pagos,
    ) {
    }

    public function index(Request $request): View
    {
        $busqueda = trim($request->string('q')->toString());
        $padronId = (int) $request->query('padron', 0);
        $periodoEntrada = $request->input('periodo');
        $periodo = is_string($periodoEntrada) && preg_match('/^\d{4}-\d{2}$/', $periodoEntrada)
            ? $periodoEntrada
            : now()->format('Y-m');

        $residentesCoincidencias = collect();
        if ($padronId === 0 && $busqueda !== '') {
            $residentesCoincidencias = $this->cobros->buscarResidentes($busqueda);
        }

        $padron = null;
        $resumen = null;
        $padronSolicitadoNoEncontrado = false;
        if ($padronId > 0) {
            $padron = PadronUsuario::query()->find($padronId);
            if ($padron === null) {
                $padronSolicitadoNoEncontrado = true;
            } else {
                $resumen = $this->cobros->construirResumen($padron, $periodo);
            }
        }

        $ultimosPagos = $this->cobros->ultimosPagosRegistrados(15);

        return view('agua.cobros.index', [
            'busqueda' => $busqueda,
            'padron' => $padron,
            'resumen' => $resumen,
            'periodo' => $periodo,
            'residentesCoincidencias' => $residentesCoincidencias,
            'ultimosPagos' => $ultimosPagos,
            'padronSolicitadoNoEncontrado' => $padronSolicitadoNoEncontrado,
        ]);
    }

    public function store(StoreCobroRequest $request): RedirectResponse
    {
        $datos = $request->validated();

        $padron = PadronUsuario::query()->findOrFail($datos['padron_usuario_id']);

        try {
            $this->pagos->registrarCobro(
                $padron,
                $request->user(),
                $datos['periodo'],
                $datos['estado'],
            );
        } catch (ValidationException $exception) {
            return redirect()
                ->route('agua.cobros.index', [
                    'padron' => $padron->id,
                    'periodo' => $datos['periodo'],
                    'q' => $request->query('q'),
                ])
                ->withErrors($exception->errors())
                ->withInput();
        }

        $mensaje = $datos['estado'] === 'pagado'
            ? 'Cobro registrado como pagado y liquidaciones actualizadas.'
            : 'Se generó el cobro pendiente del periodo seleccionado.';

        return redirect()
            ->route('agua.cobros.index', [
                'padron' => $padron->id,
                'periodo' => $datos['periodo'],
            ])
            ->with('status', $mensaje);
    }
}
