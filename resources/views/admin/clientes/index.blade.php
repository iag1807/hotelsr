@extends('admin.layouts.app')
@section('titulo', 'Huéspedes')

@section('contenido')

<div class="panel">
  <div class="facturas-header">
    <h1>HUÉSPEDES</h1>
    <p>Clientes registrados del hotel</p>
    <a href="{{ route('admin.clientes.create') }}" class="panel-action btn-descargar">
      Agregar huésped
    </a>
  </div>

  <div class="tabla-wrapper">
    <table>
      <thead>
        <tr>
          <th>Documento</th>
          <th>Tipo doc.</th>
          <th>Nombre</th>
          <th>Correo</th>
          <th>Género</th>
          <th>Celular</th>
          <th>Rol</th>
          <th>Estado</th>
          <th>Acciones</th>
        </tr>
      </thead>
      <tbody>
        @forelse($clientes as $c)
          <tr>
            <td>{{ $c->documento }}</td>
            <td>{{ $c->tipo_documento }}</td>
            <td>{{ $c->name }}</td>
            <td>{{ $c->email }}</td>
            <td>{{ ucfirst($c->genero) }}</td>
            <td>{{ $c->celular }}</td>
            <td>{{ ucfirst($c->rol) }}</td>
            <td>
              <span class="badge {{ $c->estado === 'activo' ? 'badge-confirmada' : 'badge-cancelada' }}">
                {{ ucfirst($c->estado) }}
              </span>
            </td>
            <td>
              <a class="badge badge-confirmada"
                 href="{{ route('admin.clientes.edit', $c->id) }}">Editar</a>

              <form method="POST" action="{{ route('admin.clientes.estado', $c->id) }}" style="display:inline;">
                @csrf @method('PATCH')
                <button type="submit"
                        class="badge {{ $c->estado === 'activo' ? 'badge-cancelada' : 'badge-confirmada' }}"
                        style="border:none; cursor:pointer;">
                  {{ $c->estado === 'activo' ? 'Desactivar' : 'Activar' }}
                </button>
              </form>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="9" style="text-align:center; padding:2rem; opacity:.4;">
              No hay usuarios registrados.
            </td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>

@endsection
