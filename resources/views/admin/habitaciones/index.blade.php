@extends('admin.layouts.app')
@section('titulo', 'Habitaciones')

@section('contenido')

<div class="panel">
  <div class="facturas-header">
    <h1>HABITACIONES</h1>
    <p>Habitaciones del hotel</p>
    <a href="{{ route('admin.habitaciones.create') }}" class="panel-action btn-descargar">
      Agregar habitación
    </a>
  </div>

  <div class="tabla-wrapper">
    <table>
      <thead>
        <tr>
          <th>Número</th>
          <th>Tipo</th>
          <th>Capacidad</th>
          <th>Precio</th>
          <th>Descripción</th>
          <th>Estado</th>
          <th>Acciones</th>
        </tr>
      </thead>
      <tbody>
        @forelse($habitaciones as $h)
          <tr>
            <td>{{ $h->numero }}</td>
            <td>{{ ucfirst($h->tipo_habitacion) }}</td>
            <td>{{ $h->capacidad }}</td>
            <td>${{ number_format($h->precio, 2) }}</td>
            <td>{{ $h->descripcion }}</td>
            <td>
              <span class="badge {{ $h->estado === 'disponible' ? 'badge-confirmada' : 'badge-cancelada' }}">
                {{ ucfirst($h->estado) }}
              </span>
            </td>
            <td>
              <a class="badge badge-confirmada"
                 href="{{ route('admin.habitaciones.edit', $h->id) }}">Editar</a>

              <form method="POST" action="{{ route('admin.habitaciones.estado', $h->id) }}" style="display:inline;">
                @csrf @method('PATCH')
                <button type="submit"
                        class="badge {{ $h->estado === 'disponible' ? 'badge-cancelada' : 'badge-confirmada' }}"
                        style="border:none; cursor:pointer;">
                  {{ $h->estado === 'disponible' ? 'Mantenimiento' : 'Disponible' }}
                </button>
              </form>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="7" style="text-align:center; padding:2rem; opacity:.4;">
              No hay habitaciones registradas.
            </td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>

@endsection
