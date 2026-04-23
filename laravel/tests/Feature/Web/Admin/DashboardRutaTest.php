<?php

namespace Tests\Feature\Web\Admin;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class DashboardRutaTest extends TestCase
{
    #[Test]
    public function panel_admin_requiere_autenticacion(): void
    {
        $this->get(route('admin.dashboard'))->assertRedirect();
    }
}
