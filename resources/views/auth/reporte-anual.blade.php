<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte Anual - Creaciones y Fantasías Erlinda</title>
    <link rel="stylesheet" href="{{ asset('css/reporte-diario.css') }}">
    <link rel="stylesheet" href="{{ asset('css/reporte-anual.css') }}">
</head>
<body>

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

        <form action="{{ route('logout') }}" method="POST" style="margin-top: 20px; padding: 0 20px;">
            @csrf
            <button type="submit" style="background: none; border: none; color: #333; cursor: pointer; display: flex; align-items: center; gap: 10px; font-size: 15px;">
                <span>🚪</span>
                <span>Salir</span>
            </button>
        </form>
    </aside>

    <main class="contenido">
        <div class="reporte-container">
            
            <div class="header-titulo">
                <div class="titulo-info">
                    <a href="{{ route('reportes') }}" class="btn-volver">← Volver a Reportes</a>
                    <h1>Reporte Anual </h1>
                </div>
            </div>

            <!-- Cuadrícula superior para los años individuales (2026, 2027, etc.) -->
            <div class="grid-anual" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 20px;">
                
                @forelse($aniosData as $data)
                    <div class="tarjeta-seccion estadisticas">
                        <h2>Año {{ $data->anio }}</h2>
                        <div class="caja-dato" style="margin-top: 15px;">
                            <span class="icono-stat"></span>
                            <div class="info-stat">
                                <label>Recaudación Total</label>
                                <span class="valor">Bs {{ number_format($data->total, 2, ',', '.') }}</span>
                            </div>
                        </div>
                        <div class="caja-dato" style="margin-top: 10px;">
                            <span class="icono-stat"></span>
                            <div class="info-stat">
                                <label>Trajes Entregados</label>
                                <span class="valor">{{ $data->trajes }} Trajes</span>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="tarjeta-seccion estadisticas" style="grid-column: 1 / -1; text-align: center; padding: 40px;">
                        <h2>Sin registros</h2>
                        <p style="color: #7f8c8d; margin-top: 10px;">No se encontraron alquileres registrados en el sistema.</p>
                    </div>
                @endforelse

            </div>

            <!-- Tarjeta de Total Acumulado Global (Diseño Profesional) -->
            <div class="tarjeta-seccion estadisticas" style="margin-top: 25px; background: linear-gradient(135deg, #fffaf0 0%, #ffffff 100%); border-left: 5px solid #e65100;">
                <h2 style="color: #e65100; display: flex; align-items: center; gap: 8px; border-bottom: 2px solid #ffebee; padding-bottom: 12px; margin-bottom: 20px;">
                    <span></span> Total Acumulado
                </h2>
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 20px;">
                    <div class="caja-dato" style="margin-top: 0; background: white;">
                        <span class="icono-stat"></span>
                        <div class="info-stat">
                            <label>Recaudación Total</label>
                            <span class="valor" style="color: #e65100;">Bs {{ number_format($totalAcumulado, 2, ',', '.') }}</span>
                        </div>
                    </div>
                    <div class="caja-dato" style="margin-top: 0; background: white;">
                        <span class="icono-stat"></span>
                        <div class="info-stat">
                            <label>Total Trajes</label>
                            <span class="valor">{{ $historicoTrajes }} Trajes</span>
                        </div>
                    </div>
                </div>
            </div>


        </div>
    </main>

</body>
</html>