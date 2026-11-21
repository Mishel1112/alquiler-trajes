<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alquiler de Trajes - Creaciones y Fantasías Erlinda</title>
    <link rel="stylesheet" href="{{ asset('css/stock.css') }}">
</head>
<body>

    <!-- BARRA SUPERIOR ESTÁTICA -->
    <header class="topbar">
        <div class="logo-container">
            <span class="logo-text">Creaciones y Fantasías Erlinda</span>
        </div>
        <div class="user-info">
            <span class="user-icon">👤</span>
            <span class="user-role">{{ auth()->check() ? trim(auth()->user()->nombre . ' ' . (auth()->user()->apellido ?? '')) : 'Erlinda Pedrazas' }}</span>
            <a href="{{ route('dashboard') }}" class="btn-salir" style="background-color: #34495e; margin-right: 10px;">Dashboard</a>
            <form action="{{ route('logout') }}" method="POST" style="display: inline;">
                @csrf
                <button type="submit" class="btn-salir" style="border: none; cursor: pointer;">Cerrar sesión</button>
            </form>
        </div>
    </header>

    <main>
        <div class="stock-header">
            <h1>Módulo de Alquiler</h1>
            <p>Seleccione una categoría para ver los trajes disponibles y procesar un alquiler</p>
        </div>

        <div class="categories-grid">
            <!-- Categoría 1: Trajes de Bolivia -->
            <a href="{{ route('alquiler.categoria', 'tipicos') }}" class="category-card-link">
                <div class="category-card">
                    <div class="card-img-container">
                        <img src="{{ asset('img/bolivia.jpeg') }}" alt="Trajes de Bolivia">
                    </div>
                    <h3>TRAJES DE BOLIVIA</h3>
                </div>
            </a>

            <!-- Categoría 2: Trajes Internacionales -->
            <a href="{{ route('alquiler.categoria', 'internacionales') }}" class="category-card-link">
                <div class="category-card">
                    <div class="card-img-container">
                        <img src="{{ asset('img/internacionales.jpg') }}" alt="Trajes Internacionales">
                    </div>
                    <h3>TRAJES INTERNACIONALES</h3>
                </div>
            </a>

            <!-- Categoría 3: Disfraces -->
            <a href="{{ route('alquiler.categoria', 'disfraces') }}" class="category-card-link">
                <div class="category-card">
                    <div class="card-img-container">
                        <img src="{{ asset('img/disfraces.jpg') }}" alt="Disfraces">
                    </div>
                    <h3>DISFRACES</h3>
                </div>
            </a>

            <!-- Categoría 4: Trajes de Gala -->
            <a href="{{ route('alquiler.categoria', 'gala') }}" class="category-card-link">
                <div class="category-card">
                    <div class="card-img-container">
                        <img src="{{ asset('img/gala.jpg') }}" alt="Trajess de Gala">
                    </div>
                    <h3>TRAJES DE GALA</h3>
                </div>
            </a>
        </div>
    </main>

</body>
</html>