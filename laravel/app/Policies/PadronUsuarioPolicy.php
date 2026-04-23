<?php

namespace App\Policies;

use App\Models\PadronUsuario;
use App\Models\User;

class PadronUsuarioPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, PadronUsuario $padronUsuario): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, PadronUsuario $padronUsuario): bool
    {
        return true;
    }

    public function delete(User $user, PadronUsuario $padronUsuario): bool
    {
        return true;
    }
}
