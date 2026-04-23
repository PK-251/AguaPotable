<?php

namespace Tests\Feature\Web\Auth;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class LoginTest extends TestCase
{
    #[Test]
    public function muestra_formulario_de_login(): void
    {
        $this->markTestSkipped('Completar cuando exista flujo Auth::attempt y redirección.');
    }
}
