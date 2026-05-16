<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>@yield('titulo', 'Panel Cliente') — Hotel Sueño Real</title>
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Cal+Sans&display=swap" rel="stylesheet">
  

  <link rel="stylesheet" href="{{ asset('css/style-clientes.css') }}">
  <link rel="shortcut icon" href="{{ asset('images/logo.png') }}">

  @stack('styles')
</head>
<body>

{{-- ═══════════════ SIDEBAR ═══════════════ --}}
<aside class="sidebar">
  <div class="sidebar-logo">
    <img src="{{ asset('images/logo.png') }}" alt="Hotel Sueño Real" class="logo-img">
  </div>

  <ul class="nav-links">
    <li>
      <a href="{{ route('cliente.dashboard') }}"
         @class(['active' => request()->routeIs('cliente.dashboard')])>
        <span class="icon">⌂</span> Inicio
      </a>
    </li>
    <li>
      <a href="{{ route('cliente.reservas') }}"
         @class(['active' => request()->routeIs('cliente.reservas')])>
        <span class="icon">⁘</span> Mis reservas
      </a>
    </li>
    <li>
      <a href="{{ route('cliente.facturas') }}"
         @class(['active' => request()->routeIs('cliente.facturas')])>
        <span class="icon">◳</span> Mis facturas
      </a>
    </li>
    <li>
      <a href="{{ route('cliente.perfil') }}"
         @class(['active' => request()->routeIs('cliente.perfil')])>
        <span class="icon">Ω</span> Mi perfil
      </a>
    </li>
  </ul>

  <div class="sidebar-bottom">
    <form method="POST" action="{{ route('logout') }}">
      @csrf
      <button type="submit" class="logout-btn">
        <span>⏻</span> Cerrar sesión
      </button>
    </form>
  </div>
</aside>

{{-- ═══════════════ MAIN ═══════════════ --}}
<main class="main">

  <nav class="topnav">
    <div class="topnav-right">
      <span class="topnav-date" id="fecha-topnav"></span>
    </div>
  </nav>

  {{-- Alertas flash --}}
  @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
  @endif
  @if(session('error'))
    <div class="alert alert-error">{{ session('error') }}</div>
  @endif

  @yield('contenido')

</main>

<script>
  document.getElementById('fecha-topnav').textContent =
    new Date().toLocaleDateString('es-ES', { year:'numeric', month:'long', day:'numeric' });
</script>

@stack('scripts')
</body>
</html>