@extends('components.layouts.portal')

@section('title', 'Historial de pagos')

@section('content')
    <x-ui.page-header
        title="Historial de pagos"
        subtitle="Consulta el detalle completo de los pagos realizados." />

    <x-ui.data-table>
        <thead>
            <tr>
                <th>Fecha</th>
                <th>Mes / concepto</th>
                <th>Recibo</th>
                <th class="text-end">Monto</th>
                <th class="text-end" style="width:130px;">Comprobante</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td colspan="5">
                    <x-ui.empty-state
                        icon="history"
                        title="Sin pagos registrados"
                        message="Cuando se registren pagos aparecerán aquí." />
                </td>
            </tr>
        </tbody>
    </x-ui.data-table>
@endsection
