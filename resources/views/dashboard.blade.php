<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Creaciones y Fantasías Erlinda</title>
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
</head>

<body>

    <!-- ENCABEZADO -->
    <header class="topbar">
        <div class="topbar-marca">
            <span class="figura"></span>
            <span>CREACIONES Y FANTASÍAS ERLINDA</span>
        </div>
        <div class="topbar-usuario">
            <span class="usuario-nombre">
                <span></span>
                <span>{{ auth()->check() ? trim(auth()->user()->nombre . ' ' . (auth()->user()->apellido ?? '')) : 'Erlinda Pedrazas' }}</span>
            </span>
            <form action="{{ route('logout') }}" method="POST" style="margin: 0;">
                @csrf
                <button type="submit" class="btn-cerrar-sesion" title="Cerrar sesión">
                    <span></span>
                    <span>Cerrar sesión</span>
                </button>
            </form>
        </div>
    </header>

    <!-- MENÚ LATERAL -->
    <aside class="sidebar">
        <a href="{{ route('dashboard') }}" class="menu-item activo">
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
        <a href="{{ route('reportes') }}" class="menu-item">
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
            <button type="submit" style="background: none; border: none; color: white; cursor: pointer; display: flex; align-items: center; gap: 10px; font-size: 16px;">
                <span>🚪</span>
                <span>Salir</span>
            </button>
        </form>
    </aside>

    <!-- CONTENIDO PRINCIPAL -->
    <main class="contenido">

        @if(session('error'))
            <div style="background-color: #fee2e2; border-left: 4px solid #ef4444; color: #b91c1c; padding: 12px 16px; border-radius: 6px; margin-bottom: 20px; font-weight: 500;">
                ⚠️ {{ session('error') }}
            </div>
        @endif

        <section class="bienvenida">
            <h1>Panel de Control</h1>
            <p>Bienvenido a Creaciones y Fantasías Erlinda</p>
        </section>

        @if(auth()->check() && auth()->user()->esAdmin())
        <!-- RESUMEN DIARIO (Solo Administrador) -->
        <section class="tarjeta resumen-diario">
            <h2>Resumen Diario de Alquileres</h2>
            <div class="estadisticas">
                
                <!-- Tarjeta 1: Alquileres hoy -->
                <div class="estadistica">
                    <img src="{{ asset('images/llave.gif') }}" alt="Alquileres hoy" class="stat-img">
                    <div class="stat-info">
                        <strong>{{ $alquileresHoy ?? 0 }}</strong>
                        <p>Alquileres hoy</p>
                        <span class="stat-subtexto"></span>
                    </div>
                </div>

                <!-- Tarjeta 2: Trajes salidos hoy -->
                <div class="estadistica">
                    <img src="{{ asset('images/traje.jpeg') }}" alt="Trajes salidos hoy" class="stat-img">
                    <div class="stat-info">
                        <strong>{{ $trajesSalidosHoy ?? 0 }}</strong>
                        <p>Trajes salidos hoy</p>
                        <span class="stat-subtexto"></span>
                    </div>
                </div>

                <!-- Tarjeta 3: Ingresos -->
                <div class="estadistica">
                    <img src="{{ asset('images/ingresos.png') }}" alt="Ingresos" class="stat-img">
                    <div class="stat-info">
                        <strong>Bs {{ number_format($ingresosHoy ?? 0) }}</strong>
                        <p>Ingresos</p>
                        <span class="stat-subtexto">Total generado hoy</span>
                    </div>
                </div>

            </div>
        </section>
        @endif

        <!-- DISPONIBILIDAD -->
        <section class="tarjeta">
            <h2>Disponibilidad de Stock</h2>
            <div class="stock">
                <p>Disponibles: <strong>{{ $porcDisponibles ?? 0 }}%</strong></p>
                <p>Alquilados: <strong>{{ $porcAlquilados ?? 0 }}%</strong></p>
                <p>Reservados: <strong>{{ $porcReservados ?? 0 }}%</strong></p>
                <p>En lavado: <strong>{{ $porcLavados ?? 0 }}%</strong></p>
            </div>
        </section>

        <!-- PRÓXIMAS DEVOLUCIONES -->
        <section class="tarjeta">
            <h2>Próximas Devoluciones</h2>
            <div class="devoluciones">
                @forelse($proximasDevoluciones as $dev)
                    <p><strong>{{ $dev->traje }}</strong> Devolución: {{ $dev->fecha_devolucion }}</p>
                @empty
                    <p>No hay devoluciones pendientes próximas.</p>
                @endforelse
            </div>
        </section>

        <!-- ALERTAS -->
        <section class="tarjeta tarjeta-alertas">
            <h2>Alertas del Sistema</h2>
            <div class="alerta">
                ⚠️ {{ $trajesLavadoCount ?? 0 }} trajes requieren limpieza (en lavandería).
            </div>
            @if(($lavadoAtrasadoCount ?? 0) > 0)
                <div class="alerta" style="color: red; font-weight: bold;">
                    🚨 ¡Atención! Hay {{ $lavadoAtrasadoCount }} trajes que llevan más de 2 días sin volver de lavado.
                </div>
            @endif
            @if(isset($nuevoUsuario))
                <div class="alerta">
                    Nuevo usuario registrado: {{ $nuevoUsuario->nombre }} {{ $nuevoUsuario->apellido }}
                </div>
            @endif
        </section>

    </main>

</body>
</html>