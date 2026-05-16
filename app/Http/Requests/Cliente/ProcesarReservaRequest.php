<?php

namespace App\Http\Requests\Cliente;

use Illuminate\Foundation\Http\FormRequest;

class ProcesarReservaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->esCliente();
    }

    public function rules(): array
    {
        return [
            'id_habitacion' => ['required', 'integer', 'exists:habitaciones,id'],
            'fecha_ingreso' => ['required', 'date', 'date_format:Y-m-d', 'after_or_equal:today'],
            'fecha_salida'  => ['required', 'date', 'date_format:Y-m-d', 'after:fecha_ingreso'],
            'num_personas'  => ['required', 'integer', 'min:1', 'max:10'],
            'comprobante'   => ['required', 'string'],  // base64
        ];
    }

    public function messages(): array
    {
        return [
            'id_habitacion.required'       => 'Debes seleccionar una habitación.',
            'id_habitacion.exists'         => 'La habitación seleccionada no existe.',
            'fecha_ingreso.required'       => 'La fecha de entrada es obligatoria.',
            'fecha_ingreso.after_or_equal' => 'La fecha de entrada no puede ser en el pasado.',
            'fecha_salida.required'        => 'La fecha de salida es obligatoria.',
            'fecha_salida.after'           => 'La fecha de salida debe ser posterior a la de entrada.',
            'num_personas.required'        => 'Indica el número de personas.',
            'num_personas.min'             => 'Debe haber al menos 1 persona.',
            'comprobante.required'         => 'Adjunta el comprobante de pago.',
        ];
    }
}
