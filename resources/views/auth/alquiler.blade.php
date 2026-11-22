<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alquileres, Devoluciones y Reservas - Creaciones y Fantasías Erlinda</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('css/alquiler.css') }}?v={{ time() }}">
</head>
<body>

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
        <a href="{{ route('alquileres') }}" class="menu-item activo">
            <span class="menu-icono">📋</span>
            <span class="texto">Alquileres</span>
        </a>
        @if(auth()->check() && auth()->user()->esAdmin())
        <a href="{{ route('reportes') }}" class="menu-item">
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
                <span class="texto">Salir</span>
            </button>
        </form>
    </aside>

    <!-- CONTENEDOR DERECHO (BARRA SUPERIOR + CONTENIDO) -->
    <div class="main-container">

        <!-- ENCABEZADO -->
        <header class="topbar">
            <div class="topbar-marca">
                <span class="figura"></span>
                <span>CREACIONES Y FANTASÍAS ERLINDA</span>
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

        <!-- CONTENIDO PRINCIPAL -->
        <main class="contenido">

            <section class="bienvenida">
                <h1>Control de alquileres</h1>
                <p>Gestiona alquileres, devoluciones y reservas de trajes</p>
            </section>

            <!-- Pestañas de Navegación Interna -->
            <div class="tabs">
                <a href="{{ route('alquileres', ['tab' => 'activos']) }}" class="tab-btn {{ $pestaña_actual == 'activos' ? 'active' : '' }}">
                    Alquileres Activos
                </a>
                <a href="{{ route('alquileres', ['tab' => 'devoluciones']) }}" class="tab-btn {{ $pestaña_actual == 'devoluciones' ? 'active' : '' }}">
                    Devoluciones 
                </a>
                <a href="{{ route('alquileres', ['tab' => 'reservas']) }}" class="tab-btn {{ $pestaña_actual == 'reservas' ? 'active' : '' }}">
                    Reservas
                </a>
                <a href="{{ route('alquileres', ['tab' => 'historial']) }}" class="tab-btn {{ $pestaña_actual == 'historial' ? 'active' : '' }}">
                    Historial
                </a>
            </div>

            <!-- Mensaje de éxito si se procesa una acción -->
            @if(session('success'))
                <div class="alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <!-- 1. PESTAÑA: ALQUILERES ACTIVOS -->
            @if ($pestaña_actual == 'activos')
                <div class="card">
                    <h2>Alquileres Activos</h2>
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>N°</th>
                                <th>Cliente</th>
                                <th>Trajes Alquilados</th>
                                <th style="text-align: center;">Cantidad</th>
                                <th>Fecha Límite</th>
                                <th style="text-align: center;">Estado</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($alquileres_activos as $item)
                            @php
                                $idRecibo = is_object($item) ? ($item->numero_recibo ?? $item->idAlquiler) : ($item['numero_recibo'] ?? $item['idAlquiler']);
                                $nomCliente = is_object($item) ? ($item->cliente->nombre_completo ?? $item->cliente) : ($item['cliente']['nombre_completo'] ?? $item['cliente']);
                                $fLimiteRaw = is_object($item) ? ($item->fecha_limite ?? ($item->fecha_devolucion ?? null)) : ($item['fecha_limite'] ?? ($item['fecha_devolucion'] ?? null));
                                $fLimite = !empty($fLimiteRaw) && $fLimiteRaw !== 'N/D' ? \Carbon\Carbon::parse($fLimiteRaw)->format('d/m/Y') : 'N/D';
                                $cantTotal = is_object($item) ? ($item->cantidad_total ?? 1) : ($item['cantidad_total'] ?? 1);
                                $estadoPlazo = is_object($item) ? ($item->estado_plazo ?? 'Vigente') : ($item['estado_plazo'] ?? 'Vigente');
                            @endphp
                            <tr>
                                <td><strong>#{{ $idRecibo }}</strong></td>
                                <td>{{ $nomCliente }}</td>
                                <td>
                                    @if(is_object($item) && isset($item->detalles) && count($item->detalles) > 0)
                                        <ul style="margin: 0; padding-left: 18px; list-style-type: disc;">
                                            @foreach($item->detalles as $det)
                                                <li>{{ $det->nombre ?? (isset($det->traje) ? (is_object($det->traje) ? $det->traje->nombre : $det->traje) : 'Traje') }}</li>
                                            @endforeach
                                        </ul>
                                    @else
                                        {{ is_object($item) ? ($item->traje ?? 'Sin traje') : ($item['traje'] ?? 'Sin traje') }}
                                    @endif
                                </td>
                                <td style="text-align: center;">
                                    <span class="badge-cantidad">{{ $cantTotal }} {{ $cantTotal == 1 ? 'traje' : 'trajes' }}</span>
                                </td>
                                <td>{{ $fLimite }}</td>
                                <td style="text-align: center;">
                                    @if(strtolower($estadoPlazo) === 'vigente')
                                        <span class="badge-vigente">Vigente</span>
                                    @else
                                        <span class="badge-atrasado">Atrasado</span>
                                    @endif
                                </td>
                                <td>
                                    <div style="display: flex; gap: 6px; align-items: center;">
                                        <a href="{{ route('alquiler.recibo', $idRecibo) }}" target="_blank" class="btn-accion-recibo" title="Ver recibo">
                                             Ver recibo
                                        </a>
                                        <button type="button" class="btn-devolver" onclick="abrirModalDevolver({{ $idRecibo }}, '{{ addslashes($nomCliente) }}', {{ floatval($item->monto_garantia ?? 0) }})" title="Devolver trajes">Devolver</button>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center">No hay alquileres activos actualmente.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

            <!-- 2. PESTAÑA: DEVOLUCIONES / LAVANDERÍA -->
            @elseif ($pestaña_actual == 'devoluciones')
                <div class="card">
                    <h2>Devoluciones</h2>
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>N° Devolución</th>
                                <th>Cliente</th>
                                <th>Trajes Devueltos</th>
                                <th style="text-align: center;">Cantidad</th>
                                <th>Fecha de Devolución</th>
                                <th style="text-align: center;">Estado</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($devoluciones as $dev)
                            @php
                                $idDev = is_object($dev) ? ($dev->numero_recibo ?? $dev->idAlquiler) : ($dev['numero_recibo'] ?? $dev['idAlquiler']);
                                $clienteDev = is_object($dev) ? ($dev->cliente->nombre_completo ?? $dev->cliente) : ($dev['cliente']['nombre_completo'] ?? $dev['cliente']);
                                $fDevolucion = is_object($dev) ? ($dev->fecha_devolucion_formateada ?? 'N/D') : ($dev['fecha_devolucion_formateada'] ?? 'N/D');
                                $fIngreso = is_object($dev) ? ($dev->fecha_ingreso_stock_formateada ?? 'Pendiente') : ($dev['fecha_ingreso_stock_formateada'] ?? 'Pendiente');
                                $estadoSlug = is_object($dev) ? ($dev->estado_slug ?? 'lavado') : ($dev['estado_slug'] ?? 'lavado');
                                $estadoNombre = is_object($dev) ? ($dev->estado_nombre ?? 'En lavado') : ($dev['estado_nombre'] ?? 'En lavado');
                                $cantTotalDev = is_object($dev) ? ($dev->cantidad_total ?? 1) : ($dev['cantidad_total'] ?? 1);
                                $detallesDev = is_object($dev) ? ($dev->detalles ?? collect()) : ($dev['detalles'] ?? []);
                            @endphp
                            <tr>
                                <td><strong>#{{ $idDev }}</strong></td>
                                <td>{{ $clienteDev }}</td>
                                <td>
                                    @if(count($detallesDev) > 0)
                                        <ul style="margin: 0; padding-left: 18px; list-style-type: disc;">
                                            @foreach($detallesDev as $d)
                                                <li>{{ is_object($d) ? $d->nombre : ($d['nombre'] ?? 'Traje') }}</li>
                                            @endforeach
                                        </ul>
                                    @else
                                        {{ is_object($dev) ? ($dev->traje ?? 'Sin traje') : ($dev['traje'] ?? 'Sin traje') }}
                                    @endif
                                </td>
                                <td style="text-align: center;">
                                    <span class="badge-cantidad">{{ $cantTotalDev }} {{ $cantTotalDev == 1 ? 'traje' : 'trajes' }}</span>
                                </td>
                                <td>{{ $fDevolucion }}</td>
                                <td style="text-align: center;">
                                    @if($estadoSlug === 'lavado')
                                        <span class="badge-en-lavado">En lavado</span>
                                    @else
                                        <span class="badge-ingresado-stock">Ingresado al stock</span>
                                    @endif
                                </td>
                                <td>
                                    @if($estadoSlug === 'lavado')
                                        {{-- Estado «En lavado»: Mostrar botón «Registrar ingreso al stock» --}}
                                        <form action="{{ route('alquileres.liberar') }}" method="POST" style="margin: 0;" onsubmit="return confirm('¿Confirmar el reingreso al inventario de los trajes devueltos de lavandería?\n\nAl confirmar, las cantidades se sumarán al stock disponible y el registro quedará guardado en el historial como «Ingresado al stock».');">
                                            @csrf
                                            <input type="hidden" name="id" value="{{ $idDev }}">
                                            <button type="submit" class="btn-ingreso-stock" title="Registrar ingreso al stock">
                                                Registrar ingreso al stock
                                            </button>
                                        </form>
                                    @else
                                        {{-- Estado «Ingresado al stock»: Mostrar botón «Ver detalle» --}}
                                        <button type="button" class="btn-ver-detalle"
                                                data-id="{{ $idDev }}"
                                                data-cliente="{{ $clienteDev }}"
                                                data-trajes="{{ json_encode($detallesDev) }}"
                                                data-cantidad="{{ $cantTotalDev }}"
                                                data-fdevolucion="{{ $fDevolucion }}"
                                                data-fingreso="{{ $fIngreso }}"
                                                data-estado="{{ $estadoNombre }}"
                                                onclick="mostrarModalDetalle(this)"
                                                title="Ver detalle de la devolución">
                                            Ver detalle
                                        </button>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center">No hay registros en devoluciones actualmente.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

            <!-- 3. PESTAÑA: RESERVAS / APARTADOS -->
            @elseif ($pestaña_actual == 'reservas')
                <div class="card">
                    <h2>Trajes Reservados</h2>
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>N°</th>
                                <th>Cliente</th>
                                <th>Trajes Reservados</th>
                                <th>Cant. Total</th>
                                <th>F. de Reserva</th>
                                <th>F. Entrega Programada</th>
                                <th>Estado</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($reservas as $res)
                            @php
                                $idRecibo = is_object($res) ? ($res->numero_recibo ?? $res->idReserva ?? $res->idAlquiler) : ($res['numero_recibo'] ?? $res['idReserva'] ?? $res['idAlquiler']);
                                $clienteNom = is_object($res) ? ($res->cliente->nombre_completo ?? ($res->cliente ?? 'N/D')) : (is_array($res['cliente'] ?? null) ? $res['cliente']['nombre_completo'] : ($res['cliente'] ?? 'N/D'));
                                
                                $rawReserva = is_object($res) ? ($res->fecha_reserva ?? ($res->created_at ?? null)) : ($res['fecha_reserva'] ?? ($res['created_at'] ?? null));
                                $fReserva = !empty($rawReserva) && $rawReserva !== 'N/D' ? \Carbon\Carbon::parse($rawReserva)->format('d/m/Y') : 'N/D';

                                $rawEntrega = is_object($res) ? ($res->fecha_programada ?? ($res->fecha_entrega ?? ($res->fecha_devolucion ?? null))) : ($res['fecha_programada'] ?? ($res['fecha_entrega'] ?? null));
                                $fEntrega = !empty($rawEntrega) && $rawEntrega !== 'N/D' ? \Carbon\Carbon::parse($rawEntrega)->format('d/m/Y') : 'N/D';

                                $estadoRes = is_object($res) ? ($res->estado ?? 'Reservado') : ($res['estado'] ?? 'Reservado');
                                
                                // Cálculo de la cantidad total de trajes para el agrupamiento
                                $cantTotal = 0;
                                if (is_object($res) && isset($res->detalles)) {
                                    $cantTotal = $res->detalles->sum('cantidad');
                                } elseif (is_array($res) && isset($res['detalles'])) {
                                    $cantTotal = array_sum(array_column($res['detalles'], 'cantidad'));
                                } else {
                                    $cantTotal = is_object($res) ? ($res->cantidad ?? 1) : ($res['cantidad'] ?? 1);
                                }
                            @endphp
                            <tr>
                                <td><strong>#{{ $idRecibo }}</strong></td>
                                <td>{{ $clienteNom }}</td>
                                <td>
                                    {{-- Muestra la lista de trajes agrupados bajo la misma reserva --}}
                                    @if(is_object($res) && isset($res->detalles) && count($res->detalles) > 0)
                                        <ul style="margin: 0; padding-left: 15px; list-style-type: disc;">
                                            @foreach($res->detalles as $det)
                                                <li>{{ $det->nombre ?? (isset($det->traje) ? (is_object($det->traje) ? $det->traje->nombre : $det->traje) : 'Traje') }} (x{{ $det->cantidad ?? 1 }})</li>
                                            @endforeach
                                        </ul>
                                    @elseif(is_array($res) && isset($res['detalles']) && count($res['detalles']) > 0)
                                        <ul style="margin: 0; padding-left: 15px; list-style-type: disc;">
                                            @foreach($res['detalles'] as $det)
                                                <li>{{ is_array($det['traje'] ?? null) ? $det['traje']['nombre'] : ($det['nombre'] ?? (isset($det['traje']) ? $det['traje'] : 'Traje')) }} (x{{ $det['cantidad'] ?? 1 }})</li>
                                            @endforeach
                                        </ul>
                                    @else
                                        {{ is_object($res) ? $res->traje : $res['traje'] }}
                                    @endif
                                </td>
                                <td style="text-align: center;"><strong>{{ $cantTotal }}</strong></td>
                                <td>{{ $fReserva }}</td>
                                <td><strong style="color: #b9770e;">{{ $fEntrega }}</strong></td>
                                <td>
                                    @if(strtolower($estadoRes) == 'reservado')
                                        <span style="background: #fff3cd; color: #856404; padding: 4px 10px; border-radius: 6px; font-weight: bold; font-size: 13px; display: inline-block;">
                                            Reservado
                                        </span>
                                    @elseif(strtolower($estadoRes) == 'activo' || strtolower($estadoRes) == 'entregado')
                                        <span style="background: #d4edda; color: #155724; padding: 4px 10px; border-radius: 6px; font-weight: bold; font-size: 13px; display: inline-block;">
                                            Entregado
                                        </span>
                                    @elseif(strtolower($estadoRes) == 'cancelado')
                                        <span style="background: #f8d7da; color: #721c24; padding: 4px 10px; border-radius: 6px; font-weight: bold; font-size: 13px; display: inline-block;">
                                            Cancelado
                                        </span>
                                    @else
                                        <span style="background: #e2e3e5; color: #383d41; padding: 4px 10px; border-radius: 6px; font-weight: bold; font-size: 13px; display: inline-block;">
                                            {{ ucfirst($estadoRes) }}
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    <div style="display: flex; gap: 5px; align-items: center;">
                                        {{-- 1. Ver Detalle / Reimprimir Recibo --}}
                                        <a href="{{ route('reservas.recibo', $idRecibo) }}" target="_blank" class="btn-ver-detalle" title="Ver comprobante de reserva">
                                            Ver
                                        </a>

                                        @if(strtolower($estadoRes) == 'reservado')
                                            {{-- 2. Registrar Entrega --}}
                                            <a href="{{ route('reservas.entregar.form', $idRecibo) }}" class="btn-devolver" title="Registrar entrega de trajes">
                                                Entregar
                                            </a>

                                            {{-- 3. Cancelar Reserva --}}
                                            <form action="{{ route('reservas.cancelar', $idRecibo) }}" method="POST" style="display: inline; margin: 0;" onsubmit="return confirm('¿Está seguro de que desea cancelar esta reserva? Se devolverá el stock disponible.');">
                                                @csrf
                                                <button type="submit" class="btn-accion-reserva" style="background: #E74C3C; color: white;" title="Cancelar reserva">
                                                    Cancelar
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="text-center">No hay reservas registradas por el momento.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

            <!-- 4. PESTAÑA: HISTORIAL -->
            @elseif ($pestaña_actual == 'historial')
                <div class="card">
                    <h2>Historial de Alquileres</h2>
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Nº</th>
                                <th>Cliente</th>
                                <th>Trajes</th>
                                <th style="text-align: center;">Cantidad</th>
                                <th>Fecha de ingreso al historial</th>
                                <th>Usuario</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($historial as $item)
                            @php
                                $idRecibo = is_object($item) ? ($item->numero_recibo ?? $item->idAlquiler) : ($item['numero_recibo'] ?? $item['idAlquiler']);
                                $nomCliente = is_object($item) ? ($item->cliente->nombre_completo ?? $item->cliente) : ($item['cliente']['nombre_completo'] ?? $item['cliente']);
                                $cantTotal = is_object($item) ? ($item->cantidad_total ?? 1) : ($item['cantidad_total'] ?? 1);
                                $fIngreso = is_object($item) ? ($item->fecha_ingreso_historial_formateada ?? 'N/D') : ($item['fecha_ingreso_historial_formateada'] ?? 'N/D');
                                $usuarioNom = is_object($item) ? ($item->usuario ?? 'Erlinda') : ($item['usuario'] ?? 'Erlinda');
                                $detallesHist = is_object($item) ? ($item->detalles ?? collect()) : ($item['detalles'] ?? []);
                            @endphp
                            <tr>
                                <td><strong>#{{ $idRecibo }}</strong></td>
                                <td>{{ $nomCliente }}</td>
                                <td>
                                    @if(count($detallesHist) > 0)
                                        <ul style="margin: 0; padding-left: 18px; list-style-type: disc;">
                                            @foreach($detallesHist as $d)
                                                <li>{{ is_object($d) ? $d->nombre : ($d['nombre'] ?? 'Traje') }}</li>
                                            @endforeach
                                        </ul>
                                    @else
                                        {{ is_object($item) ? ($item->traje ?? 'Sin traje') : ($item['traje'] ?? 'Sin traje') }}
                                    @endif
                                </td>
                                <td style="text-align: center;">
                                    <span class="badge-cantidad">{{ $cantTotal }} {{ $cantTotal == 1 ? 'traje' : 'trajes' }}</span>
                                </td>
                                <td>{{ $fIngreso }}</td>
                                <td>{{ $usuarioNom }}</td>
                                <td>
                                    <a href="{{ route('alquiler.recibo', $idRecibo) }}" target="_blank" class="btn-accion-recibo" title="Ver recibo original">
                                        Ver
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center">No hay registros en el historial actualmente.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>

                    @if(method_exists($historial, 'hasPages') && $historial->hasPages())
                        <div class="paginacion-container">
                            <div class="paginacion-info">
                                Mostrando <strong>{{ $historial->firstItem() }}</strong> - <strong>{{ $historial->lastItem() }}</strong> de <strong>{{ $historial->total() }}</strong> alquileres finalizados
                            </div>
                            <div class="paginacion-links">
                                {{ $historial->links('pagination::bootstrap-5') }}
                            </div>
                        </div>
                    @endif
                </div>
            @endif

        </main>
    </div>

    <!-- MODAL: REGISTRAR DEVOLUCIÓN CON PREGUNTA DE COBRO POR DAÑO -->
    <div id="modalDevolverAlquiler" class="modal-overlay" style="display: none;" onclick="cerrarModalDevolverSiClickFondo(event)">
        <div class="modal-container" onclick="event.stopPropagation();" style="max-width: 520px; border-radius: 8px; overflow: hidden; box-shadow: 0 10px 25px rgba(0,0,0,0.2);">
            <div class="modal-header" style="background: #5b2c6f; color: white; padding: 15px 20px; display: flex; justify-content: space-between; align-items: center;">
                <div style="display: flex; align-items: center; gap: 8px;">
                    <span style="font-size: 20px;">📦</span>
                    <h3 style="margin: 0; font-size: 17px; color: white; font-weight: 600;">Devolución de Alquiler</h3>
                </div>
                <button type="button" onclick="cerrarModalDevolver()" style="background: none; border: none; color: white; font-size: 24px; cursor: pointer; line-height: 1;" title="Cerrar">&times;</button>
            </div>

            <form action="{{ route('alquileres.devolver') }}" method="POST" id="formDevolucionAlquiler">
                @csrf
                <input type="hidden" name="id" id="dev_idAlquiler" value="">
                <input type="hidden" name="monto_garantia_base" id="dev_garantia_base" value="0">
                <input type="hidden" name="monto_devuelto" id="dev_monto_devuelto" value="0">

                <div class="modal-body" style="padding: 20px;">
                    <!-- Ficha resumen del Alquiler -->
                    <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 6px; padding: 12px 15px; margin-bottom: 18px;">
                        <div style="display: flex; justify-content: space-between; margin-bottom: 6px;">
                            <span style="color: #64748b; font-size: 13px;">Nº Alquiler:</span>
                            <strong id="dev_numRecibo" style="color: #1e293b; font-size: 13px;">#--</strong>
                        </div>
                        <div style="display: flex; justify-content: space-between; margin-bottom: 6px;">
                            <span style="color: #64748b; font-size: 13px;">Cliente:</span>
                            <strong id="dev_nomCliente" style="color: #1e293b; font-size: 13px;">--</strong>
                        </div>
                        <div style="display: flex; justify-content: space-between; padding-top: 6px; border-top: 1px dashed #cbd5e1;">
                            <span style="color: #475569; font-size: 13px; font-weight: 600;">Garantía registrada:</span>
                            <strong style="color: #5b2c6f; font-size: 15px;" id="dev_txtGarantia">Bs 0.00</strong>
                        </div>
                    </div>

                    <!-- Pregunta ¿Hubo daño? -->
                    <div style="margin-bottom: 18px;">
                        <label style="display: block; font-weight: 700; margin-bottom: 10px; color: #1e293b; font-size: 14px;">
                            ¿Hubo algún daño en las prendas devueltas?
                        </label>
                        <div style="display: flex; gap: 24px;">
                            <label style="display: flex; align-items: center; gap: 8px; cursor: pointer; font-size: 14px;">
                                <input type="radio" name="hubo_danio" value="no" checked onchange="toggleDanioInputs(false)" style="cursor: pointer; width: 16px; height: 16px;">
                                <span style="color: #166534; font-weight: 600;">No (Sin daño)</span>
                            </label>
                            <label style="display: flex; align-items: center; gap: 8px; cursor: pointer; font-size: 14px;">
                                <input type="radio" name="hubo_danio" value="si" onchange="toggleDanioInputs(true)" style="cursor: pointer; width: 16px; height: 16px;">
                                <span style="color: #dc2626; font-weight: 600;">Sí (Cobrar por daño)</span>
                            </label>
                        </div>
                    </div>

                    <!-- Campos de Daño (condicionales) -->
                    <div id="seccionCamposDanio" style="display: none; background: #fff5f5; border: 1px solid #fed7d7; border-radius: 6px; padding: 14px; margin-bottom: 18px;">
                        <div style="margin-bottom: 12px;">
                            <label style="display: block; font-size: 13px; font-weight: 600; color: #9b2c2c; margin-bottom: 5px;">
                                Monto por cobro de daño (Bs):
                            </label>
                            <input type="number" step="0.50" min="0" name="cobro_danio" id="dev_cobro_danio" value="0" style="width: 100%; box-sizing: border-box; padding: 8px 10px; border: 1px solid #feb2b2; border-radius: 4px; font-size: 14px;" oninput="calcularTotalDevolucion()" placeholder="Ej: 10">
                        </div>
                        <div>
                            <label style="display: block; font-size: 13px; font-weight: 600; color: #9b2c2c; margin-bottom: 5px;">
                                Observación / Detalle del daño:
                            </label>
                            <textarea name="observacion_danio" id="dev_observacion_danio" rows="3" style="width: 100%; box-sizing: border-box; padding: 8px 10px; border: 1px solid #feb2b2; border-radius: 4px; font-size: 13px; font-family: inherit; resize: vertical;" placeholder="Ej: Camisa quemada, botón roto, mancha en la falda..."></textarea>
                        </div>
                    </div>

                    <!-- Total de devolución de garantía al cliente -->
                    <div style="background: #f0fdf4; border: 1.5px solid #86efac; border-radius: 6px; padding: 12px 16px; display: flex; justify-content: space-between; align-items: center;">
                        <span style="font-weight: 700; color: #166534; font-size: 14px;">Total devolución al cliente:</span>
                        <strong id="dev_txtMontoDevuelto" style="color: #15803d; font-size: 18px;">Bs 0.00</strong>
                    </div>
                </div>

                <div class="modal-footer" style="padding: 12px 20px; background: #f8fafc; display: flex; justify-content: flex-end; gap: 10px; border-top: 1px solid #e2e8f0;">
                    <button type="button" onclick="cerrarModalDevolver()" style="padding: 8px 16px; background: #64748b; color: white; border: none; border-radius: 4px; cursor: pointer; font-size: 13px; font-weight: 600;">
                        Cancelar
                    </button>
                    <button type="submit" style="padding: 8px 20px; background: #5b2c6f; color: white; border: none; border-radius: 4px; cursor: pointer; font-size: 13px; font-weight: bold;">
                        Confirmar Devolución
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL: VER DETALLE DE DEVOLUCIÓN -->
    <div id="modalDetalleDevolucion" class="modal-overlay" style="display: none;" onclick="cerrarModalSiClickFondo(event)">
        <div class="modal-container" onclick="event.stopPropagation();">
            <div class="modal-header">
                <div style="display: flex; align-items: center; gap: 8px;">
                    <span style="font-size: 20px;"></span>
                    <h3 id="modalTitulo" style="margin: 0; font-size: 18px; color: #2c3e50;">Detalle de la Devolución</h3>
                </div>
                <button type="button" class="modal-btn-cerrar" onclick="cerrarModalDetalle()" title="Cerrar">&times;</button>
            </div>

            <div class="modal-body">
                <div class="modal-grid-info">
                    <div class="modal-info-item">
                        <span class="modal-info-label">Cliente:</span>
                        <span class="modal-info-valor" id="modalCliente">--</span>
                    </div>
                    <div class="modal-info-item">
                        <span class="modal-info-label">Fecha de Devolución:</span>
                        <span class="modal-info-valor" id="modalFechaDevolucion">--</span>
                    </div>
                    <div class="modal-info-item">
                        <span class="modal-info-label">Fecha de Ingreso al Stock:</span>
                        <span class="modal-info-valor" id="modalFechaIngreso">--</span>
                    </div>
                    <div class="modal-info-item" style="grid-column: 1 / -1;">
                        <span class="modal-info-label">Estado Actual:</span>
                        <span id="modalEstadoBadge"></span>
                    </div>
                </div>

                <div style="margin-top: 18px;">
                    <h4 style="font-size: 13px; color: #475569; margin-bottom: 8px; text-transform: uppercase; letter-spacing: 0.5px; font-weight: 700;">
                        Lista de Trajes Devueltos
                    </h4>
                    <table class="modal-table-trajes">
                        <thead>
                            <tr>
                                <th>TRAJE</th>
                                <th style="text-align: center; width: 130px;">CANTIDAD<th>
                            </tr>
                        </thead>
                        <tbody id="modalListaTrajes">
                            <!-- Inserción dinámica -->
                        </tbody>
                        <tfoot>
                            <tr>
                                <td style="text-align: right; font-weight: bold; background: #f8fafc;">Total trajes:</td>
                                <td style="text-align: center; font-weight: bold; background: #f8fafc;" id="modalTotalPrendas">0</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn-cerrar-modal" onclick="cerrarModalDetalle()">Cerrar</button>
            </div>
        </div>
    </div>

    <script>
        // --- FUNCIONES DEL MODAL DEVOLVER (COBRO DAÑO) ---
        function abrirModalDevolver(id, cliente, montoGarantia) {
            document.getElementById('dev_idAlquiler').value = id;
            document.getElementById('dev_numRecibo').textContent = '#' + id;
            document.getElementById('dev_nomCliente').textContent = cliente;

            var garantia = parseFloat(montoGarantia) || 0;
            document.getElementById('dev_garantia_base').value = garantia;
            document.getElementById('dev_txtGarantia').textContent = 'Bs ' + garantia.toFixed(2);

            // Resetear formulario a "No" (sin daño)
            var radios = document.getElementsByName('hubo_danio');
            if (radios.length > 0) radios[0].checked = true;
            toggleDanioInputs(false);

            document.getElementById('dev_cobro_danio').value = 0;
            document.getElementById('dev_observacion_danio').value = '';

            calcularTotalDevolucion();

            const modal = document.getElementById('modalDevolverAlquiler');
            modal.style.display = 'flex';
            document.body.style.overflow = 'hidden';
        }

        function cerrarModalDevolver() {
            const modal = document.getElementById('modalDevolverAlquiler');
            if (modal) {
                modal.style.display = 'none';
            }
            document.body.style.overflow = 'auto';
        }

        function cerrarModalDevolverSiClickFondo(e) {
            if (e.target.id === 'modalDevolverAlquiler') {
                cerrarModalDevolver();
            }
        }

        function toggleDanioInputs(mostrar) {
            var seccion = document.getElementById('seccionCamposDanio');
            var inputCobro = document.getElementById('dev_cobro_danio');
            var inputObs = document.getElementById('dev_observacion_danio');

            if (mostrar) {
                seccion.style.display = 'block';
                inputObs.required = true;
                setTimeout(function() { inputCobro.focus(); }, 100);
            } else {
                seccion.style.display = 'none';
                inputCobro.value = 0;
                inputObs.value = '';
                inputObs.required = false;
            }
            calcularTotalDevolucion();
        }

        function calcularTotalDevolucion() {
            var garantia = parseFloat(document.getElementById('dev_garantia_base').value) || 0;
            var radioSi = document.querySelector('input[name="hubo_danio"][value="si"]');
            var huboDanio = radioSi && radioSi.checked;
            var cobroDanio = huboDanio ? (parseFloat(document.getElementById('dev_cobro_danio').value) || 0) : 0;

            var totalDevolver = Math.max(0, garantia - cobroDanio);

            document.getElementById('dev_monto_devuelto').value = totalDevolver.toFixed(2);
            document.getElementById('dev_txtMontoDevuelto').textContent = 'Bs ' + totalDevolver.toFixed(2);
        }

        function mostrarModalDetalle(boton) {
            const id = boton.getAttribute('data-id');
            const cliente = boton.getAttribute('data-cliente');
            const fdevolucion = boton.getAttribute('data-fdevolucion');
            const fingreso = boton.getAttribute('data-fingreso');
            const estado = boton.getAttribute('data-estado');
            let trajes = [];
            try {
                trajes = JSON.parse(boton.getAttribute('data-trajes') || '[]');
            } catch(e) {
                trajes = [];
            }
            const cantidad = boton.getAttribute('data-cantidad');

            document.getElementById('modalTitulo').innerText = 'Detalle de Devolución Nº ' + id;
            document.getElementById('modalCliente').innerText = cliente;
            document.getElementById('modalFechaDevolucion').innerText = fdevolucion;
            document.getElementById('modalFechaIngreso').innerText = fingreso;

            const badgeCont = document.getElementById('modalEstadoBadge');
            if (estado === 'En lavado') {
                badgeCont.innerHTML = '<span class="badge-en-lavado">En lavado</span>';
            } else {
                badgeCont.innerHTML = '<span class="badge-ingresado-stock">Ingresado al stock</span>';
            }

            const tbody = document.getElementById('modalListaTrajes');
            tbody.innerHTML = '';

            let total = 0;
            if (trajes && trajes.length > 0) {
                trajes.forEach(t => {
                    const cant = parseInt(t.cantidad || 1);
                    total += cant;
                    const tr = document.createElement('tr');
                    tr.innerHTML = `
                        <td style="padding: 9px 12px; border-bottom: 1px solid #e2e8f0;"><strong>${t.nombre || 'Traje'}</strong></td>
                        <td style="padding: 9px 12px; border-bottom: 1px solid #e2e8f0; text-align: center;"><span class="badge-cantidad">${cant} ${cant === 1 ? 'traje' : 'trajes'}</span></td>
                    `;
                    tbody.appendChild(tr);
                });
            } else {
                tbody.innerHTML = '<tr><td colspan="2" style="text-align: center; color: #94a3b8; padding: 12px;">Sin trajes asociados</td></tr>';
            }

            document.getElementById('modalTotalPrendas').innerText = (total > 0 ? total : cantidad) + ' trajes';

            const modal = document.getElementById('modalDetalleDevolucion');
            modal.style.display = 'flex';
            document.body.style.overflow = 'hidden';
        }

        function cerrarModalDetalle() {
            const modal = document.getElementById('modalDetalleDevolucion');
            if (modal) {
                modal.style.display = 'none';
            }
            document.body.style.overflow = 'auto';
        }

        function cerrarModalSiClickFondo(e) {
            if (e.target.id === 'modalDetalleDevolucion') {
                cerrarModalDetalle();
            }
        }

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                cerrarModalDetalle();
                cerrarModalDevolver();
            }
        });
    </script>
</body>
</html>