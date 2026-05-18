<x-guest-layout>
    <link
        href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@400;600&family=Cal+Sans&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/registros.css') }}">

    <div class="card2">

        {{-- Cabecera --}}
        <div class="card-header">
            <div>
                <div class="header-title">Recuperar contraseña</div>
                <div class="header-sub">Te enviaremos un enlace para restablecer tu contraseña</div>
            </div>
        </div>

        <div class="card-body">

            {{-- Estado de sesión (confirmación de envío) --}}
            <x-auth-session-status class="mb-4" :status="session('status')" />

            {{-- Errores --}}
            @if ($errors->any())
                <div class="errors-box">
                    <div class="errors-title">⚠ Errores</div>
                    @foreach ($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <form method="POST" action="{{ route('password.email') }}">
                @csrf

                {{-- Correo --}}
                <div class="form-section">
                    <div class="field2">
                        <label for="email">Correo electrónico</label>
                        <input type="email" name="email" id="email"
                               value="{{ old('email') }}"
                               required autofocus autocomplete="username">
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>
                </div>

                {{-- Footer --}}
                <div class="card-footer">
                    <p class="footer-note">
                        ¿Ya recordaste? →
                        <a href="{{ route('login') }}">Iniciar sesión</a>
                    </p>
                    <div class="footer-actions">
                        <a href="{{ route('login') }}" class="boton boton-back">Volver</a>
                        <button type="submit" class="boton boton-submit"><span>Enviar enlace</span></button>
                    </div>
                </div>

            </form>
        </div>
    </div>
</x-guest-layout>