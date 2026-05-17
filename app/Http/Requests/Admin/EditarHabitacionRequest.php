<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class EditarHabitacionRequest extends FormRequest
{
    public function authorize(): bool { return auth()->user()->esAdmin(); }

    public function rules(): array
    {
        $id = $this->route('habitacion');

        return [
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
            'tipo_habitacion.in' => 'Tipo de habitación no válido.',
            'capacidad.min'      => 'La capacidad debe ser al menos 1.',
            'estado.in'          => 'Estado no válido. Usa disponible o mantenimiento.',
        ];
    }
}
