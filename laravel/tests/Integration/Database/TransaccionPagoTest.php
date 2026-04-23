<?php

namespace Tests\Integration\Database;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class TransaccionPagoTest extends TestCase
{
    #[Test]
    public function pendiente_transaccion_en_registro_pago(): void
    {
        $this->markTestSkipped('Usar RefreshDatabase y escenarios reales de pago.');
    }
}
