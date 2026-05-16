<?php

namespace App\Http\Requests\Cliente;

use Illuminate\Foundation\Http\FormRequest;

class BuscarHabitacionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->esCliente();
    }

    public function rules(): array
    {
        return [
            'entrada'  => ['required', 'date', 'date_format:Y-m-d', 'after_or_equal:today'],
            'salida'   => ['required', 'date', 'date_format:Y-m-d', 'after:entrada'],
            'personas' => ['required', 'integer', 'min:1', 'max:10'],
        ];
    }

    public function messages(): array
    {
        return [
            'entrada.required'       => 'La fecha de entrada es obligatoria.',
            'entrada.after_or_equal' => 'La fecha de entrada no puede ser en el pasado.',
            'salida.required'        => 'La fecha de salida es obligatoria.',
            'salida.after'           => 'La fecha de salida debe ser posterior a la de entrada.',
            'personas.required'      => 'Indica el número de personas.',
            'personas.min'           => 'Debe haber al menos 1 persona.',
            'personas.max'           => 'El máximo permitido es 10 personas.',
        ];
    }
}
