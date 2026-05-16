<?php

namespace App\Http\Requests\Cliente;

use Illuminate\Foundation\Http\FormRequest;

class CambiarContrasenaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->esCliente();
    }

    public function rules(): array
    {
        return [
            'actual'            => ['required', 'string'],
            'nueva'             => ['required', 'string', 'min:8', 'confirmed'],
            // nueva_confirmation se valida implícitamente con 'confirmed'
        ];
    }

    public function messages(): array
    {
        return [
            'actual.required' => 'Ingresa tu contraseña actual.',
            'nueva.required'  => 'Ingresa la nueva contraseña.',
            'nueva.min'       => 'La nueva contraseña debe tener al menos 8 caracteres.',
            'nueva.confirmed' => 'Las contraseñas no coinciden.',
        ];
    }
}
