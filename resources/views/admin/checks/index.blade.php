@extends('admin.layouts.app')
@section('titulo', 'Checks In/Out')

@section('contenido')

<div class="panel">
  <div class="facturas-header">
    <h1>CHECKS-IN / CHECKS-OUT</h1>
    <p>Registra los ingresos y salidas de las reservas confirmadas</p>
  </div>

  @if($reservas->isEmpty())
    <div style="text-align:center; padding:48px; color:#888; font-size:15px;">
      No hay reservas confirmadas en este momento.
    </div>
  @else
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
            <th>Check-in</th>
            <th>Comprobante</th>
            <th>Acción</th>
          </tr>
        </thead>
        <tbody>
          @foreach($reservas as $r)
            <tr>
              <td>{{ $r->user->documento }}</td>
              <td>{{ $r->user->name }}</td>
              <td>{{ $r->fecha_ingreso->locale('es')->isoFormat('D [de] MMMM [de] YYYY') }}</td>
              <td>{{ $r->fecha_salida->locale('es')->isoFormat('D [de] MMMM [de] YYYY') }}</td>
              <td>{{ $r->numero_personas }}</td>
              <td>{{ $r->habitacion->numero }} — {{ ucfirst($r->habitacion->tipo_habitacion) }}</td>
              <td>
                @if($r->checkIn)
                  <span class="badge badge-confirmada">
                    {{ $r->checkIn->fecha_hora_check_in->format('d M, H:i') }}
                  </span>
                @else
                  <span style="color:#aaa; font-size:12px;">Pendiente</span>
                @endif
              </td>
              <td>
                @if($r->comprobante_pago)
                  <a href="{{ Storage::url($r->comprobante_pago) }}"
                     target="_blank" class="acciones btn-accion btn-descargar">
                    Ver
                  </a>
                @else
                  <span class="acciones btn-accion btn-ver">Sin comprobante</span>
                @endif
              </td>
              <td>
                @if(!$r->checkIn)
                  <form method="POST" action="{{ route('admin.checks.checkin', $r->id) }}" style="display:inline;">
                    @csrf
                    <button type="submit" class="acciones btn-accion btn-ver">Check-in</button>
                  </form>
                @else
                  <form method="POST" action="{{ route('admin.checks.checkout', $r->id) }}" style="display:inline;">
                    @csrf
                    <button type="submit" class="acciones btn-accion btn-ver">Check-out</button>
                  </form>
                @endif
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  @endif
</div>

@endsection
