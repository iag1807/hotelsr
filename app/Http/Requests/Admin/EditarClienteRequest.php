<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class EditarClienteRequest extends FormRequest
{
    public function authorize(): bool { return auth()->user()->esAdmin(); }

    public function rules(): array
    {
        $id = $this->route('cliente');

        return [
            'tipo_documento' => ['required', 'in:CC,TI,CE,PAS'],
            'name'           => ['required', 'string', 'max:100'],
            'genero'         => ['required', 'in:masculino,femenino,otro'],
            'celular'        => ['required', 'string', 'max:20'],
            'email'          => ['required', 'email', Rule::unique('users', 'email')->ignore($id)],
            'rol'            => ['required', 'in:admin,cliente'],
            'estado'         => ['required', 'in:activo,inactivo'],
        ];
    }
}
