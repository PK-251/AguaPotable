<?php

namespace App\Http\Controllers\Agua;

use App\Http\Controllers\Controller;
use App\Http\Requests\Agua\StoreMultaUsuarioRequest;
use App\Models\Multa;
use App\Models\PadronUsuario;
use App\Services\Agua\MultaService;
use App\Services\Agua\MultaUsuarioService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class MultaUsuarioController extends Controller
{
    public function __construct(
        private readonly MultaUsuarioService $asignaciones,
        private readonly MultaService $catalogoMultas,
    ) {
    }

    public function index(Request $request): View
    {
        $filtros = [
            'q' => $request->string('q')->toString(),
            'pagada' => $request->input('pagada'),
            'multa_id' => $request->input('multa_id'),
            'padron_usuario_id' => $request->input('padron_usuario_id'),
        ];

        $listado = $this->asignaciones->paginarAsignaciones($filtros);
        $catalogoFiltro = Multa::query()->orderBy('nombre')->get(['id', 'nombre']);
        $multasActivas = $this->catalogoMultas->listarActivasOrdenadas();

        $padronPreseleccionado = null;
        if ($request->filled('padron_usuario_id')) {
            $padronPreseleccionado = PadronUsuario::query()->find((int) $request->input('padron_usuario_id'));
        }

        return view('agua.multas-usuario.index', [
            'asignaciones' => $listado,
            'filtros' => $filtros,
            'multasParaFiltro' => $catalogoFiltro,
            'multasActivas' => $multasActivas,
            'padronPreseleccionado' => $padronPreseleccionado,
        ]);
    }

    public function create(Request $request): View
    {
        $catalogo = $this->catalogoMultas->listarActivasOrdenadas();
        $padron = null;
        if ($request->filled('padron_usuario_id')) {
            $padron = PadronUsuario::query()->find((int) $request->input('padron_usuario_id'));
        }

        return view('agua.multas-usuario.create', [
            'multasActivas' => $catalogo,
            'padron' => $padron,
        ]);
    }

    public function store(StoreMultaUsuarioRequest $request): RedirectResponse
    {
        try {
            $this->asignaciones->asignar($request->validated());
        } catch (ValidationException $exception) {
            return redirect()
                ->route('agua.multas-usuario.create', [
                    'padron_usuario_id' => $request->input('padron_usuario_id'),
                ])
                ->withErrors($exception->errors())
                ->withInput();
        }

        return redirect()
            ->route('agua.multas-usuario.index', [
                'padron_usuario_id' => $request->input('padron_usuario_id'),
            ])
            ->with('status', 'Multa aplicada al usuario del padrón.');
    }
}
