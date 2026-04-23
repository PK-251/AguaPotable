<?php

namespace App\Http\Requests\Agua;

use Illuminate\Foundation\Http\FormRequest;

class StoreTarifaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    /**
     * @return array<string, array<int, string|object>>
     */
    public function rules(): array
    {
        return [
            // TODO: tarifas
        ];
    }
}
