<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte Diario - Creaciones y Fantasías Erlinda</title>
    <link rel="stylesheet" href="{{ asset('/css/reporte-diario.css') }}">
    <script src="{{ asset('js/chart.umd.js') }}"></script>
    <script>
        if (typeof Chart === 'undefined') {
            document.write('<script src="https://cdn.jsdelivr.net/npm/chart.js"><\/script>');
        }
    </script>
</head>
<body>

    <!-- BARRA SUPERIOR -->
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

    <!-- MENÚ LATERAL -->
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

    <!-- CONTENIDO PRINCIPAL -->
    <main class="contenido">
        <div class="reporte-container">
            
            <div class="header-titulo" style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 15px;">
                <div class="titulo-info">
                    <a href="{{ route('reportes') }}" class="btn-volver">← Volver a Reportes</a>
                    <h1>Reporte Diario</h1>
                </div>

                <!-- Calendario Interactivo Estilo Selector Móvil / Web -->
                <form method="GET" action="{{ route('reportes.diario') }}" style="display: flex; align-items: center; gap: 10px; background: #fff; padding: 8px 12px; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.05); border: 1px solid #ddd;">
                    <div>
                        <label style="font-size: 11px; font-weight: bold; color: #555; display: block; margin-bottom: 2px;">SELECCIONAR FECHA</label>
                        <input type="date" name="fecha" value="{{ $fechaSeleccionada ?? date('Y-m-d') }}" onchange="this.form.submit()" style="padding: 5px 8px; border: 1px solid #ccc; border-radius: 4px; font-size: 14px; outline: none; cursor: pointer;">
                    </div>
                    <noscript>
                        <button type="submit" style="background: #e65100; color: white; border: none; padding: 6px 12px; border-radius: 4px; cursor: pointer; font-weight: bold;">Consultar</button>
                    </noscript>
                </form>
            </div>

            <div class="reporte-grid">
                <!-- Sección Gráfica de Torta (Dinámica e Interactiva) -->
                <div class="tarjeta-seccion">
                    <h2>Rentabilidad por Traje</h2>
                    <div class="grafica-contenedor">
                        <div class="torta-visual">
                            <canvas id="graficaTorta" width="190" height="190"></canvas>
                            <div class="torta-centro">
                                <span class="torta-centro-numero">{{ $trajesEntregados ?? 0 }}</span>
                                <span class="torta-centro-texto">{{ ($trajesEntregados ?? 0) == 1 ? 'Traje' : 'Trajes' }}</span>
                            </div>
                        </div>
                        <div class="leyenda-torta">
                            <ul>
                                @forelse($modelosData ?? [] as $index => $item)
                                    <li data-index="{{ $index }}">
                                        <span class="punto" style="background-color: {{ $item['color'] ?? '#e65100' }};"></span> 
                                        <span class="leyenda-nombre" title="{{ $item['modelo'] }}">{{ $item['modelo'] }}</span>
                                        <span class="leyenda-cantidad">({{ $item['cantidad'] }})</span>
                                        <span class="leyenda-badge" style="background: {{ $item['color'] ?? '#e65100' }}22; color: {{ $item['color'] ?? '#e65100' }};">
                                            {{ $item['porcentaje'] }}%
                                        </span>
                                    </li>
                                @empty
                                    <li style="color: #777; text-align: center; padding: 15px; justify-content: center; background: none; border: none;">
                                        No hay alquileres registrados para esta fecha.
                                    </li>
                                @endforelse
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Sección de Estadísticas del Día -->
                <div class="tarjeta-seccion estadisticas">
                    <h2>Estadísticas del Día</h2>
                    <div class="caja-dato">
                        <span class="icono-stat"></span>
                        <div class="info-stat">
                            <label>Total recaudado en trajes</label>
                            <span class="valor">Bs {{ number_format($totalRecaudado ?? 0, 2) }}</span>
                        </div>
                    </div>
                    <div class="caja-dato">
                        <span class="icono-stat"></span>
                        <div class="info-stat">
                            <label>Total de trajes entregados hoy</label>
                            <span class="valor">{{ $trajesEntregados ?? 0 }} Trajes</span>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </main>

    <!-- Script de Inicialización y Animación de la Torta Dinámica -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const modelosData = @json($modelosData ?? []);
            const totalTrajes = {{ $trajesEntregados ?? 0 }};
            const canvas = document.getElementById('graficaTorta');
            if (!canvas) return;

            const ctx = canvas.getContext('2d');

            if (modelosData.length > 0) {
                const labels = modelosData.map(d => d.modelo);
                const data = modelosData.map(d => d.cantidad);
                const colors = modelosData.map(d => d.color);

                const chart = new Chart(ctx, {
                    type: 'doughnut',
                    data: {
                        labels: labels,
                        datasets: [{
                            data: data,
                            backgroundColor: colors,
                            borderColor: '#ffffff',
                            borderWidth: 2,
                            hoverOffset: 12,
                        }]
                    },
                    options: {
                        responsive: false,
                        maintainAspectRatio: false,
                        cutout: '65%',
                        animation: {
                            animateRotate: true,
                            animateScale: true,
                            duration: 1200,
                            easing: 'easeOutQuart'
                        },
                        plugins: {
                            legend: {
                                display: false
                            },
                            tooltip: {
                                backgroundColor: 'rgba(15, 23, 42, 0.92)',
                                titleFont: { size: 13, weight: 'bold' },
                                bodyFont: { size: 13 },
                                padding: 12,
                                cornerRadius: 8,
                                displayColors: true,
                                boxPadding: 4,
                                callbacks: {
                                    label: function(context) {
                                        const item = modelosData[context.dataIndex];
                                        return ` ${item.cantidad} traje(s) (${item.porcentaje}%)`;
                                    }
                                }
                            }
                        }
                    }
                });

                // Interactividad: al pasar el ratón sobre los elementos de la leyenda, resaltar la porción en la torta
                const listItems = document.querySelectorAll('.leyenda-torta li[data-index]');
                listItems.forEach(li => {
                    li.addEventListener('mouseenter', function() {
                        const idx = parseInt(this.getAttribute('data-index'));
                        chart.setActiveElements([{ datasetIndex: 0, index: idx }]);
                        chart.tooltip.setActiveElements([{ datasetIndex: 0, index: idx }], { x: 0, y: 0 });
                        chart.update();
                    });
                    li.addEventListener('mouseleave', function() {
                        chart.setActiveElements([]);
                        chart.tooltip.setActiveElements([], { x: 0, y: 0 });
                        chart.update();
                    });
                });
            } else {
                // Estado vacío: dibujar dona gris suave
                new Chart(ctx, {
                    type: 'doughnut',
                    data: {
                        labels: ['Sin alquileres'],
                        datasets: [{
                            data: [1],
                            backgroundColor: ['#e2e8f0'],
                            borderColor: '#ffffff',
                            borderWidth: 2
                        }]
                    },
                    options: {
                        responsive: false,
                        maintainAspectRatio: false,
                        cutout: '65%',
                        animation: {
                            duration: 600
                        },
                        plugins: {
                            legend: { display: false },
                            tooltip: { enabled: false }
                        }
                    }
                });
            }
        });
    </script>
</body>
</html>