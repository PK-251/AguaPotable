<?php

namespace App\Http\Requests\Agua;

use Illuminate\Foundation\Http\FormRequest;

class ImportPadronRequest extends FormRequest
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
        $max = (int) config('agua.import.max_size_kb', 5120);

        return [
            'archivo' => ['required', 'file', 'max:'.$max],
        ];
    }
}
