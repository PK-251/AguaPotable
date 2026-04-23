@extends('components.layouts.pdf')

@section('title', 'Reporte mensual')

@section('content')
    @php
        $reporte = $reporte ?? (object) [
            'periodo'     => 'MAYO 2024',
            'rango'       => '01/05/2024 al 31/05/2024',
            'generadoEn'  => '05 Jun 2024, 09:41 AM',
            'ingresos'    => [
                ['concepto' => 'Tarifa Doméstica',  'monto' => '4,250.00'],
                ['concepto' => 'Tarifa Comercial',  'monto' => '1,120.00'],
                ['concepto' => 'Multas y Recargos', 'monto' => '340.00'],
                ['concepto' => 'Cuotas de Inscripción', 'monto' => '150.00'],
            ],
            'egresos'     => [
                ['concepto' => 'Mantenimiento de Redes', 'monto' => '1,200.00'],
                ['concepto' => 'Cloro e Insumos Químicos','monto' => '850.00'],
                ['concepto' => 'Gastos Administrativos', 'monto' => '450.00'],
                ['concepto' => 'Pago Personal Operativo','monto' => '1,500.00'],
            ],
            'totalIng'    => '5,860.00',
            'totalEgr'    => '4,000.00',
            'saldo'       => '1,860.00',
            'metricas'    => [
                ['label' => 'Cobranza efectiva',        'valor' => '92%'],
                ['label' => 'Cortes realizados',        'valor' => '15'],
                ['label' => 'Reparaciones mantenimiento','valor' => '4'],
            ],
            'firmantes'   => [
                ['nombre' => 'Carlos Mendoza', 'cargo' => 'Administrador J.A.S.S.'],
                ['nombre' => 'Rosa Gutiérrez', 'cargo' => 'Tesorera J.A.S.S.'],
            ],
        ];
    @endphp

    <table style="width:100%; margin-bottom:10pt;" cellpadding="0" cellspacing="0">
        <tr>
            <td>
                <x-pdf.brand-header subtitle="Junta Administradora de Servicios de Saneamiento" />
            </td>
        </tr>
        <tr>
            <td class="muted" style="text-align:right; font-size:9pt;">
                Generado: {{ $reporte->generadoEn }}
            </td>
        </tr>
    </table>

    <div class="text-center" style="margin-bottom:14pt;">
        <h1 style="font-size:14pt; margin-bottom:2pt;">REPORTE MENSUAL OPERATIVO - {{ $reporte->periodo }}</h1>
        <div class="muted" style="font-size:9pt;">Periodo: {{ $reporte->rango }}</div>
    </div>

    <table class="dual-col" cellpadding="0" cellspacing="0">
        <tr>
            <td>
                <div class="summary-title">▲ Resumen de Ingresos</div>
                <table class="items">
                    <thead>
                        <tr><th>Concepto</th><th class="text-right" style="width:80pt;">Monto (S/)</th></tr>
                    </thead>
                    <tbody>
                        @foreach ($reporte->ingresos as $i)
                            <tr><td>{{ $i['concepto'] }}</td><td class="text-right">{{ $i['monto'] }}</td></tr>
                        @endforeach
                        <tr><td><strong>TOTAL INGRESOS</strong></td><td class="text-right"><strong>{{ $reporte->totalIng }}</strong></td></tr>
                    </tbody>
                </table>
            </td>
            <td>
                <div class="summary-title is-danger">▼ Resumen de Egresos</div>
                <table class="items">
                    <thead>
                        <tr><th>Concepto</th><th class="text-right" style="width:80pt;">Monto (S/)</th></tr>
                    </thead>
                    <tbody>
                        @foreach ($reporte->egresos as $e)
                            <tr><td>{{ $e['concepto'] }}</td><td class="text-right">{{ $e['monto'] }}</td></tr>
                        @endforeach
                        <tr><td><strong>TOTAL EGRESOS</strong></td><td class="text-right"><strong>{{ $reporte->totalEgr }}</strong></td></tr>
                    </tbody>
                </table>
            </td>
        </tr>
    </table>

    <div class="balance-box">
        <table style="width:100%;" cellpadding="0" cellspacing="0">
            <tr>
                <td colspan="2"><strong>Balance del mes</strong></td>
            </tr>
            <tr>
                <td>Ingresos totales</td>
                <td class="text-right">S/ {{ $reporte->totalIng }}</td>
            </tr>
            <tr>
                <td>Egresos totales</td>
                <td class="text-right">S/ {{ $reporte->totalEgr }}</td>
            </tr>
            <tr>
                <td><strong>SALDO A FAVOR</strong></td>
                <td class="text-right"><strong style="color:#0057cd;">S/ {{ $reporte->saldo }}</strong></td>
            </tr>
        </table>
    </div>

    <div class="label" style="margin-top:18pt;">MÉTRICAS OPERATIVAS</div>
    <table class="metric-grid" cellpadding="0" cellspacing="0">
        <tr>
            @foreach ($reporte->metricas as $m)
                <td>
                    <span class="metric-value">{{ $m['valor'] }}</span>
                    <div class="muted">{{ $m['label'] }}</div>
                </td>
            @endforeach
        </tr>
    </table>

    <table style="width:100%; margin-top:32pt;" cellpadding="0" cellspacing="0">
        <tr>
            @foreach ($reporte->firmantes as $f)
                <td style="width:50%; text-align:center;">
                    <div class="signature">
                        <div><strong>{{ $f['nombre'] }}</strong></div>
                        <div class="muted" style="font-size:9pt;">{{ $f['cargo'] }}</div>
                    </div>
                </td>
            @endforeach
        </tr>
    </table>

    <x-pdf.footer text="Este documento es un reporte oficial interno de la Junta Administradora de Servicios de Saneamiento QUILCATA." />
@endsection
