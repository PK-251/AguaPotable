<?php

namespace App\Http\Requests\Agua;

use Illuminate\Foundation\Http\FormRequest;

class UpdateMultaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->esAdmin() ?? false;
    }

    protected function prepareForValidation(): void
    {
        $this->merge(['activa' => $this->boolean('activa')]);
    }

    /**
     * @return array<string, array<int, string|\Illuminate\Contracts\Validation\ValidationRule>>
     */
    public function rules(): array
    {
        return [
            'nombre' => ['required', 'string', 'max:255'],
            'descripcion' => ['nullable', 'string', 'max:5000'],
            'monto' => ['required', 'numeric', 'min:0', 'max:999999.99'],
            'activa' => ['required', 'boolean'],
        ];
    }
}
