@extends('components.layouts.app')

@section('title', 'Multas aplicadas')

@section('content')
    <x-ui.page-header
        title="Multas aplicadas al padrón"
        subtitle="Consulte las multas vigentes por residente y su estado de pago.">
        <x-slot name="actions">
            <a href="#" class="btn btn-primary d-inline-flex align-items-center gap-2">
                <x-ui.icon name="add" size="sm" /> Aplicar Multa
            </a>
        </x-slot>
    </x-ui.page-header>

    <x-ui.filter-bar>
        <x-ui.search-input label="Buscar" name="q" placeholder="Residente o recibo..." />

        <div>
            <label class="form-label">Estado</label>
            <select class="form-select" name="estado">
                <option>Todos</option>
                <option>Pendiente</option>
                <option>Pagada</option>
                <option>Condonada</option>
            </select>
        </div>

        <div>
            <label class="form-label">Concepto</label>
            <select class="form-select" name="concepto">
                <option>Todos</option>
                <option>Mora por retraso</option>
                <option>Reconexión</option>
                <option>Falta a asamblea</option>
            </select>
        </div>
    </x-ui.filter-bar>

    <x-ui.data-table>
        <thead>
            <tr>
                <th>Residente</th>
                <th>Concepto</th>
                <th>Fecha</th>
                <th class="text-end">Monto</th>
                <th>Estado</th>
                <th class="text-end" style="width:90px;">Acciones</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td colspan="6">
                    <x-ui.empty-state
                        icon="warning"
                        title="Sin multas aplicadas"
                        message="Aún no hay multas registradas para el periodo filtrado." />
                </td>
            </tr>
        </tbody>
    </x-ui.data-table>
@endsection
