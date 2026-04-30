<?php

namespace App\Http\Requests\Agua;

use Illuminate\Foundation\Http\FormRequest;

class StorePadronRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    /**
     * @return array<string, array<int, string|\Illuminate\Contracts\Validation\Rule>>
     */
    public function rules(): array
    {
        return [
            'codigo' => ['required', 'string', 'max:50', 'unique:padron_usuarios,codigo'],
            'nombre' => ['required', 'string', 'max:255'],
            'apellido' => ['required', 'string', 'max:255'],
            'direccion' => ['required', 'string', 'max:500'],
            'estado' => ['required', 'in:activo,cortado'],
            'tarifa_id' => ['required', 'integer', 'exists:tarifas,id'],
        ];
    }
}
