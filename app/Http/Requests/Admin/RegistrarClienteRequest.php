<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class RegistrarClienteRequest extends FormRequest
{
    public function authorize(): bool { return auth()->user()->esAdmin(); }

    public function rules(): array
    {
        return [
            'documento'      => ['required', 'string', 'unique:users,documento'],
            'tipo_documento' => ['required', 'in:CC,TI,CE,PAS'],
            'name'           => ['required', 'string', 'max:100'],
            'genero'         => ['required', 'in:masculino,femenino,otro'],
            'celular'        => ['required', 'string', 'max:20'],
            'email'          => ['required', 'email', 'unique:users,email'],
            'password'       => ['required', 'string', 'min:8'],
            'rol'            => ['required', 'in:admin,cliente'],
            'estado'         => ['required', 'in:activo,inactivo'],
        ];
    }

    public function messages(): array
    {
        return [
            'documento.unique'  => 'Este documento ya está registrado.',
            'email.unique'      => 'Este correo ya está registrado.',
            'password.min'      => 'La contraseña debe tener al menos 8 caracteres.',
            'tipo_documento.in' => 'Tipo de documento no válido.',
            'genero.in'         => 'Género no válido.',
            'rol.in'            => 'Rol no válido.',
            'estado.in'         => 'Estado no válido.',
        ];
    }
}
