<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class PruneArchivosTemporalesCommand extends Command
{
    protected $signature = 'agua:prune-temp {--dry-run : Solo listar}';

    protected $description = 'Eliminar archivos antiguos bajo storage/app/temp (esqueleto)';

    public function handle(): int
    {
        $this->info('Pendiente: respetar config(\'agua.retencion.temp_dias\') y disco temp.');

        if ($this->option('dry-run')) {
            $this->comment('Dry-run: sin borrado.');
        }

        if (! is_dir(storage_path('app/temp'))) {
            File::makeDirectory(storage_path('app/temp'), 0755, true);
        }

        return self::SUCCESS;
    }
}
