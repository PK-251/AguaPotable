{{--
    Encabezado de marca reutilizable para los PDF (recibo y reporte mensual).

    Props:
      - docTitle (string | null) — título pequeño a la derecha: "RECIBO DE PAGO", "REPORTE".
      - docNumber (string | null) — número o referencia a destacar.
      - subtitle (string | null)  — descripción debajo del nombre de la JASS.
--}}
@props([
    'docTitle'  => null,
    'docNumber' => null,
    'subtitle'  => 'Junta Administradora de Servicios de Saneamiento',
])

<table class="brand-row" cellpadding="0" cellspacing="0" style="width:100%;">
    <tr>
        <td style="width:50pt; vertical-align:middle;">
            <div class="brand-mark">~</div>
        </td>
        <td style="padding-left:10pt; vertical-align:middle;">
            <div class="brand-name">J.A.S.S. QUILCATA</div>
            <div class="muted" style="font-size:9pt;">{{ $subtitle }}</div>
            <div class="muted" style="font-size:9pt;">Sara-Sara, Ayacucho — RUC: 20456789123</div>
        </td>
        @if ($docTitle || $docNumber)
            <td style="text-align:right; vertical-align:middle;">
                @if ($docTitle)
                    <div class="doc-title">{{ $docTitle }}</div>
                @endif
                @if ($docNumber)
                    <div class="doc-number" style="margin-top:4pt;">{{ $docNumber }}</div>
                @endif
            </td>
        @endif
    </tr>
</table>
