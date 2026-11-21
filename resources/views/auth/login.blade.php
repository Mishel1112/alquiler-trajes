<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión - Creaciones y Fantasías Erlinda</title>
    
    <!-- Enlace al archivo CSS externo -->
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
</head>
<body>

    <div class="contenedor">

        <!-- LOGOTIPO -->
        <div class="logo">
            <div class="figuras">
                <img src="{{ asset('img/logout.png') }}" alt="Logo" class="logo-imagen">
            </div>
        </div>

        <!-- FORMULARIO -->
        @if ($errors->any())
            <div class="error">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="/login">
            @csrf

            <!-- CORREO ELECTRÓNICO -->
            <div class="form-group">
                <label for="email" class="form-label">Correo:</label>
                <div class="input-wrapper">
                    <span class="icono"></span>
                    <input
                        id="email"
                        type="email"
                        name="email"
                        placeholder="ejemplo@correo.com"
                        value="{{ old('email') }}"
                        required
                        autofocus
                    >
                </div>
            </div>

            <!-- CONTRASEÑA -->
            <div class="form-group">
                <label for="password" class="form-label">Contraseña:</label>
                <div class="input-wrapper">
                    <span class="icono"></span>
                    <input
                        id="password"
                        type="password"
                        name="password"
                        placeholder="••••••••"
                        required
                    >
                </div>
            </div>

            <!-- BOTÓN -->
            <button type="submit" class="btn-login">
                INICIAR SESIÓN
            </button>
        </form>

    </div>

</body>
</html>