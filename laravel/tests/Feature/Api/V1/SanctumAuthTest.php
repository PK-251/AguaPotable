<?php

namespace Tests\Feature\Api\V1;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class SanctumAuthTest extends TestCase
{
    #[Test]
    public function pendiente_emision_de_token(): void
    {
        $this->markTestSkipped('Implementar login API y prueba con auth:sanctum.');
    }
}
