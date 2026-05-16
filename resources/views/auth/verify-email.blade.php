<x-guest-layout>
    <link
        href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@400;600&family=Cal+Sans&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/registros.css') }}">

    <div class="card2">

        {{-- Cabecera --}}
        <div class="card-header">
            <div>
                <div class="header-title">Verifica tu correo</div>
                <div class="header-sub">Un paso más antes de comenzar</div>
            </div>
        </div>

        <div class="card-body">

            {{-- Mensaje informativo --}}
            <div class="errors-box" style="background:rgba(201,160,46,0.08); border-color:rgba(201,160,46,0.3); border-left-color:#c9a84c;">
                <p style="color:#7a6530;">
                    Gracias por registrarte. Por favor verifica tu correo electrónico haciendo clic en el enlace que te enviamos. Si no lo recibiste, podemos enviarte uno nuevo.
                </p>
            </div>

            {{-- Confirmación de reenvío --}}
            @if (session('status') == 'verification-link-sent')
                <div class="errors-box" style="background:rgba(46,160,78,0.08); border-color:rgba(46,160,78,0.3); border-left-color:#2ea04e; margin-top:1rem;">
                    <p style="color:#2ea04e;">
                        Se ha enviado un nuevo enlace de verificación a tu correo electrónico.
                    </p>
                </div>
            @endif

            {{-- Footer --}}
            <div class="card-footer" style="margin:1.8rem -2rem -1.8rem; padding-left:2rem; padding-right:2rem;">

                {{-- Cerrar sesión --}}
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="btn btn-back">Cerrar sesión</button>
                </form>

                {{-- Reenviar verificación --}}
                <form method="POST" action="{{ route('verification.send') }}">
                    @csrf
                    <div class="footer-actions">
                        <button type="submit" class="btn btn-submit"><span>Reenviar enlace</span></button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-guest-layout>