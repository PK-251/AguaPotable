<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class LoginRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, array<int, string>>
     */
    public function rules(): array
    {
        return [
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'email.required' => 'El correo es obligatorio.',
            'email.email' => 'Ingresa un correo válido.',
            'password.required' => 'La contraseña es obligatoria.',
        ];
    }

    /**
     * Intenta autenticar al usuario contra el guard web.
     *
     * @throws ValidationException
     */
    public function authenticate(): void
    {
        $credentials = $this->only('email', 'password');
        $remember = $this->boolean('remember');

        if (! Auth::guard('web')->attempt($credentials, $remember)) {
            throw ValidationException::withMessages([
                'email' => 'Las credenciales no coinciden con nuestros registros.',
            ]);
        }
    }
}
