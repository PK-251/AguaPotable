@extends('components.layouts.pdf')

@section('title', 'Recibo de pago')

@section('content')
    @php
        /** Datos demo; se reemplazan al conectar el backend. */
        $recibo = $recibo ?? (object) [
            'numero'   => '#0123-2024',
            'residente'=> 'Carlos Mendoza Rojas',
            'codigo'   => 'USR-84920',
            'fecha'    => '15 de Noviembre, 2024',
            'mes'      => 'Octubre 2024',
            'items'    => [
                ['item' => '01', 'concepto' => 'Cuota Familiar Mensual - Agua Potable', 'monto' => '20.00'],
                ['item' => '02', 'concepto' => 'Fondo de Mantenimiento de Redes',       'monto' => '5.00'],
            ],
            'total'    => '25.00',
            'firmante' => 'Rosa García Vilca',
            'cargo'    => 'Tesorero / Operador',
            'emitidoEn'=> '15/11/2024 10:45 AM',
        ];
    @endphp

    <x-pdf.brand-header
        docTitle="Recibo de Pago"
        :docNumber="$recibo->numero"
        subtitle="Junta Administradora de Servicios de Saneamiento" />

    <div class="meta-box">
        <table style="width:100%;">
            <tr>
                <td style="width:50%;">
                    <div class="label">Residente</div>
                    <div><strong>{{ $recibo->residente }}</strong></div>
                </td>
                <td style="width:50%;">
                    <div class="label">Código de usuario</div>
                    <div><strong>{{ $recibo->codigo }}</strong></div>
                </td>
            </tr>
            <tr>
                <td>
                    <div class="label" style="margin-top:6pt;">Fecha de emisión</div>
                    <div>{{ $recibo->fecha }}</div>
                </td>
                <td>
                    <div class="label" style="margin-top:6pt;">Mes correspondiente</div>
                    <div>{{ $recibo->mes }}</div>
                </td>
            </tr>
        </table>
    </div>

    <table class="items">
        <thead>
            <tr>
                <th style="width:50pt;">Ítem</th>
                <th>Concepto</th>
                <th class="text-right" style="width:100pt;">Monto (S/)</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($recibo->items as $item)
                <tr>
                    <td>{{ $item['item'] }}</td>
                    <td>{{ $item['concepto'] }}</td>
                    <td class="text-right">{{ $item['monto'] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="total-box">Total pagado: S/ {{ $recibo->total }}</div>
    <div style="clear:both;"></div>

    <table style="width:100%; margin-top:30pt;" cellpadding="0" cellspacing="0">
        <tr>
            <td style="width:55%; vertical-align:top;">
                <table cellpadding="0" cellspacing="0">
                    <tr>
                        <td style="width:60pt;">
                            <div style="width:50pt;height:50pt;border:1px solid #c2c6d8;background:#f7f8fc;text-align:center;line-height:50pt;font-size:8pt;color:#727787;">QR</div>
                        </td>
                        <td style="padding-left:10pt;">
                            <div class="label" style="margin-bottom:4pt;">Verificación digital</div>
                            <div class="muted" style="font-size:9pt;">
                                Escanee el código para validar la autenticidad de este recibo.
                            </div>
                        </td>
                    </tr>
                </table>
            </td>
            <td style="width:45%; vertical-align:bottom; text-align:center;">
                <div class="signature">
                    <div><strong>{{ $recibo->firmante }}</strong></div>
                    <div class="muted" style="font-size:9pt;">{{ $recibo->cargo }}</div>
                </div>
            </td>
        </tr>
    </table>

    <x-pdf.footer :text="'Impreso el '.$recibo->emitidoEn.' · Documento válido como comprobante de pago'" />
@endsection
