<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Hotel Sueño Real</title>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@400;600&family=Cal+Sans&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/style-clientes.css') }}">
</head>
<body>

{{-- ── Backdrop sidebar (mobile) ── --}}
<div class="sidebar-backdrop" id="sidebar-backdrop"></div>

{{-- ── Botón hamburguesa (mobile/tablet) ── --}}
<button class="hamburger-cliente" id="hamburger-cliente" aria-label="Abrir menú">
    <span></span>
    <span></span>
    <span></span>
</button>

{{-- ══ Sidebar ═══════════════════════════════════════════════════════════════ --}}
<aside class="sidebar" id="sidebar">
    <div class="sidebar-logo">
        <img class="logo-img" src="{{ asset('images/logo.png') }}" alt="Hotel Sueño Real">
    </div>

    <ul class="nav-links">
        <li>
            <a href="{{ route('cliente.dashboard') }}"
               class="{{ request()->routeIs('cliente.dashboard') ? 'active' : '' }}">
                <span class="icon">⌂</span> Inicio
            </a>
        </li>
        <li>
            <a href="{{ route('cliente.reservas') }}"
               class="{{ request()->routeIs('cliente.reservas') ? 'active' : '' }}">
                <span class="icon">⁘</span> Mis Reservas
            </a>
        </li>
        <li>
            <a href="{{ route('cliente.facturas') }}"
               class="{{ request()->routeIs('cliente.facturas*') ? 'active' : '' }}">
                <span class="icon">◳</span> Mis Facturas
            </a>
        </li>
        <li>
            <a href="{{ route('cliente.perfil') }}"
               class="{{ request()->routeIs('cliente.perfil') ? 'active' : '' }}">
                <span class="icon">Ω</span> Mi Perfil
            </a>
        </li>
    </ul>

    <div class="sidebar-bottom">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="logout-btn">
                <span class="icon">⏻</span> Cerrar Sesión
            </button>
        </form>
    </div>
</aside>

{{-- ══ Main ═══════════════════════════════════════════════════════════════════ --}}
<div class="main">

    {{-- Top nav --}}
    <nav class="topnav">
        <span class="topnav-date">
            {{ now()->locale('es')->isoFormat('D [DE] MMMM [DE] YYYY') }}
        </span>
    </nav>

    {{-- Contenido de cada página --}}
    <div class="content">
        @yield('contenido')
    </div>

</div>

<script>
    const hamburger = document.getElementById('hamburger-cliente');
    const sidebar   = document.getElementById('sidebar');
    const backdrop  = document.getElementById('sidebar-backdrop');

    function openSidebar() {
        sidebar.classList.add('open');
        backdrop.classList.add('open');
        hamburger.classList.add('is-open');
        document.body.style.overflow = 'hidden';
    }

    function closeSidebar() {
        sidebar.classList.remove('open');
        backdrop.classList.remove('open');
        hamburger.classList.remove('is-open');
        document.body.style.overflow = '';
    }

    hamburger.addEventListener('click', () => {
        sidebar.classList.contains('open') ? closeSidebar() : openSidebar();
    });

    backdrop.addEventListener('click', closeSidebar);

    // Cerrar al navegar (click en un link del sidebar)
    sidebar.querySelectorAll('a').forEach(link => {
        link.addEventListener('click', closeSidebar);
    });
</script>

@stack('scripts')

</body>
</html>