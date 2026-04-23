<?php

namespace Tests\Unit\Services;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class ReporteMensualServiceTest extends TestCase
{
    #[Test]
    public function pendiente_cierre_mensual(): void
    {
        $this->markTestSkipped('Implementar cierre idempotente y totales.');
    }
}
