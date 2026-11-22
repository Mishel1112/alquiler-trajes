<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión - Creaciones y Fantasías Erlinda</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
</head>
<body>
    <div class="login-bg">
        <div class="login-card">
            <div class="login-brand">
                <div class="brand-icon">✂️</div>
                <h1>Creaciones y Fantasías</h1>
                <p>Erlinda</p>
            </div>

            @if ($errors->any())
                <div class="login-error">
                    <span>⚠️</span> {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="/login" class="login-form">
                @csrf

                <div class="form-field">
                    <label for="email">Correo electrónico</label>
                    <div class="field-wrapper">
                        <span class="field-icon">📧</span>
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

                <div class="form-field">
                    <label for="password">Contraseña</label>
                    <div class="field-wrapper">
                        <span class="field-icon">🔒</span>
                        <input
                            id="password"
                            type="password"
                            name="password"
                            placeholder="••••••••"
                            required
                        >
                    </div>
                </div>

                <button type="submit" class="login-btn">
                    Iniciar Sesión
                </button>
            </form>

            <p class="login-footer">Sistema de Gestión de Alquileres</p>
        </div>
    </div>
</body>
</html>
