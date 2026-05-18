@extends('cliente.layouts.app')
@section('titulo', 'Inicio')

@section('contenido')

{{-- ══ Hero ══════════════════════════════════════════════════════════════════ --}}
<section class="hero">
  <div class="hero-bg"></div>
  <div class="hero-overlay"></div>
  <div class="hero-content">
    <div class="hero-text">
      <h2>{{ $user->saludo() }}<br>
        <em>{{ $user->name }}</em>
      </h2>
    </div>
  </div>
</section>

{{-- ══ Buscador ═══════════════════════════════════════════════════════════════ --}}
<h1 class="section-title">BUSCAR DISPONIBILIDAD</h1>

@if($errors->any())
  <div class="alert alert-error" style="margin: 0 32px 4px;">
    @foreach($errors->all() as $err) <p>{{ $err }}</p> @endforeach
  </div>
@endif

<form id="form-busqueda" method="GET" action="{{ route('cliente.dashboard') }}">
  <div class="buscador-hotel">

    <div class="campo" id="campo-ingreso">
      <label>Fecha de entrada</label>
      <div class="date-display {{ $entrada ? '' : 'empty' }}" id="display-ingreso">
        <span id="txt-ingreso">
          {{ $entrada ? \Carbon\Carbon::parse($entrada)->format('d/m/Y') : 'dd/mm/aaaa' }}
        </span>
      </div>
      <input type="hidden" id="fecha_ingreso" name="entrada" value="{{ $entrada ?? '' }}" required>
      <div class="cal-popup" id="cal-ingreso"></div>
    </div>

    <div class="linea"></div>

    <div class="campo" id="campo-salida">
      <label>Fecha de salida</label>
      <div class="date-display {{ $salida ? '' : 'empty' }}" id="display-salida">
        <span id="txt-salida">
          {{ $salida ? \Carbon\Carbon::parse($salida)->format('d/m/Y') : 'dd/mm/aaaa' }}
        </span>
      </div>
      <input type="hidden" id="fecha_salida" name="salida" value="{{ $salida ?? '' }}" required>
      <div class="cal-popup" id="cal-salida"></div>
    </div>

    <div class="linea"></div>

    <div class="campo">
      <label>Número de personas</label>
      <input type="number" id="numero_personas" name="personas"
             placeholder="Huéspedes" min="1" max="10"
             value="{{ $personas ?? '' }}" required>
    </div>

    <div class="linea"></div>
    <button type="submit" class="btn-buscar2">Buscar</button>
  </div>
</form>

{{-- ══ Tarjetas ════════════════════════════════════════════════════════════════ --}}
<p class="resultado-busqueda">HABITACIONES DISPONIBLES</p>

@php
$minimos = [
  'sencilla' => 1, 'bañera' => 1,  'jacuzzi' => 1,
  'doble'    => 2, 'triple' => 3,  'multiple' => 5,
];
$tarjetas = [
  'sencilla' => ['img'=>'sencilla.jpeg',  'nombre'=>'Sencilla',  'desc'=>'Cama semidoble, baño privado, televisor.',                          'cap'=>'1 a 2 personas',  'precio'=>'Desde $50.000'],
  'bañera'   => ['img'=>'bañera.jpeg',    'nombre'=>'Bañera',    'desc'=>'Cama semidoble, baño privado, bañera, televisor.',                  'cap'=>'1 a 2 personas',  'precio'=>'$130.000'],
  'jacuzzi'  => ['img'=>'jacuzzi.jpeg',   'nombre'=>'Jacuzzi',   'desc'=>'Cama semidoble, baño privado, jacuzzi, televisor.',                 'cap'=>'1 a 2 personas',  'precio'=>'$160.000'],
  'doble'    => ['img'=>'doble.jpeg',     'nombre'=>'Doble',     'desc'=>'Dos camas semidobles, baño privado, televisor.',                    'cap'=>'2 a 4 personas',  'precio'=>'$110.000'],
  'triple'   => ['img'=>'triple.jpeg',    'nombre'=>'Triple',    'desc'=>'Una cama semidoble, un camarote, baño privado, televisor.',         'cap'=>'3 a 6 personas',  'precio'=>'$130.000'],
  'multiple' => ['img'=>'multiple.jpeg',  'nombre'=>'Múltiple',  'desc'=>'Dos camarotes, una cama de un metro, baño privado, televisor.',     'cap'=>'5 a 10 personas', 'precio'=>'$160.000'],
];
@endphp

