@extends('cliente.layouts.app')
@section('titulo', 'Mi Perfil')

@section('contenido')

<div class="facturas-header">
  <h1>MI PERFIL</h1>
  <p>Aquí podrás editar la información de tu perfil y cambiar la contraseña</p>
</div>

<section class="perfil-grid">

  {{-- ══ Datos personales ═══════════════════════════════════════════════ --}}
  <div class="perfil-1">
    <div class="perfil-2">
      <div class="perfil-icon">
        <img src="{{ asset('images/icono-usuario.png') }}" alt="">
      </div>
      <div>
        <label class="perfil-name">{{ $user->name }}</label>
        <div class="perfil-name2">Huésped</div>
      </div>
    </div>

    <form method="POST" action="{{ route('cliente.perfil.actualizar') }}">
      @csrf
      @method('PUT')

      <div class="form-group">
        <label class="form-label">Documento de Identidad</label>
        <input type="text" class="form-input"
               value="{{ $user->tipo_documento }} {{ $user->documento }} - No editable"
               disabled>
      </div>

      <div class="form-group">
        <label class="form-label">Nombre completo</label>
        <input type="text" class="form-input" name="name"
               value="{{ old('name', $user->name) }}">
        @error('name')
          <span class="form-error">{{ $message }}</span>
        @enderror
      </div>

      <div class="form-group">
        <label class="form-label">Correo Electrónico</label>
        <input type="email" class="form-input" name="email"
               value="{{ old('email', $user->email) }}">
        @error('email')
          <span class="form-error">{{ $message }}</span>
        @enderror
      </div>

      <div class="form-group">
        <label class="form-label">Celular</label>
        <input type="tel" class="form-input" name="celular"
               value="{{ old('celular', $user->celular) }}">
        @error('celular')
          <span class="form-error">{{ $message }}</span>
        @enderror
      </div>

      @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
      @endif

      <button type="submit" name="actualizar_datos" class="btn btn-outline" style="margin-top:8px;">
        Guardar Cambios
      </button>
    </form>
  </div>

  {{-- ══ Cambiar contraseña ═════════════════════════════════════════════ --}}
  <div class="perfil-3">
    <div class="perfil-4">
      <div class="perfil-5">Cambiar Contraseña</div>

      <form method="POST" action="{{ route('cliente.perfil.contrasena') }}">
        @csrf
        @method('PUT')

        <div class="form-group">
          <label class="form-label">Contraseña Actual</label>
          <input type="password" name="actual" class="form-input" placeholder="••••••••">
          @error('actual')
            <span class="form-error">{{ $message }}</span>
          @enderror
        </div>

        <div class="form-group">
          <label class="form-label">Nueva Contraseña</label>
          <input type="password" name="nueva" class="form-input" placeholder="••••••••">
          @error('nueva')
            <span class="form-error">{{ $message }}</span>
          @enderror
        </div>

        <div class="form-group">
          <label class="form-label">Confirmar Contraseña</label>
          <input type="password" name="nueva_confirmation" class="form-input" placeholder="••••••••">
        </div>

        <button type="submit" name="cambiar_password" class="btn btn-outline">
          Actualizar
        </button>

      </form>
    </div>
  </div>

</section>

@endsection