<?php

namespace App\Policies;

use App\Models\Pago;
use App\Models\User;

class PagoPolicy
{
    public function viewAny(User $user): bool
    {
        // TODO: roles/permisos
        return true;
    }

    public function view(User $user, Pago $pago): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, Pago $pago): bool
    {
        return true;
    }

    public function delete(User $user, Pago $pago): bool
    {
        return true;
    }
}
