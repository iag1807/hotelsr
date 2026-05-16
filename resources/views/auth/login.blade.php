<x-guest-layout>
    <link
        href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@400;600&family=Cal+Sans&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/registros.css') }}">

    <div class="card2">

        {{-- Cabecera --}}
        <div class="card-header">
            <div>
                <div class="header-title">Iniciar sesión</div>
                <div class="header-sub">Ingresa para reservar una habitación</div>
            </div>
        </div>

        <div class="card-body">

            {{-- Estado de sesión (ej: "Enlace de recuperación enviado") --}}
            <x-auth-session-status class="mb-4" :status="session('status')" />

            {{-- Errores generales --}}
            @if ($errors->any())
                <div class="errors-box">
                    <div class="errors-title">⚠ Errores</div>
                    @foreach ($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf

                {{-- Correo --}}
                <div class="form-section">
                    <div class="field2">
                        <label for="email">Correo</label>
                        <input type="email" name="email" id="email"
                               value="{{ old('email') }}"
                               required autofocus autocomplete="username">
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>
                </div>

                {{-- Contraseña --}}
                <div class="form-section">
                    <div class="field2">
                        <label for="password">Contraseña</label>
                        <input type="password" name="password" id="password"
                               required autocomplete="current-password">
                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>
                </div>

                {{-- Footer --}}
                <div class="card-footer" style="margin:1.8rem -2rem -1.8rem; padding-left:2rem; padding-right:2rem;">
                    <p class="footer-note">
                        ¿Olvidaste tu contraseña? →
                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}">Recuperar</a>
                        @endif
                    </p>
                    <div class="footer-actions">
                        <a href="{{ url('/') }}" class="boton boton-back">Volver</a>
                        <button type="submit" class="boton boton-submit"><span>Ingresar</span></button>
                    </div>
                </div>

            </form>
        </div>
    </div>
</x-guest-layout>