<section class="habitaciones-section">
  <div class="habitaciones-grid">
    @foreach($tarjetas as $key => $info)
      @php
        $capMax  = $capacidades[$key] ?? 999;
        $capMin  = $minimos[$key];
        $mostrar = !$personas || ($personas >= $capMin && $personas <= $capMax);
      @endphp

      @if($mostrar)
        <div class="habitacion-card">
          <img class="habitacion-imagen"
               src="{{ asset('images/' . $info['img']) }}"
               alt="{{ $info['nombre'] }}">
          <div class="habitacion-content">
            <h3 class="habitacion-nombre">{{ $info['nombre'] }}</h3>
            <p class="habitacion-detalles">{{ $info['desc'] }}</p>
            <p class="habitacion-detalles">Capacidad: {{ $info['cap'] }}</p>
            <p class="habitacion-detalles">Precio: {{ $info['precio'] }} por noche</p>

            @if($busquedaRealizada)
              <p class="habitacion-detalles" style="margin-top:10px;">Disponibles:</p>
              @if(empty($habitacionesDisponibles[$key]))
                <p class="habitacion-detalles sin-disponibles">No hay habitaciones disponibles</p>
              @else
                <select class="habitacion-select" onchange="abrirModalReserva(this.value)">
                  <option value="">Seleccionar habitación</option>
                  @foreach($habitacionesDisponibles[$key] as $hab)
                    <option value="{{ $hab['id'] }}">N° {{ $hab['numero'] }}</option>
                  @endforeach
                </select>
              @endif
            @endif
          </div>
        </div>
      @endif
    @endforeach
  </div>
</section>

{{-- ══ Modal de reserva ════════════════════════════════════════════════════════ --}}
<div id="modal-reserva" class="modal-overlay" style="display:none;">
  <div class="modal-container">

    <button class="modal-close" onclick="cerrarModal()">&#10005;</button>

    <div class="modal-header">
      <h2 class="modal-titulo">Confirmar Reserva</h2>
      <p class="modal-subtitulo">Hotel Sueño Real</p>
    </div>

    {{-- Resumen --}}
    <div class="modal-resumen" id="bloque-resumen">
      <h3 class="resumen-title">Resumen</h3>
      <div class="resumen-grid">
        <div class="resumen-item"><span class="resumen-label">Habitación</span><span class="resumen-valor" id="res-tipo">-</span></div>
        <div class="resumen-item"><span class="resumen-label">N° habitación</span><span class="resumen-valor" id="res-numero">-</span></div>
        <div class="resumen-item"><span class="resumen-label">Noches</span><span class="resumen-valor" id="res-noches">-</span></div>
        <div class="resumen-item"><span class="resumen-label">Entrada</span><span class="resumen-valor" id="res-entrada">-</span></div>
        <div class="resumen-item"><span class="resumen-label">Salida</span><span class="resumen-valor" id="res-salida">-</span></div>
        <div class="resumen-item"><span class="resumen-label">Huéspedes</span><span class="resumen-valor" id="res-personas">-</span></div>
      </div>
      <div class="resumen-totales">
        <div class="total-row"><span>Precio por noche</span><span id="res-precio-noche">-</span></div>
        <div class="total-row total-full"><span>Total estadía</span><span id="res-total">-</span></div>
        <div class="total-row total-anticipo highlight"><span>Anticipo a pagar (50%)</span><span id="res-anticipo">-</span></div>
      </div>
      <div class="info-pago-nota">
        <h3 class="resumen-title">Ten en cuenta</h3>
        • Solo debes pagar el <strong>50%</strong> para reservar la habitación.<br>
        • Una vez revisado el comprobante se confirmará tu reserva.<br>
        • El saldo restante se cancela al momento del ingreso al hotel.
      </div>
    </div>

    {{-- Pago --}}
    <div class="modal-pago" id="bloque-pago">
      <h3 class="resumen-title">Pago del anticipo</h3>
      <div class="pago-grid">

        <div class="pago-card">
          <div class="pago-card-header">
            <span class="pago-badge bancolombia">Bancolombia</span>
            <p class="pago-instruccion">Escanea el QR o usa los datos para la transferencia</p>
            <p class="pago-instruccion"><strong>Nombre:</strong> Martha Cecilia Carvajal Bran</p>
            <p class="pago-instruccion"><strong>Cuenta:</strong> 64759538917 — Ahorros</p>
          </div>
          <div class="qr-container">
            <img src="{{ asset('images/qr.jpeg') }}" alt="QR Bancolombia" class="qr-img"
                 onerror="this.style.display='none'">
          </div>
          <p class="pago-monto-label">Monto a transferir: <strong id="qr-monto">-</strong></p>
        </div>

        <div class="pago-card">
          <div class="pago-card-header">
            <span class="pago-badge comprobante">Comprobante</span>
            <p class="pago-instruccion">Adjunta la foto o captura de pantalla de tu pago</p>
          </div>

          {{-- Input file real — sin JS de conversión a base64 --}}
          <div class="upload-area" id="upload-area"
               onclick="document.getElementById('comprobante-input').click()">
            <div class="upload-icon">📎</div>
            <p class="upload-text">Toca para subir el comprobante</p>
            {{-- accept restringe en el navegador, mimes en el servidor valida el contenido real --}}
            <input type="file" id="comprobante-input"
                   accept=".jpg,.jpeg,.png,.pdf"
                   style="display:none;"
                   onchange="previsualizarComprobante(event)">
          </div>

          <div id="preview-container" style="display:none; flex-direction:column; gap:10px;">
            <img id="preview-img" class="preview-img" alt="Vista previa">
            <p id="preview-nombre" style="font-size:.78rem;color:var(--gris-claro);text-align:center;"></p>
            <button class="btn-cambiar-archivo" type="button"
                    onclick="document.getElementById('comprobante-input').click()">
              Cambiar archivo
            </button>
          </div>
        </div>

      </div>
    </div>

    <div class="modal-footer" id="bloque-footer">
      <button type="button" class="btn-confirmar" id="btn-confirmar" onclick="confirmarReserva()">
        <span id="btn-texto">Confirmar Reserva</span>
        <span id="btn-loading" style="display:none;">Procesando...</span>
      </button>
      <button type="button" class="btn-cancelar" onclick="cerrarModal()">Cancelar</button>
    </div>

    <div id="reserva-exitosa" class="reserva-exitosa" style="display:none;">
      <h3>¡Reserva registrada! ✓</h3>
      <p>Tu reserva ha sido registrada exitosamente.<br>
         Revisaremos el comprobante y confirmaremos tu reserva pronto.<br>
         Recuerda presentarte con tu documento de identidad al hacer el check-in.</p>
      <button class="btn-reservas" type="button"
              onclick="window.location='{{ route('cliente.reservas') }}'">
        Ver mis reservas
      </button>
      <button class="btn-reservas" type="button"
              onclick="window.location='{{ route('cliente.facturas') }}'">
        Ver mi factura
      </button>
    </div>

  </div>
