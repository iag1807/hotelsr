@extends('admin.layouts.app')
@section('titulo', 'Registrar Habitación')

@section('contenido')

<div class="form-wrapper">
<div class="card">
  <div class="card-header">
    <div>
      <div class="header-title">Registrar Habitación</div>
      <div class="header-sub">Nueva habitación</div>
    </div>
  </div>

  <div class="card-body">

    @if($errors->any())
      <div class="errors-box">
        <div class="errors-title">⚠ Errores</div>
        @foreach($errors->all() as $e) <p>{{ $e }}</p> @endforeach
      </div>
    @endif

    <form method="POST" action="{{ route('admin.habitaciones.store') }}">
      @csrf

      <div class="form-section">
        <div class="form-grid col-2">
          <div class="field">
            <label>Número</label>
            <input type="text" name="numero" value="{{ old('numero') }}" required>
          </div>
          <div class="field">
            <label>Tipo de habitación</label>
            <select name="tipo_habitacion" required>
              <option value="">Seleccione…</option>
              @foreach(['sencilla','bañera','jacuzzi','doble','triple','multiple'] as $t)
                <option value="{{ $t }}" {{ old('tipo_habitacion') === $t ? 'selected' : '' }}>
                  {{ ucfirst($t) }}
                </option>
              @endforeach
            </select>
          </div>
        </div>
      </div>

      <div class="form-section">
        <div class="form-grid col-2">
          <div class="field">
            <label>Capacidad</label>
            <input type="number" name="capacidad" min="1" value="{{ old('capacidad') }}" required>
          </div>
          <div class="field">
            <label>Precio</label>
            <input type="number" name="precio" min="0" step="0.01" value="{{ old('precio') }}" required>
          </div>
        </div>
        <div class="form-grid col-2" style="margin-top:1rem">
          <div class="field">
            <label>Descripción</label>
            <input type="text" name="descripcion" value="{{ old('descripcion') }}" required>
          </div>
          <div class="field">
            <label>Estado</label>
            <select name="estado" required>
              <option value="">Seleccione…</option>
              <option value="disponible"    {{ old('estado') === 'disponible'    ? 'selected' : '' }}>Disponible</option>
              <option value="mantenimiento" {{ old('estado') === 'mantenimiento' ? 'selected' : '' }}>Mantenimiento</option>
            </select>
          </div>
        </div>
      </div>

      <div class="card-footer" style="margin:1.8rem -2rem -1.8rem; padding:0 2rem;">
        <span class="footer-note"><span>*</span> Campos obligatorios</span>
        <div class="footer-actions">
          <a href="{{ route('admin.habitaciones') }}" class="btn btn-back">Volver</a>
          <button type="submit" class="btn btn-submit">Registrar habitación</button>
        </div>
      </div>

    </form>
  </div>
</div>
</div>

@endsection
