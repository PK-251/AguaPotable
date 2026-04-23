@extends('components.layouts.app')

@section('title', 'Control de egresos')

@section('content')
    @php
        $egresos = $egresos ?? [
            ['fecha' => '15/10/2023', 'cat' => ['Mantenimiento', 'warning'],   'beneficiario' => 'Ferretería El Tornillo',  'descripcion' => 'Compra de tubos PVC y p…', 'comprobante' => 'factura',  'monto' => '450.00'],
            ['fecha' => '12/10/2023', 'cat' => ['Administrativo', 'info'],     'beneficiario' => 'Librería Central',         'descripcion' => 'Suministros de oficina y t…','comprobante' => 'boleta',   'monto' => '120.50'],
            ['fecha' => '05/10/2023', 'cat' => ['Personal', 'muted'],          'beneficiario' => 'Juan Pérez (Operario)',     'descripcion' => 'Pago quincena limpieza d…','comprobante' => 'recibo',   'monto' => '800.00'],
            ['fecha' => '02/10/2023', 'cat' => ['Otros', 'danger'],            'beneficiario' => 'Municipalidad Distrital',   'descripcion' => 'Trámites legales y legaliz…','comprobante' => 'factura',  'monto' => '350.00'],
        ];
    @endphp

    <x-ui.page-header
        title="Control de Egresos"
        subtitle="Gestione y registre los gastos operativos y administrativos.">
        <x-slot name="actions">
            <a href="#" class="btn btn-primary d-inline-flex align-items-center gap-2">
                <x-ui.icon name="add" size="sm" /> Registrar Egreso
            </a>
        </x-slot>
    </x-ui.page-header>

    <div class="row g-3 mb-3">
        <div class="col-12 col-md-4">
            <x-ui.stat-card
                title="Total egresos mes (octubre)"
                value="2,800.00"
                prefix="S/"
                tone="danger"
                help="+12% vs mes anterior" />
        </div>

        <div class="col-12 col-md-8">
            <x-ui.card padded>
                <form class="row g-3 align-items-end">
                    <div class="col-12 col-md-4">
                        <label class="form-label">Desde</label>
                        <input type="date" class="form-control">
                    </div>
                    <div class="col-12 col-md-4">
                        <label class="form-label">Hasta</label>
                        <input type="date" class="form-control">
                    </div>
                    <div class="col-12 col-md-3">
                        <label class="form-label">Categoría</label>
                        <select class="form-select">
                            <option>Todas las categorías</option>
                            <option>Mantenimiento</option>
                            <option>Administrativo</option>
                            <option>Personal</option>
                            <option>Otros</option>
                        </select>
                    </div>
                    <div class="col-12 col-md-1">
                        <button type="submit" class="btn btn-outline-secondary w-100 d-inline-flex align-items-center gap-1 justify-content-center">
                            <x-ui.icon name="tune" size="sm" /> Filtrar
                        </button>
                    </div>
                </form>
            </x-ui.card>
        </div>
    </div>

    <x-ui.data-table>
        <thead>
            <tr>
                <th>Fecha</th>
                <th>Categoría</th>
                <th>Beneficiario</th>
                <th>Descripción</th>
                <th>Comprobante</th>
                <th class="text-end">Monto</th>
                <th class="text-end" style="width:90px;">Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($egresos as $e)
                <tr>
                    <td class="text-body-secondary">{{ $e['fecha'] }}</td>
                    <td><x-ui.status-badge :label="$e['cat'][0]" :tone="$e['cat'][1]" /></td>
                    <td class="fw-semibold">{{ $e['beneficiario'] }}</td>
                    <td class="text-body-secondary">{{ $e['descripcion'] }}</td>
                    <td>
                        <button type="button" class="agua-icon-btn" aria-label="Ver comprobante: {{ $e['comprobante'] }}">
                            <x-ui.icon name="description" size="sm" />
                        </button>
                    </td>
                    <td class="text-end"><x-ui.money :amount="$e['monto']" /></td>
                    <td class="text-end">
                        <div class="agua-actions">
                            <a href="#" class="agua-icon-btn" aria-label="Editar"><x-ui.icon name="edit" size="sm" /></a>
                            <button type="button" class="agua-icon-btn agua-icon-btn--danger" aria-label="Eliminar"><x-ui.icon name="delete" size="sm" /></button>
                        </div>
                    </td>
                </tr>
            @endforeach
        </tbody>

        <x-slot name="footer">
            <div class="text-body-secondary small">Mostrando 1 a 4 de 24 registros</div>
            <nav>
                <ul class="pagination pagination-sm mb-0">
                    <li class="page-item disabled"><span class="page-link">Anterior</span></li>
                    <li class="page-item active"><span class="page-link">1</span></li>
                    <li class="page-item"><a class="page-link" href="#">2</a></li>
                    <li class="page-item"><a class="page-link" href="#">3</a></li>
                    <li class="page-item"><a class="page-link" href="#">Siguiente</a></li>
                </ul>
            </nav>
        </x-slot>
    </x-ui.data-table>
@endsection
