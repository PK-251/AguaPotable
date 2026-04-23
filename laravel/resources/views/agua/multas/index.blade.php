@extends('components.layouts.app')

@section('title', 'Catálogo de multas')

@section('content')
    @php
        $multas = $multas ?? [
            ['concepto' => 'Reconexión de Servicio',   'descripcion' => 'Cobro por reconectar el servicio tras corte por mora', 'monto' => 'S/ 50.00',  'unidad' => null,             'estado' => ['ACTIVO',   'info']],
            ['concepto' => 'Falta a Asamblea General', 'descripcion' => 'Multa por inasistencia injustificada a reuniones',      'monto' => 'S/ 30.00',  'unidad' => null,             'estado' => ['ACTIVO',   'info']],
            ['concepto' => 'Mora por Mes Retrasado',   'descripcion' => 'Porcentaje adicional sobre el recibo vencido',          'monto' => '5%',        'unidad' => 'del recibo',     'estado' => ['ACTIVO',   'info']],
            ['concepto' => 'Falta a Faena Comunal',    'descripcion' => 'Inasistencia a trabajos comunitarios programados',      'monto' => 'S/ 40.00',  'unidad' => null,             'estado' => ['INACTIVO', 'muted']],
            ['concepto' => 'Manipulación no autorizada','descripcion' => 'Alteración del medidor o red matriz',                  'monto' => 'S/ 500.00', 'unidad' => null,             'estado' => ['ACTIVO',   'info']],
        ];
    @endphp

    <x-ui.page-header
        title="Catálogo de Multas"
        subtitle="Gestione las tipologías de infracciones y sus respectivas penalidades.">
        <x-slot name="actions">
            <a href="#" class="btn btn-primary d-inline-flex align-items-center gap-2">
                <x-ui.icon name="add" size="sm" /> Definir Multa
            </a>
        </x-slot>
    </x-ui.page-header>

    <x-ui.data-table>
        <x-slot name="toolbar">
            <div class="flex-grow-1" style="max-width:360px;">
                <x-ui.search-input placeholder="Filtrar multas..." name="q" />
            </div>
            <button type="button" class="btn btn-outline-secondary ms-auto d-inline-flex align-items-center gap-2">
                <x-ui.icon name="tune" size="sm" /> Filtros
            </button>
        </x-slot>

        <thead>
            <tr>
                <th>Concepto</th>
                <th>Descripción</th>
                <th>Monto fijo / %</th>
                <th>Estado</th>
                <th class="text-end" style="width:90px;">Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($multas as $m)
                <tr>
                    <td class="fw-semibold">{{ $m['concepto'] }}</td>
                    <td class="text-body-secondary">{{ $m['descripcion'] }}</td>
                    <td>
                        {{ $m['monto'] }}
                        @if ($m['unidad'])
                            <span class="text-body-secondary small">{{ $m['unidad'] }}</span>
                        @endif
                    </td>
                    <td><x-ui.status-badge :label="$m['estado'][0]" :tone="$m['estado'][1]" caps dotless /></td>
                    <td class="text-end">
                        <div class="agua-actions">
                            <a href="#" class="agua-icon-btn" aria-label="Editar">
                                <x-ui.icon name="edit" size="sm" />
                            </a>
                            <button type="button" class="agua-icon-btn agua-icon-btn--danger" aria-label="Eliminar">
                                <x-ui.icon name="delete" size="sm" />
                            </button>
                        </div>
                    </td>
                </tr>
            @endforeach
        </tbody>

        <x-slot name="footer">
            <div class="text-body-secondary small">Mostrando 1 a 5 de 12 registros</div>
            <nav>
                <ul class="pagination pagination-sm mb-0">
                    <li class="page-item disabled"><span class="page-link">&lsaquo;</span></li>
                    <li class="page-item active"><span class="page-link">1</span></li>
                    <li class="page-item"><a class="page-link" href="#">2</a></li>
                    <li class="page-item"><a class="page-link" href="#">3</a></li>
                    <li class="page-item"><a class="page-link" href="#">&rsaquo;</a></li>
                </ul>
            </nav>
        </x-slot>
    </x-ui.data-table>
@endsection
