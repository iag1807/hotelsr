<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class RegistrarHabitacionRequest extends FormRequest
{
    public function authorize(): bool { return auth()->user()->esAdmin(); }

    public function rules(): array
    {
        return [
            'numero'          => ['required', 'string', 'unique:habitaciones,numero'],
            'tipo_habitacion' => ['required', 'in:sencilla,bañera,jacuzzi,doble,triple,multiple'],
            'capacidad'       => ['required', 'integer', 'min:1'],
            'precio'          => ['required', 'numeric', 'min:0'],
            'descripcion'     => ['required', 'string', 'max:255'],
            'estado'          => ['required', 'in:disponible,mantenimiento'],
        ];
    }

    public function messages(): array
    {
        return [
            'numero.required'          => 'El número de habitación es obligatorio.',
            'numero.unique'            => 'Este número de habitación ya existe.',
            'tipo_habitacion.required' => 'Selecciona el tipo de habitación.',
            'tipo_habitacion.in'       => 'Tipo de habitación no válido.',
            'capacidad.min'            => 'La capacidad debe ser al menos 1.',
            'precio.min'               => 'El precio debe ser mayor a 0.',
            'estado.in'                => 'Estado no válido. Usa disponible o mantenimiento.',
        ];
    }
}
