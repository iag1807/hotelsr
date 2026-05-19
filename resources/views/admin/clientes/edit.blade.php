@extends('admin.layouts.app')
@section('titulo', 'Editar Cliente')

@section('contenido')

<div class="form-wrapper">
<div class="card">
  <div class="card-header">
    <div>
      <div class="header-title">Editar Cliente</div>
      <div class="header-sub">Modificar datos del usuario {{ $cliente->name }}</div>
    </div>
  </div>

  <div class="card-body">

    @if($errors->any())
      <div class="errors-box">
        <div class="errors-title">⚠ Errores</div>
        @foreach($errors->all() as $e) <p>{{ $e }}</p> @endforeach
      </div>
    @endif

    <form method="POST" action="{{ route('admin.clientes.update', $cliente->id) }}">
      @csrf
      @method('PUT')

      <div class="form-section">
        <div class="form-grid col-2">
          <div class="field">
            <label>Nombre</label>
            <input type="text" name="name"
                   value="{{ old('name', $cliente->name) }}" required>
          </div>
          <div class="field">
            <label>Email</label>
            <input type="email" name="email"
                   value="{{ old('email', $cliente->email) }}" required>
          </div>
        </div>
      </div>

      <div class="form-section">
        <div class="form-grid col-2">
          <div class="field">
            <label>Teléfono</label>
            <input type="text" name="celular"
                   value="{{ old('celular', $cliente->celular) }}">
          </div>
          <div class="field">
            <label>Estado</label>
            <select name="estado" required>
              <option value="activo"   {{ old('estado', $cliente->estado) === 'activo'   ? 'selected' : '' }}>Activo</option>
              <option value="inactivo" {{ old('estado', $cliente->estado) === 'inactivo' ? 'selected' : '' }}>Inactivo</option>
            </select>
          </div>
        </div>
      </div>

      <div class="card-footer">
        <span class="footer-note"><span>*</span> Campos obligatorios</span>
        <div class="footer-actions">
          <a href="{{ route('admin.clientes') }}" class="btn btn-back">Volver</a>
          <button type="submit" class="btn btn-submit">Actualizar cliente</button>
        </div>
      </div>

    </form>
  </div>
</div>
</div>

@endsection