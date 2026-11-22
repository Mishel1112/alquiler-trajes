<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte Mensual - Creaciones y Fantasías Erlinda</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/reporte-mensual.css') }}">
</head>
<body>

    <!-- Encabezado superior idéntico al Dashboard -->
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

    <!-- Menú Lateral unificado -->
    <aside class="sidebar">
        <a href="{{ route('dashboard') }}" class="menu-item">
            <span class="menu-icono"><img src="{{ asset('images/ini.png') }}" alt="Inicio"></span>
            <span class="texto">Inicio</span>
        </a>
        <a href="{{ route('alquiler.seleccionar') }}" class="menu-item">
            <span class="menu-icono"><img src="{{ asset('images/resg.png') }}" alt="Registrar"></span>
            <span class="texto">Registrar alquiler</span>
        </a>
        <a href="{{ route('stock') }}" class="menu-item">
            <span class="menu-icono">📦</span>
            <span class="texto">Stock</span>
        </a>
        <a href="{{ route('alquileres') }}" class="menu-item">
            <span class="menu-icono">📋</span>
            <span class="texto">Alquileres</span>
        </a>
       
        @if(auth()->check() && auth()->user()->esAdmin())
        <a href="{{ route('reportes') }}" class="menu-item activo">
            <span class="menu-icono">📈</span>
            <span class="texto">Reportes</span>
        </a>
        <a href="{{ route('usuarios.index') }}" class="menu-item">
            <span class="menu-icono">👥</span>
            <span class="texto">Usuarios</span>
        </a>
        @endif

        <!-- Botón de Cerrar Sesión -->
        <form action="{{ route('logout') }}" method="POST" class="form-logout">
            @csrf
            <button type="submit" class="btn-salir">
                <span class="menu-icono">🚪</span>
                <span>Salir</span>
            </button>
        </form>
    </aside>

    <!-- CONTENIDO PRINCIPAL -->
    <main class="contenido">
        <div class="reporte-container">
            
            <div class="header-titulo">
                <div class="titulo-info">
                    <a href="{{ route('reportes') }}" class="btn-volver">← Volver a Reportes</a>
                    <h1>Reporte Mensual</h1>
                </div>

                <!-- Selector de mes y año -->
                <form method="GET" action="{{ route('reportes.mensual') }}" class="selector-mes-form">
                    <select name="mes" class="select-control">
                        <option value="1" {{ ($mesSeleccionado ?? \Carbon\Carbon::now()->month) == 1 ? 'selected' : '' }}>Enero</option>
                        <option value="2" {{ ($mesSeleccionado ?? \Carbon\Carbon::now()->month) == 2 ? 'selected' : '' }}>Febrero</option>
                        <option value="3" {{ ($mesSeleccionado ?? \Carbon\Carbon::now()->month) == 3 ? 'selected' : '' }}>Marzo</option>
                        <option value="4" {{ ($mesSeleccionado ?? \Carbon\Carbon::now()->month) == 4 ? 'selected' : '' }}>Abril</option>
                        <option value="5" {{ ($mesSeleccionado ?? \Carbon\Carbon::now()->month) == 5 ? 'selected' : '' }}>Mayo</option>
                        <option value="6" {{ ($mesSeleccionado ?? \Carbon\Carbon::now()->month) == 6 ? 'selected' : '' }}>Junio</option>
                        <option value="7" {{ ($mesSeleccionado ?? \Carbon\Carbon::now()->month) == 7 ? 'selected' : '' }}>Julio</option>
                        <option value="8" {{ ($mesSeleccionado ?? \Carbon\Carbon::now()->month) == 8 ? 'selected' : '' }}>Agosto</option>
                        <option value="9" {{ ($mesSeleccionado ?? \Carbon\Carbon::now()->month) == 9 ? 'selected' : '' }}>Septiembre</option>
                        <option value="10" {{ ($mesSeleccionado ?? \Carbon\Carbon::now()->month) == 10 ? 'selected' : '' }}>Octubre</option>
                        <option value="11" {{ ($mesSeleccionado ?? \Carbon\Carbon::now()->month) == 11 ? 'selected' : '' }}>Noviembre</option>
                        <option value="12" {{ ($mesSeleccionado ?? \Carbon\Carbon::now()->month) == 12 ? 'selected' : '' }}>Diciembre</option>
                    </select>

                    <select name="anio" class="select-control">
                        <option value="2026" {{ ($anioSeleccionado ?? 2026) == 2026 ? 'selected' : '' }}>2026</option>
                        <option value="2027" {{ ($anioSeleccionado ?? 2026) == 2027 ? 'selected' : '' }}>2027</option>
                    </select>

                    <button type="submit" class="btn-buscar">Ver</button>
                </form>
            </div>

            <!-- Gráfica de Barras por Semanas Dinámica -->
            @php
                $sumaTotalMes = array_sum($semanasData);
                $maxSemana = max(array_merge($semanasData, [1])); 
            @endphp

            <div class="tarjeta-seccion">
                <h2>Rendimiento por Semanas</h2>
                <p class="subtitulo-seccion">Comparativa semanal del mes seleccionado.</p>
                
                <div class="barras-contenedor">
                    @foreach(['Semana 1' => $semanasData['Semana 1'], 'Semana 2' => $semanasData['Semana 2'], 'Semana 3' => $semanasData['Semana 3'], 'Semana 4' => $semanasData['Semana 4']] as $nombreSemana => $montoSemana)
                        @php
                            $altura = $montoSemana > 0 ? max(30, round(($montoSemana / max(array_values($semanasData))) * 140)) : 20;
                            $esMayor = $montoSemana == max(array_values($semanasData)) && $montoSemana > 0;
                            $porcentajeSemana = $sumaTotalMes > 0 ? round(($montoSemana / $sumaTotalMes) * 100) : 0;
                        @endphp
                        <div class="barra-columna">
                            <span class="porcentaje-barra" style="font-size: 11px; margin-bottom: 5px; font-weight: bold; color: #555;">{{ $porcentajeSemana }}%</span>
                            <div class="barra {{ $esMayor ? 'destacada' : 'normal' }}" style="height: {{ $altura }}px;" title="Bs {{ number_format($montoSemana, 2) }}"></div>
                            <span class="{{ $esMayor ? 'texto-semana-activo' : '' }}">{{ $nombreSemana }}</span>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Totales del Mes -->
            <div class="reporte-grid">
                <div class="tarjeta-seccion estadisticas">
                    <h2>Total Recaudado en el Mes</h2>
                    <div class="caja-dato">
                        <span class="icono-stat"></span>
                        <div class="info-stat">
                            <label>Ingresos totales del mes</label>
                            <span class="valor">Bs {{ number_format($totalMes ?? 0, 2) }}</span>
                        </div>
                    </div>
                </div>

                <div class="tarjeta-seccion estadisticas">
                    <h2>Total de Trajes</h2>
                    <div class="caja-dato">
                        <span class="icono-stat"></span>
                        <div class="info-stat">
                            <label>Cantidad total de trajes</label>
                            <span class="valor">{{ $trajesMes ?? 0 }} trajes</span>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </main>

</body>
</html>