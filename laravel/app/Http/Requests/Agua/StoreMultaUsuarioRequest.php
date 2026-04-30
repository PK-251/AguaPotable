<?php

namespace App\Http\Requests\Agua;

use Illuminate\Foundation\Http\FormRequest;

class StoreMultaUsuarioRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    /**
     * @return array<string, array<int, string|\Illuminate\Contracts\Validation\ValidationRule>>
     */
    public function rules(): array
    {
        return [
            'padron_usuario_id' => ['required', 'integer', 'exists:padron_usuarios,id'],
            'multa_id' => ['required', 'integer', 'exists:multas,id'],
            'mes' => ['required', 'regex:/^\d{4}-(0[1-9]|1[0-2])$/'],
            'monto' => ['nullable', 'numeric', 'min:0', 'max:999999.99'],
        ];
    }
}
