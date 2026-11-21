<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Módulo de Reportes - Creaciones y Fantasías Erlinda</title>
    <link rel="stylesheet" href="{{ asset('/css/reporte.css') }}">
</head>
<body>

    <!-- BARRA SUPERIOR ESTÁTICA -->
    <header class="topbar">
        <div class="topbar-marca">
            <span class="figura"></span>
            <span class="titulo-header">Creaciones y Fantasías Erlinda</span>
        </div>
        <div class="topbar-usuario">
            <span class="usuario-nombre">
                <span>👤</span>
                <span>{{ auth()->check() ? trim(auth()->user()->nombre . ' ' . (auth()->user()->apellido ?? '')) : 'Erlinda Pedrazas' }}</span>
            </span>
            <form action="{{ route('logout') }}" method="POST" style="margin: 0;">
                @csrf
                <button type="submit" class="btn-cerrar-sesion" title="Cerrar sesión">
                    <span>🚪</span>
                    <span>Cerrar sesión</span>
                </button>
            </form>
        </div>
    </header>

    <aside class="sidebar">
        <a href="{{ route('dashboard') }}" class="menu-item">
            <img src="{{ asset('images/ini.png') }}" alt="Inicio" style="width: 20px; height: 20px;">
            <span class="texto">Inicio</span>
        </a>
        <a href="{{ route('alquiler.seleccionar') }}" class="menu-item">
            <img src="{{ asset('images/resg.png') }}" alt="Registrar" style="width: 20px; height: 20px;">
            <span class="texto">Registrar alquiler</span>
        </a>
        <a href="{{ route('stock') }}" class="menu-item">
            <span class="icono">📦</span>
            <span class="texto">Stock</span>
        </a>
        <a href="{{ route('alquileres') }}" class="menu-item">
            <span class="icono">📋</span>
            <span class="texto">Alquileres</span>
        </a>

        @if(auth()->check() && auth()->user()->esAdmin())
        <a href="{{ route('reportes') }}" class="menu-item activo">
            <span class="icono">📈</span>
            <span class="texto">Reportes</span>
        </a>
        <a href="{{ route('usuarios.index') }}" class="menu-item">
            <span class="icono">👥</span>
            <span class="texto">Usuarios</span>
        </a>
        @endif

        <!-- Botón de Cerrar Sesión -->
        <form action="{{ route('logout') }}" method="POST" class="form-logout">
            @csrf
            <button type="submit" class="btn-salir">
                <span>🚪</span>
                <span>Salir</span>
            </button>
        </form>
    </aside>

    <!-- CONTENIDO CENTRAL: SELECCIÓN DE REPORTES -->
    <main class="contenido">
        <div class="reporte-container">
            
            <div class="header-titulo">
                <h1>Módulo de Reportes</h1>
            </div>

            <!-- Tarjetas de Selección (Diario, Mensual, Anual) con Imágenes -->
            <div class="grid-opciones-reporte">
                
                <!-- Opción 1: Diario -->
                <a href="{{ route('reportes.diario') }}" class="tarjeta-opcion">
                    <div style="margin-bottom: 15px;">
                        <img src="{{ asset('images/r.diario.jpeg') }}" alt="Reporte Diario" style="width: 120px; height: 120px; object-fit: contain;">
                    </div>
                    <h2>Reporte Diario</h2>
                    <p style="color: #666; font-size: 14px; margin-top: 10px;">Ver recaudación, trajes entregados y rentabilidad del día actual.</p>
                </a>

                <!-- Opción 2: Mensual -->
                <a href="{{ route('reportes.mensual') }}" class="tarjeta-opcion">
                    <div style="margin-bottom: 15px;">
                        <img src="{{ asset('images/mensual.jpeg') }}" alt="Reporte Mensual" style="width: 120px; height: 120px; object-fit: contain;">
                    </div>
                    <h2>Reporte Mensual</h2>
                    <p style="color: #666; font-size: 14px; margin-top: 10px;">Analizar rendimiento por semanas y meses del año.</p>
                </a>

                <!-- Opción 3: Anual -->
                <a href="{{ route('reportes.anual') }}" class="tarjeta-opcion">
                    <div style="margin-bottom: 15px;">
                        <img src="{{ asset('images/anuall.jpeg') }}" alt="Reporte Anual" style="width: 120px; height: 120px; object-fit: contain;">
                    </div>
                    <h2>Reporte Anual</h2>
                    <p style="color: #666; font-size: 14px; margin-top: 10px;">Comparativa global y totales acumulados.</p>
                </a>

            </div>

        </div>
    </main>

</body>
</html>