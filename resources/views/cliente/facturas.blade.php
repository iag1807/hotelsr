@extends('cliente.layouts.app')
@section('titulo', 'Mis Facturas')

@section('contenido')

<div class="facturas-header">
  <h1>MIS FACTURAS</h1>
  <p>Descarga o visualiza las facturas de tus reservas.</p>
</div>

@if($facturas->isEmpty())
  <div class="sin-facturas">
    <h3>Aún no tienes facturas</h3>
    <p>Las facturas se generan automáticamente al realizar una reserva.</p>
  </div>
@else
  <div class="tabla-wrapper">
    <table>
      <thead>
        <tr>
          <th>N° Factura</th>
          <th>Habitación</th>
          <th>Fecha emisión</th>
          <th>Noches</th>
          <th>Entrada</th>
          <th>Salida</th>
          <th>Total</th>
          <th>Estado</th>
          <th>Acciones</th>
        </tr>
      </thead>
      <tbody>
        @foreach($facturas as $factura)
          @php
            $reserva = $factura->reserva;
            $hab     = $reserva->habitacion;
            $noches  = \Carbon\Carbon::parse($reserva->fecha_ingreso)
                         ->diffInDays(\Carbon\Carbon::parse($reserva->fecha_salida));
          @endphp
          <tr>
            <td class="td-numero">{{ $factura->numero_factura }}</td>
            <td class="td-hab">
              {{ ucfirst($hab->tipo_habitacion) }} N°{{ $hab->numero }}
            </td>
            <td class="td-fecha">
              {{ \Carbon\Carbon::parse($factura->fecha_factura)->format('d/m/Y') }}
            </td>
            <td class="td-fecha">{{ $noches }}</td>
            <td class="td-fecha">
              {{ \Carbon\Carbon::parse($reserva->fecha_ingreso)->format('d/m/Y') }}
            </td>
            <td class="td-fecha">
              {{ \Carbon\Carbon::parse($reserva->fecha_salida)->format('d/m/Y') }}
            </td>
            <td class="td-total">${{ number_format($factura->total, 0, ',', '.') }}</td>
            <td>
              <span class="badge {{ $factura->badgeClass() }}">
                {{ ucfirst($factura->estado) }}
              </span>
            </td>
            <td>
              <div class="acciones">
                <a href="{{ route('cliente.facturas.pdf', ['id' => $factura->id, 'ver' => 1]) }}"
                   target="_blank" class="btn-accion btn-ver">Ver</a>
                <a href="{{ route('cliente.facturas.pdf', $factura->id) }}"
                   class="btn-accion btn-descargar">Descargar</a>
              </div>
            </td>
          </tr>
        @endforeach
      </tbody>
    </table>
  </div>
@endif

@endsection
