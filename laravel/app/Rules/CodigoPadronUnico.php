<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class CodigoPadronUnico implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // Pendiente: consultar padron / ignorar en update
    }
}
