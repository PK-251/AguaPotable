<?php

namespace Tests\Unit\Services;

use App\Models\Multa;
use App\Services\Agua\MultaService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class MultaServiceTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function paginar_respeta_filtro_activa_y_busqueda(): void
    {
        Multa::query()->create([
            'nombre' => 'Alpha',
            'descripcion' => null,
            'monto' => 1,
            'activa' => true,
        ]);
        Multa::query()->create([
            'nombre' => 'Beta off',
            'descripcion' => null,
            'monto' => 2,
            'activa' => false,
        ]);

        $soloActivas = app(MultaService::class)->paginarCatalogo(['activa' => '1']);
        $this->assertCount(1, $soloActivas->items());
        $this->assertTrue($soloActivas->items()[0]->activa);

        $busqueda = app(MultaService::class)->paginarCatalogo(['q' => 'Beta']);
        $this->assertCount(1, $busqueda->items());
    }
}
