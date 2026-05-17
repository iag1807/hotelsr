@extends('admin.layouts.app')
@section('titulo', 'Registrar Reserva')

@section('contenido')

<div class="form-wrapper">
<div class="card">
  <div class="card-header">
    <div>
      <div class="header-title">Registrar Reserva</div>
      <div class="header-sub">Nueva reserva</div>
    </div>
  </div>

  <div class="card-body">

    @if($errors->any())
      <div class="errors-box">
        <div class="errors-title">⚠ Errores</div>
        @foreach($errors->all() as $e)
          <p>{{ $e }}</p>
        @endforeach
      </div>
    @endif

    <form method="POST" action="{{ route('admin.reservas.store') }}">
      @csrf

      <div class="form-section">
        <div class="form-grid col-2">
          <div class="field">
            <label>Cliente <span style="color:#c9a84c">*</span></label>
            <select name="user_id" required>
              <option value="">Seleccione un cliente...</option>
              @foreach($clientes as $c)
                <option value="{{ $c->id }}" {{ old('user_id') == $c->id ? 'selected' : '' }}>
                  {{ $c->documento }} — {{ $c->name }}
                </option>
              @endforeach
            </select>
          </div>
          <div class="field">
            <label>Habitación <span style="color:#c9a84c">*</span></label>
            <select name="habitacion_id" id="habitacion_id" required>
              <option value="">Seleccione una habitación...</option>
              @foreach($habitaciones as $h)
                <option value="{{ $h->id }}"
                        data-precio="{{ $h->precio }}"
                        data-adicional="{{ $h->precio_persona_adicional }}"
                        {{ old('habitacion_id') == $h->id ? 'selected' : '' }}>
                  N° {{ $h->numero }} — {{ ucfirst($h->tipo_habitacion) }} — ${{ number_format($h->precio, 0, ',', '.') }}/noche
                </option>
              @endforeach
            </select>
          </div>
        </div>
      </div>

      <div class="form-section">
        <div class="form-grid col-2">
          <div class="field">
            <label>Fecha de ingreso <span style="color:#c9a84c">*</span></label>
            <input type="date" name="fecha_ingreso" id="fecha_ingreso"
                   min="{{ date('Y-m-d') }}"
                   value="{{ old('fecha_ingreso') }}"
                   onchange="actualizarMinSalida()" required>
          </div>
          <div class="field">
            <label>Fecha de salida <span style="color:#c9a84c">*</span></label>
            <input type="date" name="fecha_salida" id="fecha_salida"
                   value="{{ old('fecha_salida') }}"
                   onchange="calcularTotal()" required>
          </div>
        </div>
      </div>

      <div class="form-section">
        <div class="form-grid col-2">
          <div class="field">
            <label>Número de personas <span style="color:#c9a84c">*</span></label>
            <input type="number" name="numero_personas" id="numero_personas"
                   min="1" max="20" value="{{ old('numero_personas') }}"
                   oninput="calcularTotal()" required>
          </div>
          <div class="field">
            <label>Estado <span style="color:#c9a84c">*</span></label>
            <select name="estado" required>
              <option value="">Seleccione...</option>
              @foreach(['pendiente','confirmada','cancelada'] as $e)
                <option value="{{ $e }}" {{ old('estado') === $e ? 'selected' : '' }}>
                  {{ ucfirst($e) }}
                </option>
              @endforeach
            </select>
          </div>
        </div>
      </div>

      <div class="form-section">
        <div class="form-grid col-2">
          <div class="field">
            <label>Total a cobrar</label>
            <input type="number" name="total" id="total" min="0" step="0.01"
                   placeholder="Se calcula automáticamente"
                   value="{{ old('total') }}"
                   readonly style="cursor:not-allowed; opacity:.7;">
          </div>
          <div class="field">
            <label>Método de pago <span style="color:#c9a84c">*</span></label>
            <select name="metodo_pago" required>
              <option value="">Seleccione...</option>
              @foreach(['efectivo','transferencia'] as $mp)
                <option value="{{ $mp }}" {{ old('metodo_pago') === $mp ? 'selected' : '' }}>
                  {{ ucfirst($mp) }}
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
          <button type="submit" class="btn btn-submit">Registrar reserva</button>
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
    const min = sig.toISOString().split('T')[0];
    const salida = document.getElementById('fecha_salida');
    salida.min = min;
    if (salida.value && salida.value <= ingreso) salida.value = min;
    calcularTotal();
}

function calcularTotal() {
    const sel      = document.getElementById('habitacion_id');
    const ingreso  = document.getElementById('fecha_ingreso').value;
    const salida   = document.getElementById('fecha_salida').value;
    const personas = parseInt(document.getElementById('numero_personas').value) || 1;
    const totalEl  = document.getElementById('total');

    if (!sel.value || !ingreso || !salida) { totalEl.value = ''; return; }

    const base      = parseFloat(sel.selectedOptions[0].dataset.precio)    || 0;
    const adicional = parseFloat(sel.selectedOptions[0].dataset.adicional) || 0;
    const extra     = Math.max(0, personas - 1);
    const pNoche    = base + extra * adicional;
    const noches    = Math.round((new Date(salida + 'T00:00:00') - new Date(ingreso + 'T00:00:00')) / 86400000);

    if (noches <= 0) { totalEl.value = ''; return; }
    totalEl.value = pNoche * noches;
}

document.getElementById('habitacion_id').addEventListener('change', calcularTotal);
document.getElementById('fecha_salida').addEventListener('change', calcularTotal);
document.getElementById('numero_personas').addEventListener('input', calcularTotal);
document.addEventListener('DOMContentLoaded', calcularTotal);
</script>
@endpush
