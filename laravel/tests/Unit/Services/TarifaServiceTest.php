<?php

namespace Tests\Unit\Services;

use App\Models\Tarifa;
use App\Services\Agua\TarifaService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class TarifaServiceTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function paginar_filtra_por_termino(): void
    {
        Tarifa::query()->create([
            'nombre' => 'Doméstica',
            'monto' => 1,
            'descripcion' => null,
            'vigente_desde' => '2026-01-01',
        ]);

        Tarifa::query()->create([
            'nombre' => 'Comercial',
            'monto' => 2,
            'descripcion' => null,
            'vigente_desde' => '2026-01-01',
        ]);

        $pagina = app(TarifaService::class)->paginar(['q' => 'Comer']);

        $this->assertCount(1, $pagina->items());
        $this->assertSame('Comercial', $pagina->items()[0]->nombre);
    }
}
