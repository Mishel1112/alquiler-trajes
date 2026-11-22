<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte Anual (Gestión {{ $anioSeleccionado }}) - Creaciones y Fantasías Erlinda</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/reporte-diario.css') }}?v={{ time() }}">
    <link rel="stylesheet" href="{{ asset('css/reporte-anual.css') }}?v={{ time() }}">
</head>
<body>

    <header class="topbar">
        <div class="topbar-marca">
            <span class="figura">✂️</span>
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

        <form action="{{ route('logout') }}" method="POST" class="form-logout">
            @csrf
            <button type="submit" class="btn-salir">
                <span class="menu-icono">🚪</span>
                <span>Salir</span>
            </button>
        </form>
    </aside>

    <main class="contenido">
        <div class="reporte-container">
            
            <div class="header-titulo">
                <div class="titulo-info">
                    <a href="{{ route('reportes') }}" class="btn-volver">← Volver a Reportes</a>
                    <h1>Reporte Anual - Gestión {{ $anioSeleccionado }}</h1>
                </div>

                <!-- Selector interactivo de gestiones (año actual y anteriores) -->
                <form method="GET" action="{{ route('reportes.anual') }}" class="selector-gestion-form">
                    <label for="selectGestion" class="label-selector">📅 Gestión:</label>
                    <select name="gestion" id="selectGestion" class="select-control-gestion" onchange="this.form.submit()">
                        @foreach($aniosDisponibles as $anio)
                            <option value="{{ $anio }}" {{ $anioSeleccionado == $anio ? 'selected' : '' }}>
                                Gestión {{ $anio }}{{ $anio == $anioActual ? ' (Actual)' : '' }}
                            </option>
                        @endforeach
                    </select>
                    <button type="submit" class="btn-filtrar-gestion">Consultar</button>
                </form>
            </div>

            <!-- SECCIÓN 1: KPI CARDS DE LA GESTIÓN SELECCIONADA -->
            <div class="tarjeta-gestion-destacada">
                <div class="gestion-destacada-header">
                    <div>
                        <h2 style="margin: 0; font-size: 19px; color: #1E293B;">
                            Balance General de la Gestión {{ $anioSeleccionado }}
                        </h2>
                        <p style="margin: 4px 0 0; font-size: 13.5px; color: #64748B;">
                            @if($anioSeleccionado == $anioActual)
                                Mostrando información acumulada en tiempo real de la gestión actual en curso.
                            @else
                                Mostrando registro histórico cerrado de la gestión {{ $anioSeleccionado }}.
                            @endif
                        </p>
                    </div>
                    <span class="gestion-badge-activa">
                        {{ $anioSeleccionado == $anioActual ? '🟢 Gestión En Curso' : '📁 Gestión Histórica' }}
                    </span>
                </div>

                <div class="kpis-gestion-grid">
                    <!-- Recaudación Total -->
                    <div class="kpi-card">
                        <div class="kpi-icono" style="color: #C0392B; background: #FEF2F2;">💰</div>
                        <div class="kpi-info">
                            <label>Recaudación Total</label>
                            <span class="kpi-valor" style="color: #C0392B;">Bs {{ number_format($totalGestion, 2, ',', '.') }}</span>
                        </div>
                    </div>

                    <!-- Trajes Entregados -->
                    <div class="kpi-card">
                        <div class="kpi-icono" style="color: #2563EB; background: #EFF6FF;">👗</div>
                        <div class="kpi-info">
                            <label>Trajes Entregados</label>
                            <span class="kpi-valor">{{ $trajesGestion }} Trajes</span>
                        </div>
                    </div>

                    <!-- Operaciones / Alquileres -->
                    <div class="kpi-card">
                        <div class="kpi-icono" style="color: #7C3AED; background: #F5F3FF;">📋</div>
                        <div class="kpi-info">
                            <label>Alquileres Realizados</label>
                            <span class="kpi-valor">{{ $operacionesGestion }} Contratos</span>
                        </div>
                    </div>

                    <!-- Comparativa vs Gestión Anterior -->
                    <div class="kpi-card">
                        <div class="kpi-icono" style="color: #059669; background: #ECFDF5;">📈</div>
                        <div class="kpi-info">
                            <label>Vs. Gestión {{ $anioAnterior }}</label>
                            @if($porcentajeCrecimiento >= 0)
                                <span class="kpi-valor" style="color: #059669;">+{{ $porcentajeCrecimiento }}%</span>
                                <span class="badge-crecimiento-positivo">▲ +Bs {{ number_format($diferencia, 2, ',', '.') }}</span>
                            @else
                                <span class="kpi-valor" style="color: #DC2626;">{{ $porcentajeCrecimiento }}%</span>
                                <span class="badge-crecimiento-negativo">▼ Bs {{ number_format($diferencia, 2, ',', '.') }}</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- SECCIÓN 2: DESGLOSE MENSUAL DE LA GESTIÓN SELECCIONADA -->
            <div class="tarjeta-seccion estadisticas">
                <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px; margin-bottom: 15px;">
                    <div>
                        <h2>Desglose Mensual (Gestión {{ $anioSeleccionado }})</h2>
                        <p style="margin: 3px 0 0; color: #64748B; font-size: 13.5px;">
                            Distribución de recaudación y flujo de prendas mes a mes.
                        </p>
                    </div>
                    @if($mesPico)
                        <span style="background: #FFFBEB; color: #B45309; border: 1px solid #FDE68A; padding: 4px 12px; border-radius: 16px; font-size: 12.5px; font-weight: 600;">
                            ⭐ Mes pico de demanda: <strong>{{ $mesPico }}</strong>
                        </span>
                    @endif
                </div>

                <div class="tabla-mensual-wrapper">
                    <table class="tabla-desglose-mensual">
                        <thead>
                            <tr>
                                <th>Mes</th>
                                <th style="text-align: center;">Alquileres</th>
                                <th style="text-align: center;">Trajes</th>
                                <th style="text-align: right;">Recaudación (Bs.)</th>
                                <th style="min-width: 180px;">Aporte al Año</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($mesesDesglose as $m)
                                <tr class="{{ $m['nombre'] === $mesPico && $m['total'] > 0 ? 'mes-activo-pico' : '' }}">
                                    <td>
                                        <strong>{{ $m['nombre'] }}</strong>
                                        @if($m['nombre'] === $mesPico && $m['total'] > 0)
                                            <span style="font-size: 11px; color: #D97706; margin-left: 6px;">(Mayor recaudación)</span>
                                        @endif
                                    </td>
                                    <td style="text-align: center;">{{ $m['operaciones'] }}</td>
                                    <td style="text-align: center;">{{ $m['trajes'] }}</td>
                                    <td style="text-align: right; font-weight: 600; color: {{ $m['total'] > 0 ? '#C0392B' : '#94A3B8' }};">
                                        Bs {{ number_format($m['total'], 2, ',', '.') }}
                                    </td>
                                    <td>
                                        <div class="barra-progreso-bg">
                                            <div class="barra-progreso-fill" style="width: {{ $m['porcentaje'] }}%;"></div>
                                        </div>
                                        <span style="font-size: 12px; font-weight: 600; color: #475569;">{{ $m['porcentaje'] }}%</span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr style="background: #F8FAFC; font-weight: 700;">
                                <td>TOTAL GESTIÓN {{ $anioSeleccionado }}</td>
                                <td style="text-align: center;">{{ $operacionesGestion }}</td>
                                <td style="text-align: center;">{{ $trajesGestion }}</td>
                                <td style="text-align: right; color: #C0392B;">Bs {{ number_format($totalGestion, 2, ',', '.') }}</td>
                                <td>100%</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

            <!-- SECCIÓN 3: COMPARATIVA MULTIANUAL DE TODAS LAS GESTIONES -->
            <div style="margin-top: 25px;">
                <h2 style="font-size: 18px; font-weight: 700; color: #1E293B; margin-bottom: 5px;">
                    Histórico Comparativo entre Gestiones
                </h2>
                <p style="color: #64748B; font-size: 13.5px; margin: 0 0 16px;">
                    Haz clic en consultar en el selector superior para analizar cualquier gestión en detalle.
                </p>

                <div class="grid-anual">
                    @forelse($aniosData as $data)
                        <div class="tarjeta-gestion-comparativa {{ $data->anio == $anioSeleccionado ? 'activa' : '' }}">
                            @if($data->anio == $anioSeleccionado)
                                <span class="badge-gestion-seleccionada">Viendo ahora</span>
                            @endif

                            <h3 style="margin: 0 0 12px; font-size: 17px; color: #1E293B; display: flex; align-items: center; gap: 8px;">
                                <span>🗓️</span> Gestión {{ $data->anio }}
                                @if($data->anio == $anioActual)
                                    <span style="font-size: 11.5px; font-weight: 600; color: #16A34A; background: #DCFCE7; padding: 2px 7px; border-radius: 10px;">Actual</span>
                                @endif
                            </h3>

                            <div class="caja-dato" style="margin-top: 10px; padding: 12px 14px;">
                                <div class="info-stat">
                                    <label>Recaudación Anual</label>
                                    <span class="valor" style="font-size: 18px;">Bs {{ number_format($data->total, 2, ',', '.') }}</span>
                                </div>
                            </div>

                            <div class="caja-dato" style="margin-top: 8px; padding: 12px 14px;">
                                <div class="info-stat">
                                    <label>Trajes Alquilados</label>
                                    <span class="valor" style="font-size: 18px; color: #2563EB;">{{ $data->trajes }} Trajes</span>
                                </div>
                            </div>

                            @if($data->anio != $anioSeleccionado)
                                <div style="margin-top: 14px; text-align: right;">
                                    <a href="{{ route('reportes.anual', ['gestion' => $data->anio]) }}" style="font-size: 12.5px; font-weight: 600; color: #C0392B; text-decoration: none;">
                                        Ver esta gestión →
                                    </a>
                                </div>
                            @endif
                        </div>
                    @empty
                        <div class="tarjeta-seccion estadisticas" style="grid-column: 1 / -1; text-align: center; padding: 40px;">
                            <h2>Sin registros</h2>
                            <p style="color: #7f8c8d; margin-top: 10px;">No se encontraron alquileres registrados en el sistema.</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- SECCIÓN 4: TOTAL ACUMULADO GLOBAL HISTÓRICO -->
            <div class="tarjeta-seccion estadisticas" style="margin-top: 25px; background: linear-gradient(135deg, #FFFDFB 0%, #FFFFFF 100%); border-left: 5px solid #EA580C;">
                <h2 style="color: #C2410C; display: flex; align-items: center; gap: 8px; border-bottom: 2px solid #FFEDD5; padding-bottom: 12px; margin-bottom: 20px;">
                    <span>📊</span> Resumen Acumulado Histórico (Todas las Gestiones)
                </h2>
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 16px;">
                    <div class="caja-dato" style="margin-top: 0; background: white;">
                        <span class="icono-stat" style="color: #EA580C; background: #FFEDD5;">💰</span>
                        <div class="info-stat">
                            <label>Recaudación Histórica Total</label>
                            <span class="valor" style="color: #EA580C;">Bs {{ number_format($totalAcumulado, 2, ',', '.') }}</span>
                        </div>
                    </div>
                    <div class="caja-dato" style="margin-top: 0; background: white;">
                        <span class="icono-stat" style="color: #2563EB; background: #DBEAFE;">👗</span>
                        <div class="info-stat">
                            <label>Total Histórico de Trajes</label>
                            <span class="valor" style="color: #2563EB;">{{ $historicoTrajes }} Trajes</span>
                        </div>
                    </div>
                    <div class="caja-dato" style="margin-top: 0; background: white;">
                        <span class="icono-stat" style="color: #7C3AED; background: #EDE9FE;">📋</span>
                        <div class="info-stat">
                            <label>Total de Contratos</label>
                            <span class="valor" style="color: #7C3AED;">{{ $historicoOperaciones }} Alquileres</span>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </main>

</body>
</html>
