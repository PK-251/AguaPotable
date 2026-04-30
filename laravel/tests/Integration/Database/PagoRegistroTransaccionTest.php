<?php

namespace Tests\Integration\Database;

use App\Models\PadronUsuario;
use App\Models\Tarifa;
use App\Models\User;
use App\Services\Agua\PagoService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class PagoRegistroTransaccionTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function no_persiste_cambios_si_la_transaccion_externa_falla(): void
    {
        $usuario = User::factory()->create(['role' => 'operador']);
        $tarifa = Tarifa::query()->create([
            'nombre' => 'Doméstica',
            'monto' => 10,
            'descripcion' => null,
            'vigente_desde' => '2026-01-01',
        ]);

        $padron = PadronUsuario::query()->create([
            'codigo' => 'TRX-01',
            'nombre' => 'Test',
            'apellido' => 'Tx',
            'direccion' => 'Dir',
            'estado' => 'activo',
            'tarifa_id' => $tarifa->id,
        ]);

        $excepcion = null;

        try {
            DB::transaction(function () use ($usuario, $padron): void {
                app(PagoService::class)->registrarCobro($padron, $usuario, '2026-07', 'pagado');

                throw new \RuntimeException('rollback-forzado');
            });
        } catch (\RuntimeException $e) {
            $excepcion = $e;
        }

        $this->assertNotNull($excepcion);
        $this->assertDatabaseCount('pagos', 0);
    }
}
