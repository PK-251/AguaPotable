<?php

namespace Tests\Integration\Database;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class CierreMensualIdempotenteTest extends TestCase
{
    #[Test]
    public function pendiente_cierre_unico_por_periodo(): void
    {
        $this->markTestSkipped('Implementar restricción y prueba de doble cierre.');
    }
}
