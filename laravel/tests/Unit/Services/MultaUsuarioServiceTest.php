<?php

namespace Tests\Unit\Services;

use App\Models\Multa;
use App\Models\PadronUsuario;
use App\Models\Tarifa;
use App\Services\Agua\MultaUsuarioService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Validation\ValidationException;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class MultaUsuarioServiceTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function asignar_persiste_fila(): void
    {
        $tarifa = Tarifa::query()->create([
            'nombre' => 'T',
            'monto' => 1,
            'descripcion' => null,
            'vigente_desde' => '2026-01-01',
        ]);

        $padron = PadronUsuario::query()->create([
            'codigo' => 'U1',
            'nombre' => 'A',
            'apellido' => 'B',
            'direccion' => 'D',
            'estado' => 'activo',
            'tarifa_id' => $tarifa->id,
        ]);

        $multa = Multa::query()->create([
            'nombre' => 'M',
            'descripcion' => null,
            'monto' => 9,
            'activa' => true,
        ]);

        $servicio = app(MultaUsuarioService::class);
        $fila = $servicio->asignar([
            'padron_usuario_id' => $padron->id,
            'multa_id' => $multa->id,
            'mes' => '2026-06',
        ]);

        $this->assertSame('9.00', (string) $fila->monto);
        $this->assertFalse($fila->pagada);
    }

    #[Test]
    public function no_asigna_multa_inactiva(): void
    {
        $tarifa = Tarifa::query()->create([
            'nombre' => 'T',
            'monto' => 1,
            'descripcion' => null,
            'vigente_desde' => '2026-01-01',
        ]);

        $padron = PadronUsuario::query()->create([
            'codigo' => 'U2',
            'nombre' => 'A',
            'apellido' => 'B',
            'direccion' => 'D',
            'estado' => 'activo',
            'tarifa_id' => $tarifa->id,
        ]);

        $multa = Multa::query()->create([
            'nombre' => 'M',
            'descripcion' => null,
            'monto' => 9,
            'activa' => false,
        ]);

        $this->expectException(ValidationException::class);

        app(MultaUsuarioService::class)->asignar([
            'padron_usuario_id' => $padron->id,
            'multa_id' => $multa->id,
            'mes' => '2026-06',
        ]);
    }
}
