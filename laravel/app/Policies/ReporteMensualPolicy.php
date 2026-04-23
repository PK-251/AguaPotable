<?php

namespace App\Policies;

use App\Models\ReporteMensual;
use App\Models\User;

class ReporteMensualPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, ReporteMensual $reporteMensual): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, ReporteMensual $reporteMensual): bool
    {
        return true;
    }
}
