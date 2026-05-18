@extends('admin.layouts.app')
@section('titulo', 'Dashboard')

@section('contenido')

{{-- Hero --}}
<section class="hero">
  <div class="hero-bg"></div>
  <div class="hero-overlay"></div>
  <div class="hero-content">
    <div class="hero-text">
      <h2>{{ $user->saludo() }},<br>
        <em>{{ $user->name }}</em>
      </h2>
    </div>
  </div>
</section>

<div class="content">
  <div class="bottom-grid">

    {{-- ══ Mapa de habitaciones ══════════════════════════════════════════ --}}
    <div class="panel">
      <div class="panel-header">
        <span class="panel-title">Mapa de habitaciones</span>
        <a href="{{ route('admin.habitaciones') }}" class="panel-action">Gestionar</a>
      </div>

      <div class="rooms-legend">
        <div class="legend-item"><span class="legend-dot ld-ocupada"></span>Reservada</div>
        <div class="legend-item"><span class="legend-dot ld-disponible"></span>Disponible</div>
        <div class="legend-item"><span class="legend-dot ld-mantenimiento"></span>Mantenimiento</div>
      </div>

      <div style="padding: 1.25rem 1.5rem 0.5rem;">
        @foreach($ordenTipos as $tipo)
          @if($habsPorTipo->has($tipo))
            <div class="room-group">
              <div class="room-group-title">{{ ucfirst($tipo) }}</div>
              <div class="rooms-grid">
                @foreach($habsPorTipo[$tipo] as $hab)
                  @php
                    $clase = $hab->estado === 'mantenimiento'
                      ? 'mantenimiento'
                      : (isset($habsOcupadas[$hab->id]) ? 'ocupada' : 'disponible');
                  @endphp
                  <div class="room-cell {{ $clase }}">
                    <div class="room-num">{{ $hab->numero }}</div>
                  </div>
                @endforeach
              </div>
            </div>
          @endif
        @endforeach
      </div>
    </div>

    {{-- ══ Reservas de hoy ════════════════════════════════════════════════ --}}
    <div class="panel">
      <div class="panel-header">
        <span class="panel-title">Reservas para hoy</span>
      </div>

      <div class="res-table-wrapper">
      <table class="res-table">
        <thead>
          <tr>
            <th>Huésped</th>
            <th>Habitación</th>
            <th>Ingreso</th>
            <th>Salida</th>
            <th>Estado</th>
          </tr>
        </thead>
        <tbody>
          @forelse($reservasHoy as $r)
            <tr>
              <td>{{ $r->user->name }}</td>
              <td>{{ $r->habitacion->numero }} · {{ ucfirst($r->habitacion->tipo_habitacion) }}</td>
              <td>{{ \Carbon\Carbon::parse($r->fecha_ingreso)->locale('es')->isoFormat('D MMM') }}</td>
              <td>{{ \Carbon\Carbon::parse($r->fecha_salida)->locale('es')->isoFormat('D MMM') }}</td>
              <td><span class="badge {{ $r->badgeClass() }}">{{ $r->estado }}</span></td>
            </tr>
          @empty
            <tr>
              <td colspan="5" style="text-align:center; padding:2rem; opacity:.4;">
                Sin reservas programadas para hoy
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
      </div>
    </div>

  </div>
</div>

@endsection