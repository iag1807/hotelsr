<x-guest-layout>
    {{-- ── Estilos propios ──────────────────────────────────────────── --}}
    <link
        href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@400;600&family=Cal+Sans&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/registros.css') }}">

    {{-- ── Override: quitar el wrapper de Breeze y centrar con nuestro .card ── --}}
    <div class="card">

        {{-- Cabecera --}}
        <div class="card-header">
            <div>
                <div class="header-title">Crear cuenta</div>
                <div class="header-sub">Regístrate para disfrutar de los servicios del hotel</div>
            </div>
        </div>

        <div class="card-body">

            {{-- Errores de validación --}}
            @if ($errors->any())
                <div class="errors-box">
                    <div class="errors-title">⚠ Errores</div>
                    @foreach ($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <form method="POST" action="{{ route('register') }}">
                @csrf

                {{-- Tipo de documento + Número de documento --}}
                <div class="form-section">
                    <div class="form-grid col-2">
                        <div class="field">
                            <label for="tipo_documento">Tipo de documento</label>
                            <select name="tipo_documento" id="tipo_documento">
                                <option value="">Seleccione…</option>
                                <option value="CC" {{ old('tipo_documento') === 'CC' ? 'selected' : '' }}>C.C — Cédula de ciudadanía</option>
                                <option value="CE" {{ old('tipo_documento') === 'CE' ? 'selected' : '' }}>C.E — Cédula de extranjería</option>
                                <option value="TI" {{ old('tipo_documento') === 'TI' ? 'selected' : '' }}>T.I — Tarjeta de identidad</option>
                                <option value="PAS" {{ old('tipo_documento') === 'PAS' ? 'selected' : '' }}>P.A.S — Pasaporte</option>
                            </select>
                            <x-input-error :messages="$errors->get('tipo_documento')" class="mt-2" />
                        </div>
                        <div class="field">
                            <label for="documento">Número de documento</label>
                            <input type="text" name="documento" id="documento" value="{{ old('documento') }}">
                            <x-input-error :messages="$errors->get('documento')" class="mt-2" />
                        </div>
                    </div>
                </div>

                {{-- Nombre + Apellidos + Correo + Contraseña --}}
                <div class="form-section">
                    <div class="form-grid col-2">
                        <div class="field">
                            <label for="name">Nombre Completo</label>
                            <input type="text" name="name" id="name" value="{{ old('name') }}" required autofocus autocomplete="name">
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>
                        <div class="field">
                            <label for="email">Correo electrónico</label>
                            <input type="email" name="email" id="email" value="{{ old('email') }}" required autocomplete="username">
                            <x-input-error :messages="$errors->get('email')" class="mt-2" />
                        </div>
                    </div>

                    <div class="form-grid col-2" style="margin-top:1rem">
                        <div class="field">
                            <label for="password">Contraseña</label>
                            <input type="password" name="password" id="password" required autocomplete="new-password">
                            <x-input-error :messages="$errors->get('password')" class="mt-2" />
                        </div>
                         <div class="field">
                            <label for="password_confirmation">Confirmar contraseña</label>
                            <input type="password" name="password_confirmation" id="password_confirmation" required autocomplete="new-password">
                            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                        </div>
                    </div>
                </div>

                {{-- Confirmar contraseña + Género + Celular --}}
                <div class="form-section">
                    <div class="form-grid col-2">
                       
                        <div class="field">
                            <label for="genero">Género</label>
                            <select name="genero" id="genero">
                                <option value="">Seleccione…</option>
                                <option value="masculino" {{ old('genero') === 'masculino' ? 'selected' : '' }}>Masculino</option>
                                <option value="femenino"  {{ old('genero')  === 'femenino'  ? 'selected' : '' }}>Femenino</option>
                            </select>
                            <x-input-error :messages="$errors->get('genero')" class="mt-2" />
                        </div>

                        <div class="field">
                            <label for="celular">Celular</label>
                            <input type="text" name="celular" id="celular" value="{{ old('celular') }}">
                            <x-input-error :messages="$errors->get('celular')" class="mt-2" />
                        </div>
                    </div>
                </div>

                {{-- Footer: enlace login + acciones --}}
                <div class="card-footer">
                    <p class="footer-note">
                        ¿Ya tienes cuenta? →
                        <a href="{{ route('login') }}">Iniciar sesión</a>
                    </p>
                    <div class="footer-actions">
                        <a href="{{ url('/') }}" class="boton boton-back">Volver</a>
                        <button type="submit" class="boton boton-submit">
                            <span>Crear cuenta</span>
                        </button>
                    </div>
                </div>

            </form>
        </div>
    </div>
</x-guest-layout>