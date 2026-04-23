<?php

namespace Tests\Unit\Support;

use App\Support\Money;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class MoneyTest extends TestCase
{
    #[Test]
    public function convierte_montos_a_centavos(): void
    {
        $this->assertSame(1050, Money::toCents('10.50'));
    }
}
