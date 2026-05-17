<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Hotel Sueño Real</title>
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Cal+Sans&display=swap" rel="stylesheet">

  <link rel="stylesheet" href="{{ asset('css/style-admin.css') }}">

  <link rel="shortcut icon" href="{{ asset('images/logo.png') }}">

  @stack('styles')
</head>
<body>

<aside class="sidebar">
  <div class="sidebar-logo">
    <img src="{{ asset('images/logo.png') }}" alt="Hotel Sueño Real" class="logo-img">
  </div>

  <ul class="nav-links">
    <li>
      <a href="{{ route('admin.dashboard') }}"
         @class(['active' => request()->routeIs('admin.dashboard')])>
        <span class="icon">⌂</span> Inicio
      </a>
    </li>
    <li>
      <a href="{{ route('admin.checks') }}"
         @class(['active' => request()->routeIs('admin.checks*')])>
        <span class="icon">↻</span> Ingresos
      </a>
    </li>
    <li>
      <a href="{{ route('admin.reservas') }}"
         @class(['active' => request()->routeIs('admin.reservas*')])>
        <span class="icon">◻</span> Reservaciones
      </a>
    </li>
    <li>
      <a href="{{ route('admin.habitaciones') }}"
         @class(['active' => request()->routeIs('admin.habitaciones*')])>
        <span class="icon">◫</span> Habitaciones
      </a>
    </li>
    <li>
      <a href="{{ route('admin.clientes') }}"
         @class(['active' => request()->routeIs('admin.clientes*')])>
        <span class="icon">◈</span> Huéspedes
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

<main class="main">

  <nav class="topnav">
    <div class="topnav-right">
      <span class="topnav-date" id="fecha-topnav"></span>
    </div>
  </nav>

  @if(session('success'))
    <div class="alert-flash alert-flash-ok">{{ session('success') }}</div>
  @endif
  @if(session('error'))
    <div class="alert-flash alert-flash-err">{{ session('error') }}</div>
  @endif

  @yield('contenido')

</main>

<script>
  document.getElementById('fecha-topnav').textContent =
    new Date().toLocaleDateString('es-ES', { year:'numeric', month:'long', day:'numeric' }).toUpperCase();
</script>

@stack('scripts')
</body>
</html>