</div>

@endsection

@push('scripts')
<script>
  const mapaHabitaciones = @json($mapaHabitaciones);
  const reservaEntrada   = "{{ $entrada ?? '' }}";
  const reservaSalida    = "{{ $salida ?? '' }}";
  const reservaPersonas  = {{ $personas ?? 1 }};
  const csrfToken        = document.querySelector('meta[name="csrf-token"]').content;

  let habitacionSeleccionadaId = null;
  let archivoComprobante       = null;   // ← File object real, no base64

  const fmt = n => '$' + Number(n).toLocaleString('es-CO');

  function formatFecha(iso) {
    if (!iso) return '-';
    const [y, m, d] = iso.split('-');
    return `${d}/${m}/${y}`;
  }

  function calcNoches(e, s) {
    return Math.round((new Date(s + 'T00:00:00') - new Date(e + 'T00:00:00')) / 86400000);
  }

  function abrirModalReserva(idHabitacion) {
    if (!idHabitacion) return;
    const hab = mapaHabitaciones[idHabitacion];
    if (!hab) return;

    habitacionSeleccionadaId = idHabitacion;
    const noches      = calcNoches(reservaEntrada, reservaSalida);
    const extra       = Math.max(0, reservaPersonas - 1);
    const precioNoche = hab.precio + extra * hab.precio_adicional;
    const total       = precioNoche * noches;
    const anticipo    = Math.round(total * 0.5);

    document.getElementById('res-tipo').textContent         = hab.tipo.charAt(0).toUpperCase() + hab.tipo.slice(1);
    document.getElementById('res-numero').textContent       = 'N° ' + hab.numero;
    document.getElementById('res-entrada').textContent      = formatFecha(reservaEntrada);
    document.getElementById('res-salida').textContent       = formatFecha(reservaSalida);
    document.getElementById('res-noches').textContent       = noches + (noches === 1 ? ' noche' : ' noches');
    document.getElementById('res-personas').textContent     = reservaPersonas + (reservaPersonas === 1 ? ' persona' : ' personas');
    document.getElementById('res-precio-noche').textContent = fmt(precioNoche);
    document.getElementById('res-total').textContent        = fmt(total);
    document.getElementById('res-anticipo').textContent     = fmt(anticipo);
    document.getElementById('qr-monto').textContent         = fmt(anticipo);

    // Reset comprobante y estado modal
    archivoComprobante = null;
    document.getElementById('comprobante-input').value          = '';
    document.getElementById('preview-container').style.display  = 'none';
    document.getElementById('upload-area').style.display        = 'flex';
    document.getElementById('reserva-exitosa').style.display    = 'none';
    document.getElementById('bloque-resumen').style.display     = 'block';
    document.getElementById('bloque-pago').style.display        = 'block';
    document.getElementById('bloque-footer').style.display      = 'flex';

    const btn = document.getElementById('btn-confirmar');
    btn.disabled = false;
    document.getElementById('btn-texto').style.display   = 'inline';
    document.getElementById('btn-loading').style.display = 'none';

    document.getElementById('modal-reserva').style.display = 'flex';
    document.body.style.overflow = 'hidden';
  }

  function cerrarModal() {
    document.getElementById('modal-reserva').style.display = 'none';
    document.body.style.overflow = '';
    document.querySelectorAll('.habitacion-select').forEach(s => s.value = '');
  }

  document.getElementById('modal-reserva').addEventListener('click', e => {
    if (e.target === document.getElementById('modal-reserva')) cerrarModal();
  });

  // ── Preview del comprobante ─────────────────────────────────────────────────
  // Solo muestra la imagen si es imagen — no convierte a base64 para enviar
  function previsualizarComprobante(event) {
    const file = event.target.files[0];
    if (!file) return;

    // Validar tamaño en el cliente (5MB) — el servidor también lo valida
    if (file.size > 5 * 1024 * 1024) {
      alert('El archivo no puede superar 5MB.');
      event.target.value = '';
      return;
    }

    // Validar tipo en el cliente — el servidor también valida el contenido real
    const tiposPermitidos = ['image/jpeg', 'image/png', 'application/pdf'];
    if (!tiposPermitidos.includes(file.type)) {
      alert('Solo se permiten imágenes JPG, PNG o archivos PDF.');
      event.target.value = '';
      return;
    }

    // Guardar referencia al File object — NO convertir a base64
    archivoComprobante = file;

    const img     = document.getElementById('preview-img');
    const nombre  = document.getElementById('preview-nombre');

    // Solo mostrar preview si es imagen
    if (file.type.startsWith('image/')) {
      img.src = URL.createObjectURL(file);
      img.style.display = 'block';
    } else {
      // PDF: mostrar icono en lugar de imagen
      img.style.display = 'none';
    }

    nombre.textContent = '📄 ' + file.name;
    document.getElementById('preview-container').style.display = 'flex';
    document.getElementById('upload-area').style.display       = 'none';
  }

  // ── Confirmar reserva con FormData ──────────────────────────────────────────
  async function confirmarReserva() {
    if (!archivoComprobante) {
      alert('Por favor adjunta el comprobante de pago antes de confirmar.');
      return;
    }

    const btn     = document.getElementById('btn-confirmar');
    const txtNorm = document.getElementById('btn-texto');
    const txtLoad = document.getElementById('btn-loading');
    btn.disabled = true;
    txtNorm.style.display = 'none';
    txtLoad.style.display = 'inline';

    // FormData envía el archivo real como multipart/form-data
    // El servidor lo recibe con $request->file('comprobante')
    const formData = new FormData();
    formData.append('id_habitacion', habitacionSeleccionadaId);
    formData.append('fecha_ingreso', reservaEntrada);
    formData.append('fecha_salida',  reservaSalida);
    formData.append('num_personas',  reservaPersonas);
    formData.append('comprobante',   archivoComprobante);  // ← archivo real

    try {
      const resp = await fetch('{{ route("cliente.reservas.store") }}', {
        method : 'POST',
        headers: {
          // NO incluir Content-Type — el navegador lo pone automáticamente
          // con el boundary correcto para multipart/form-data
          'X-CSRF-TOKEN': csrfToken,
          'Accept'      : 'application/json',
        },
        body: formData,
      });

      const data = await resp.json();

      if (data.exito) {
        // Limpiar ObjectURL para liberar memoria
        if (archivoComprobante) {
          URL.revokeObjectURL(document.getElementById('preview-img').src);
        }
        document.getElementById('bloque-resumen').style.display  = 'none';
        document.getElementById('bloque-pago').style.display     = 'none';
        document.getElementById('bloque-footer').style.display   = 'none';
        document.getElementById('reserva-exitosa').style.display = 'block';
      } else {
        alert('Error: ' + (data.mensaje || 'Intenta de nuevo.'));
        btn.disabled = false;
        txtNorm.style.display = 'inline';
        txtLoad.style.display = 'none';
      }
    } catch (err) {
      alert('Error de conexión: ' + err.message);
      btn.disabled = false;
      txtNorm.style.display = 'inline';
      txtLoad.style.display = 'none';
    }
  }
</script>
<script src="{{ asset('js/calendario.js') }}"></script>
@endpush
