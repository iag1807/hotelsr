<x-guest-layout>
    <link
        href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@400;600&family=Cal+Sans&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/registros.css') }}">

    <div class="card2">

        {{-- Cabecera --}}
        <div class="card-header">
            <div>
                <div class="header-title">Restablecer contraseña</div>
                <div class="header-sub">Ingresa y confirma tu nueva contraseña</div>
            </div>
        </div>

        <div class="card-body">

            {{-- Errores --}}
            @if ($errors->any())
                <div class="errors-box">
                    <div class="errors-title">⚠ Errores</div>
                    @foreach ($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <form method="POST" action="{{ route('password.store') }}">
                @csrf

                {{-- Token oculto --}}
                <input type="hidden" name="token" value="{{ $request->route('token') }}">

                {{-- Correo --}}
                <div class="form-section">
                    <div class="field2">
                        <label for="email">Correo electrónico</label>
                        <input type="email" name="email" id="email"
                               value="{{ old('email', $request->email) }}"
                               required autofocus autocomplete="username">
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>
                </div>

                {{-- Nueva contraseña + Confirmar --}}
                <div class="form-section">
                    <div class="form-grid col-2">
                        <div class="field">
                            <label for="password">Nueva contraseña</label>
                            <input type="password" name="password" id="password"
                                   required autocomplete="new-password">
                            <x-input-error :messages="$errors->get('password')" class="mt-2" />
                        </div>
                        <div class="field">
                            <label for="password_confirmation">Confirmar contraseña</label>
                            <input type="password" name="password_confirmation" id="password_confirmation"
                                   required autocomplete="new-password">
                            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                        </div>
                    </div>
                </div>

                {{-- Footer --}}
                <div class="card-footer" style="margin:1.8rem -2rem -1.8rem; padding-left:2rem; padding-right:2rem;">
                    <p class="footer-note">
                        ¿Ya recordaste? →
                        <a href="{{ route('login') }}">Iniciar sesión</a>
                    </p>
                    <div class="footer-actions">
                        <a href="{{ route('login') }}" class="boton boton-back">Volver</a>
                        <button type="submit" class="boton boton-submit"><span>Restablecer</span></button>
                    </div>
                </div>

            </form>
        </div>
    </div>
</x-guest-layout>