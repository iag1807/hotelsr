<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class RegistrarReservaRequest extends FormRequest
{
    public function authorize(): bool { return auth()->user()->esAdmin(); }

    public function rules(): array
    {
        return [
            'user_id'        => ['required', 'integer', 'exists:users,id'],
            'habitacion_id'  => ['required', 'integer', 'exists:habitaciones,id'],
            'fecha_ingreso'  => ['required', 'date', 'date_format:Y-m-d', 'after_or_equal:today'],
            'fecha_salida'   => ['required', 'date', 'date_format:Y-m-d', 'after:fecha_ingreso'],
            'numero_personas'=> ['required', 'integer', 'min:1', 'max:20'],
            'estado'         => ['required', 'in:pendiente,confirmada,cancelada'],
            'metodo_pago'    => ['required', 'in:efectivo,transferencia'],
        ];
    }

    public function messages(): array
    {
        return [
            'user_id.required'        => 'Selecciona un cliente.',
            'user_id.exists'          => 'El cliente no existe.',
            'habitacion_id.required'  => 'Selecciona una habitación.',
            'habitacion_id.exists'    => 'La habitación no existe.',
            'fecha_ingreso.after_or_equal' => 'La fecha de ingreso no puede ser en el pasado.',
            'fecha_salida.after'      => 'La fecha de salida debe ser posterior a la de ingreso.',
            'numero_personas.min'     => 'Debe haber al menos 1 persona.',
            'estado.in'               => 'Estado no válido.',
            'metodo_pago.in'          => 'Método de pago no válido.',
        ];
    }
}
