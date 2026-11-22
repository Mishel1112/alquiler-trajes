<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ request('modo') == 'alquilar' ? 'Registrar Alquiler' : 'Gestión de Stock' }} - Creaciones y Fantasías Erlinda</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/stock.css') }}?v={{ time() }}">
</head>
<body>

    <!-- BARRA SUPERIOR ESTÁTICA -->
    <header class="topbar">
        <div class="logo-container">
            <span class="logo-text">Creaciones y Fantasías Erlinda</span>
        </div>
        <div class="user-info">
            <span class="user-icon">👤</span>
            <span class="user-role">
                {{ auth()->check() ? trim(auth()->user()->nombre . ' ' . (auth()->user()->apellido ?? '')) : 'Erlinda Pedrazas' }}
            </span>
            <a href="{{ route('dashboard') }}" class="btn-salir" style="background-color: #34495e; margin-right: 10px;">Dashboard</a>
            <form action="{{ route('logout') }}" method="POST" style="display: inline;">
                @csrf
                <button type="submit" class="btn-salir" style="border: none; cursor: pointer;">Cerrar sesión</button>
            </form>
        </div>
    </header>

    <main class="main-content">

        <!-- 1. CAMBIO AQUÍ: BOTÓN PARA VOLVER AL INICIO / DASHBOARD -->
        <div style="margin-bottom: 15px;">
            <a href="{{ route('dashboard') }}" class="btn-salir" style="background-color: #7f8c8d; text-decoration: none; padding: 8px 15px; border-radius: 4px; color: white; display: inline-block; font-weight: bold;">
                ← Volver al Inicio
            </a>
        </div>

        <div class="stock-header">
            <!-- Título dinámico según el modo -->
            <h1>{{ request('modo') == 'alquilar' ? 'Registrar alquiler' : 'Gestión de stock' }}</h1>
            <p>
                @if(request('modo') == 'alquilar')
                    <span style="color: #e74c3c; font-weight: bold;">¿Qué categoría deseas alquilar?</span>
                @else
                    ¿Qué categoría deseas gestionar o registrar?
                @endif
            </p>
        </div>
        <div class="categories-grid">
            <!-- Categoría 1: Trajes Típicos -->
            <a href="{{ route('stock.categoria', ['categoria' => 'tipicos', 'modo' => request('modo')]) }}" class="category-card-link">
                <div class="category-card">
                    <div class="card-img-container">
                        <img src="{{ asset('img/bolivia.jpeg') }}" alt="Trajes Típicos">
                    </div>
                    <h3>TRAJES TÍPICOS</h3>
                </div>
            </a>

            <!-- Categoría 2: Trajes Internacionales -->
            <a href="{{ route('stock.categoria', ['categoria' => 'internacionales', 'modo' => request('modo')]) }}" class="category-card-link">
                <div class="category-card">
                    <div class="card-img-container">
                        <img src="{{ asset('img/internacionales.jpg') }}" alt="Trajes Internacionales">
                    </div>
                    <h3>TRAJES INTERNACIONALES</h3>
                </div>
            </a>

            <!-- Categoría 3: Disfraces -->
            <a href="{{ route('stock.categoria', ['categoria' => 'disfraces', 'modo' => request('modo')]) }}" class="category-card-link">
                <div class="category-card">
                    <div class="card-img-container">
                        <img src="{{ asset('img/disfraces.jpg') }}" alt="Disfraces">
                    </div>
                    <h3>DISFRACES</h3>
                </div>
            </a>

            <!-- Categoría 4: Trajes de Gala -->
            <a href="{{ route('stock.categoria', ['categoria' => 'gala', 'modo' => request('modo')]) }}" class="category-card-link">
                <div class="category-card">
                    <div class="card-img-container">
                        <img src="{{ asset('img/gala.jpg') }}" alt="Trajes de Gala">
                    </div>
                    <h3>TRAJES DE GALA</h3>
                </div>
            </a>
        </div>
    </main>

    <!-- 2. CAMBIO AQUÍ: CONTROL PARA LA FLECHA ATRÁS DEL NAVEGADOR -->
    <script>
        if (window.history.replaceState) {
            window.history.replaceState(null, "", window.location.href);
        }
        window.onpopstate = function() {
            window.location.href = "{{ route('dashboard') }}";
        };
    </script>

</body>
</html>