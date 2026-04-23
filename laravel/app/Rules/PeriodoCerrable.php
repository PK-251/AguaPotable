<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class PeriodoCerrable implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // Pendiente: validar no duplicar cierres, formato AAAA-MM, etc.
    }
}
