@extends('admin.layouts.app')
@section('titulo', 'Registrar Huésped')

@section('contenido')

<div class="form-wrapper">
<div class="card">
  <div class="card-header">
    <div>
      <div class="header-title">Registrar Huésped</div>
      <div class="header-sub">Nuevo registro</div>
    </div>
  </div>

  <div class="card-body">

    @if($errors->any())
      <div class="errors-box">
        <div class="errors-title">⚠ Errores</div>
        @foreach($errors->all() as $e) <p>{{ $e }}</p> @endforeach
      </div>
    @endif

    <form method="POST" action="{{ route('admin.clientes.store') }}">
      @csrf

      <div class="form-section">
        <div class="form-grid col-2">
          <div class="field">
            <label>Tipo de documento</label>
            <select name="tipo_documento" required>
              <option value="">Seleccione…</option>
              @foreach(['CC'=>'C.C — Cédula de ciudadanía','TI'=>'T.I — Tarjeta de identidad','CE'=>'C.E — Cédula de extranjería','PAS'=>'Pasaporte'] as $val => $label)
                <option value="{{ $val }}" {{ old('tipo_documento') === $val ? 'selected' : '' }}>{{ $label }}</option>
              @endforeach
            </select>
          </div>
          <div class="field">
            <label>Número de documento</label>
            <input type="text" name="documento" value="{{ old('documento') }}" required>
          </div>
        </div>
      </div>

      <div class="form-section">
        <div class="form-grid col-2">
          <div class="field">
            <label>Nombre completo</label>
            <input type="text" name="name" value="{{ old('name') }}" required>
          </div>
          <div class="field">
            <label>Correo electrónico</label>
            <input type="email" name="email" value="{{ old('email') }}" required>
          </div>
        </div>
        <div class="form-grid col-2" style="margin-top:1rem">
          <div class="field">
            <label>Contraseña</label>
            <input type="password" name="password" required>
          </div>
          <div class="field">
            <label>Celular</label>
            <input type="text" name="celular" value="{{ old('celular') }}" required>
          </div>
        </div>
      </div>

      <div class="form-section">
        <div class="form-grid col-2">
          <div class="field">
            <label>Género</label>
            <select name="genero" required>
              <option value="">Seleccione…</option>
              <option value="masculino" {{ old('genero') === 'masculino' ? 'selected' : '' }}>Masculino</option>
              <option value="femenino"  {{ old('genero') === 'femenino'  ? 'selected' : '' }}>Femenino</option>
              <option value="otro"      {{ old('genero') === 'otro'      ? 'selected' : '' }}>Otro</option>
            </select>
          </div>
          <div class="field">
            <label>Rol</label>
            <select name="rol" required>
              <option value="">Seleccione…</option>
              <option value="cliente" {{ old('rol') === 'cliente' ? 'selected' : '' }}>Cliente</option>
              <option value="admin"   {{ old('rol') === 'admin'   ? 'selected' : '' }}>Administrador</option>
            </select>
          </div>
        </div>
        <div class="form-grid" style="margin-top:1rem">
          <div class="field">
            <label>Estado</label>
            <select name="estado" required>
              <option value="">Seleccione…</option>
              <option value="activo"   {{ old('estado') === 'activo'   ? 'selected' : '' }}>Activo</option>
              <option value="inactivo" {{ old('estado') === 'inactivo' ? 'selected' : '' }}>Inactivo</option>
            </select>
          </div>
        </div>
      </div>

      <div class="card-footer">
        <span class="footer-note"><span>*</span> Campos obligatorios</span>
        <div class="footer-actions">
          <a href="{{ route('admin.clientes') }}" class="btn btn-back">Volver</a>
          <button type="submit" class="btn btn-submit">Registrar huésped</button>
        </div>
      </div>

    </form>
  </div>
</div>
</div>
@endsection