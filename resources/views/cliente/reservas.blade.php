@extends('cliente.layouts.app')
@section('titulo', 'Mis Reservas')

@section('contenido')

<div class="facturas-header">
  <h1>TU PRÓXIMA RESERVA</h1>
  <p>Esta es tu próxima reserva programada.</p>

  {{-- ══ Próxima reserva ══════════════════════════════════════════════════ --}}
  <div class="proxima-reserva">
    @if($proximaReserva)
      <div class="info-reserva">
        <h3>Habitación {{ ucfirst($proximaReserva->habitacion->tipo_habitacion) }}</h3>
        <p>Fecha de entrada:
          {{ $proximaReserva->fecha_ingreso->locale('es')->isoFormat('D [de] MMMM [de] YYYY') }}
        </p>
        <p>Fecha de salida:
          {{ $proximaReserva->fecha_salida->locale('es')->isoFormat('D [de] MMMM [de] YYYY') }}
        </p>
        <p>Huéspedes: {{ $proximaReserva->numero_personas }}</p>
        <p>Habitación:
          {{ $proximaReserva->habitacion->numero }}
          &bull;
          {{ ucfirst($proximaReserva->habitacion->tipo_habitacion) }}
        </p>
        <span class="badge {{ $proximaReserva->badgeClass() }}">
          {{ ucfirst($proximaReserva->estado) }}
        </span>
      </div>
    @else
      <div class="sin-facturas">
        <h3>No tienes reservas próximas</h3>
        <p>Cuando realices una reserva aparecerá aquí.</p>
      </div>
    @endif
  </div>

  {{-- ══ Historial ════════════════════════════════════════════════════════ --}}
  <div class="bottom-grid">
    <div class="table-wrapper">
      <h1>HISTORIAL DE RESERVAS</h1>
      <p>Aquí puedes ver todas tus reservas.</p>

      @if($historial->isEmpty())
        <div class="sin-facturas">
          <h3>Aún no tienes reservas</h3>
          <p>Ve al inicio y busca la habitación perfecta para ti.</p>
        </div>
      @else
        <table class="res-table">
          <thead>
            <tr>
              <th>Fecha ingreso</th>
              <th>Fecha salida</th>
              <th>Huéspedes</th>
              <th>Habitación</th>
              <th>Estado</th>
              <th>Total</th>
              <th>Método de pago</th>
              <th>Comprobante</th>
            </tr>
          </thead>
          <tbody>
            @foreach($historial as $reserva)
              <tr>
                <td>{{ $reserva->fecha_ingreso->locale('es')->isoFormat('D [de] MMMM [de] YYYY') }}</td>
                <td>{{ $reserva->fecha_salida->locale('es')->isoFormat('D [de] MMMM [de] YYYY') }}</td>
                <td>{{ $reserva->numero_personas }}</td>
                <td>
                  {{ $reserva->habitacion->numero }}
                  —
                  {{ ucfirst($reserva->habitacion->tipo_habitacion) }}
                </td>
                <td>
                  <span class="badge {{ $reserva->badgeClass() }}">
                    {{ ucfirst($reserva->estado) }}
                  </span>
                </td>
                <td>${{ number_format($reserva->total, 2, ',', '.') }}</td>
                <td>
                  {{ $reserva->pagos->isNotEmpty()
                      ? ucfirst($reserva->pagos->first()->metodo_pago)
                      : '—' }}
                </td>
                <td>
                  @if($reserva->comprobante_pago)
                    <a href="{{ Storage::url($reserva->comprobante_pago) }}"
                       target="_blank" class="btn-accion btn-descargar">
                      Ver comprobante
                    </a>
                  @else
                    <span class="btn-accion btn-ver">Sin comprobante</span>
                  @endif
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      @endif
    </div>
  </div>

</div>

@endsection
