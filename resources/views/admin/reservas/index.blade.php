@extends('admin.layouts.app')
@section('titulo', 'Reservas')

@section('contenido')

<div class="panel">
  <div class="facturas-header">
    <h1>RESERVAS</h1>
    <p>Historial de reservas</p>
    <a href="{{ route('admin.reservas.create') }}" class="panel-action btn-descargar">
      Agregar reserva
    </a>
  </div>

  <div class="tabla-wrapper">
    <table>
      <thead>
        <tr>
          <th>Documento</th>
          <th>Nombre</th>
          <th>Fecha ingreso</th>
          <th>Fecha salida</th>
          <th>Huéspedes</th>
          <th>Habitación</th>
          <th>Estado</th>
          <th>Total</th>
          <th>Método pago</th>
          <th>Comprobante</th>
          <th>Acciones</th>
        </tr>
      </thead>
      <tbody>
        @forelse($reservas as $r)
          <tr>
            <td>{{ $r->user->documento }}</td>
            <td>{{ $r->user->name }}</td>
            <td>{{ $r->fecha_ingreso->locale('es')->isoFormat('D [de] MMMM [de] YYYY') }}</td>
            <td>{{ $r->fecha_salida->locale('es')->isoFormat('D [de] MMMM [de] YYYY') }}</td>
            <td>{{ $r->numero_personas }}</td>
            <td>{{ $r->habitacion->numero }} · {{ ucfirst($r->habitacion->tipo_habitacion) }}</td>
            <td><span class="badge {{ $r->badgeClass() }}">{{ $r->estado }}</span></td>
            <td>${{ number_format($r->total, 2) }}</td>
            <td>{{ $r->pagos->isNotEmpty() ? ucfirst($r->pagos->first()->metodo_pago) : '—' }}</td>
            <td>
              @if($r->comprobante_pago)
                <a href="{{ Storage::url($r->comprobante_pago) }}"
                   target="_blank" class="acciones btn-accion btn-descargar">Ver</a>
              @else
                <span class="acciones btn-accion btn-ver">Sin comprobante</span>
              @endif
            </td>
            <td>
              <a class="badge badge-confirmada"
                 href="{{ route('admin.reservas.edit', $r->id) }}">Editar</a>

              @if($r->estado === 'pendiente')
                <form method="POST" action="{{ route('admin.reservas.confirmar', $r->id) }}" style="display:inline;">
                  @csrf @method('PATCH')
                  <button type="submit" class="badge badge-confirmada" style="border:none;cursor:pointer;">
                    Confirmar
                  </button>
                </form>
              @endif

              @if(!in_array($r->estado, ['cancelada','finalizada']))
                <form method="POST" action="{{ route('admin.reservas.cancelar', $r->id) }}" style="display:inline;">
                  @csrf @method('PATCH')
                  <button type="submit" class="badge badge-cancelada" style="border:none;cursor:pointer;"
                          onclick="return confirm('¿Cancelar esta reserva?')">
                    Cancelar
                  </button>
                </form>
              @endif
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="11" style="text-align:center; padding:2rem; opacity:.4;">
              No hay reservas registradas.
            </td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>

@endsection
