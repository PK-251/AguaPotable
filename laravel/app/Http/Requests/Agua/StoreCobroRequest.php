<?php

namespace App\Http\Requests\Agua;

use Illuminate\Foundation\Http\FormRequest;

class StoreCobroRequest extends FormRequest
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
            'padron_usuario_id' => ['required', 'integer', 'exists:padron_usuarios,id'],
            'periodo' => ['required', 'regex:/^\d{4}-(0[1-9]|1[0-2])$/'],
            'estado' => ['required', 'in:pagado,pendiente'],
        ];
    }
}
