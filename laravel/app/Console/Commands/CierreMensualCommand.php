<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class CierreMensualCommand extends Command
{
    protected $signature = 'agua:cierre-mensual {periodo? : Periodo AAAA-MM}';

    protected $description = 'Generar o validar cierre de reporte mensual (esqueleto)';

    public function handle(): int
    {
        $this->info('Pendiente: delegar a ReporteMensualService / GenerarReporteMensualAction.');

        return self::SUCCESS;
    }
}
