@extends('admin.layouts.app')
@section('titulo', 'Editar Reserva')

@section('contenido')

<div class="form-wrapper">
<div class="card">
  <div class="card-header">
    <div>
      <div class="header-title">Editar Reserva</div>
      <div class="header-sub">Modificar reserva #{{ $reserva->id }}</div>
    </div>
  </div>

  <div class="card-body">

    @if($errors->any())
      <div class="errors-box">
        <div class="errors-title">⚠ Errores</div>
        @foreach($errors->all() as $e) <p>{{ $e }}</p> @endforeach
      </div>
    @endif

    <form method="POST" action="{{ route('admin.reservas.update', $reserva->id) }}">
      @csrf
      @method('PUT')

      <div class="form-section">
        <div class="form-grid col-2">
          <div class="field">
            <label>Cliente</label>
            <select name="user_id" required>
              <option value="">Seleccione un cliente...</option>
              @foreach($clientes as $c)
                <option value="{{ $c->id }}"
                        {{ old('user_id', $reserva->user_id) == $c->id ? 'selected' : '' }}>
                  {{ $c->documento }} — {{ $c->name }}
                </option>
              @endforeach
            </select>
          </div>
          <div class="field">
            <label>Habitación</label>
            <select name="habitacion_id" required>
              <option value="">Seleccione una habitación...</option>
              @foreach($habitaciones as $h)
                <option value="{{ $h->id }}"
                        {{ old('habitacion_id', $reserva->habitacion_id) == $h->id ? 'selected' : '' }}>
                  N° {{ $h->numero }} — {{ ucfirst($h->tipo_habitacion) }}
                </option>
              @endforeach
            </select>
          </div>
        </div>
      </div>

      <div class="form-section">
        <div class="form-grid col-2">
          <div class="field">
            <label>Fecha de ingreso</label>
            <input type="date" name="fecha_ingreso" id="fecha_ingreso"
                   value="{{ old('fecha_ingreso', $reserva->fecha_ingreso->format('Y-m-d')) }}"
                   onchange="actualizarMinSalida()" required>
          </div>
          <div class="field">
            <label>Fecha de salida</label>
            <input type="date" name="fecha_salida" id="fecha_salida"
                   value="{{ old('fecha_salida', $reserva->fecha_salida->format('Y-m-d')) }}"
                   required>
          </div>
        </div>
      </div>

      <div class="form-section">
        <div class="form-grid col-2">
          <div class="field">
            <label>Número de personas</label>
            <input type="number" name="numero_personas" min="1" max="20"
                   value="{{ old('numero_personas', $reserva->numero_personas) }}" required>
          </div>
          <div class="field">
            <label>Estado</label>
            <select name="estado" required>
              @foreach(['pendiente','confirmada','cancelada','finalizada'] as $e)
                <option value="{{ $e }}"
                        {{ old('estado', $reserva->estado) === $e ? 'selected' : '' }}>
                  {{ ucfirst($e) }}
                </option>
              @endforeach
            </select>
          </div>
        </div>
      </div>

      <div class="card-footer" style="margin:1.8rem -2rem -1.8rem; padding:0 2rem;">
        <span class="footer-note"><span>*</span> Campos obligatorios</span>
        <div class="footer-actions">
          <a href="{{ route('admin.reservas') }}" class="btn btn-back">Volver</a>
          <button type="submit" class="btn btn-submit">Guardar cambios</button>
        </div>
      </div>

    </form>
  </div>
</div>
</div>

@endsection

@push('scripts')
<script>
function actualizarMinSalida() {
    const ingreso = document.getElementById('fecha_ingreso').value;
    if (!ingreso) return;
    const sig = new Date(ingreso + 'T00:00:00');
    sig.setDate(sig.getDate() + 1);
    const salida = document.getElementById('fecha_salida');
    salida.min = sig.toISOString().split('T')[0];
    if (salida.value && salida.value <= ingreso) salida.value = salida.min;
}
</script>
@endpush
