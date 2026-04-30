<?php

namespace App\Http\Requests\Agua;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTarifaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->esAdmin() ?? false;
    }

    /**
     * @return array<string, array<int, string|\Illuminate\Contracts\Validation\ValidationRule>>
     */
    public function rules(): array
    {
        return [
            'nombre' => ['required', 'string', 'max:255'],
            'monto' => ['required', 'numeric', 'min:0', 'max:999999.99'],
            'descripcion' => ['nullable', 'string', 'max:5000'],
            'vigente_desde' => ['required', 'date'],
        ];
    }
}
