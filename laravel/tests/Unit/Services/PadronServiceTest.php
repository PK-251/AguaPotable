<?php

namespace Tests\Unit\Services;

use App\Models\PadronUsuario;
use App\Models\Tarifa;
use App\Services\Agua\PadronService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class PadronServiceTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function pagina_resultados_y_aplica_busqueda(): void
    {
        $tarifa = Tarifa::query()->create([
            'nombre' => 'Doméstica',
            'monto' => 10,
            'descripcion' => null,
            'vigente_desde' => '2026-01-01',
        ]);

        PadronUsuario::query()->create([
            'codigo' => 'X-001',
            'nombre' => 'soloX',
            'apellido' => 'Test',
            'direccion' => 'Dir',
            'estado' => 'activo',
            'tarifa_id' => $tarifa->id,
        ]);

        PadronUsuario::query()->create([
            'codigo' => 'Y-002',
            'nombre' => 'otro',
            'apellido' => 'Test',
            'direccion' => 'Dir',
            'estado' => 'cortado',
            'tarifa_id' => $tarifa->id,
        ]);

        $servicio = app(PadronService::class);
        $pagina = $servicio->paginar(['q' => 'soloX']);

        $this->assertCount(1, $pagina->items());
        $this->assertSame('X-001', $pagina->items()[0]->codigo);
    }
}
