<x-guest-layout>
    <link
        href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@400;600&family=Cal+Sans&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/registros.css') }}">

    <div class="card2">

        {{-- Cabecera --}}
        <div class="card-header">
            <div>
                <div class="header-title">Confirmar contraseña</div>
                <div class="header-sub">Zona segura — verifica tu identidad para continuar</div>
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

            <form method="POST" action="{{ route('password.confirm') }}">
                @csrf

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
                        ¿No eres tú? →
                        <a href="{{ route('login') }}">Iniciar sesión</a>
                    </p>
                    <div class="footer-actions">
                        <a href="{{ url()->previous() }}" class="btn btn-back">Volver</a>
                        <button type="submit" class="btn btn-submit"><span>Confirmar</span></button>
                    </div>
                </div>

            </form>
        </div>
    </div>
</x-guest-layout>