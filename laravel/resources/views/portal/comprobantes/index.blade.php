@extends('components.layouts.portal')

@section('title', 'Comprobantes')

@section('content')
    <x-ui.page-header
        title="Mis comprobantes"
        subtitle="Descarga los comprobantes emitidos a tu nombre." />

    <x-ui.data-table>
        <thead>
            <tr>
                <th>N° comprobante</th>
                <th>Fecha de emisión</th>
                <th>Mes correspondiente</th>
                <th class="text-end">Monto</th>
                <th class="text-end" style="width:130px;">Acción</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td colspan="5">
                    <x-ui.empty-state
                        icon="description"
                        title="Sin comprobantes disponibles"
                        message="Aún no se han emitido comprobantes a tu nombre." />
                </td>
            </tr>
        </tbody>
    </x-ui.data-table>
@endsection
