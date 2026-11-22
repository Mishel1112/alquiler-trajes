<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar Alquiler - Creaciones y Fantasías Erlinda</title>
    <link rel="stylesheet" href="{{ asset('css/registrar.css') }}">
    <style>
        .input-grupo-bs {
            display: flex;
            align-items: center;
            border: 1px solid #ccc;
            border-radius: 4px;
            overflow: hidden;
            background: #fff;
        }
        .input-grupo-bs span {
            background: #eee;
            padding: 8px 12px;
            font-weight: bold;
            color: #333;
            border-right: 1px solid #ccc;
        }
        .input-grupo-bs input {
            border: none !important;
            outline: none;
            padding: 8px;
            width: 100%;
        }
    </style>
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
        <a href="{{ route('alquiler.seleccionar') }}" class="menu-item activo">
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
            <button type="submit" style="background: none; border: none; color: #333; cursor: pointer; display: flex; align-items: center; gap: 10px; font-size: 15px;">
                <span>🚪</span>
                <span>Salir</span>
            </button>
        </form>
    </aside>

    <!-- CONTENIDO CENTRAL DINÁMICO -->
    <main class="contenido">
        <div class="alquiler-container">
            
            <div class="header-titulo" style="display: flex; justify-content: space-between; align-items: center;">
                <h1>Recibo Nº {{ $nextId }}</h1>
                @if(session('reserva_a_entregar'))
                    <a href="{{ route('alquileres', ['tab' => 'reservas']) }}" style="color: #4a5568; text-decoration: none; font-size: 13px; font-weight: bold; background: #e2e8f0; padding: 7px 15px; border-radius: 4px; display: inline-flex; align-items: center; gap: 5px;">
                        ← Volver a Reservas
                    </a>
                @endif
            </div>
            <!-- Alerta de éxito o error -->
            @if(session('success'))
                <div style="background: #d4edda; color: #155724; padding: 10px; margin-bottom: 15px; border-radius: 5px;">
                    {{ session('success') }}
                </div>
            @endif
            @if($errors->any())
                <div style="background: #f8d7da; color: #721c24; padding: 10px; margin-bottom: 15px; border-radius: 5px;">
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('alquiler.store') }}" id="form-alquiler">
                @csrf

                <input type="hidden" name="numero_recibo" value="{{ $nextId }}">
                <!-- CAMPOS OCULTOS CLAVE PARA CAPTURAR LA RESERVA O ALQUILER -->
                <input type="hidden" name="tipo_operacion" value="{{ request('tipo_operacion', 'activo') }}">
                @if(session('reserva_a_entregar'))
                    <input type="hidden" name="idreserva" value="{{ session('reserva_a_entregar') }}">
                @endif

                <div class="layout-grid">
                    
                    <!-- Columna Izquierda: Datos del Cliente y Prendas -->
                    <div class="col-izquierda">
                        
                        <fieldset class="seccion-bloque">
                            <legend>DATOS DEL CLIENTE</legend>
                            
                            @php
                                $datosReserva = session('datos_reserva_cliente');
                                if ($datosReserva) {
                                    $datosReserva = (object) $datosReserva;
                                }
                                $tipoClienteActual = old('tipo_cliente', $datosReserva->tipo_cliente ?? '');
                                $nombreActual = old('nombre', $datosReserva->nombre ?? '');
                                $telefonoActual = old('telefono', $datosReserva->telefono ?? '');
                                $colegioActual = old('colegio', $datosReserva->colegio ?? '');
                                $cursoActual = old('curso', $datosReserva->curso ?? '');
                                $fraternidadActual = old('fraternidad', $datosReserva->fraternidad ?? '');
                                $lugarBaileActual = old('lugar_baile', $datosReserva->lugar_baile ?? '');

                                $opParam = request('tipoOperacion') ?? request('tipo_operacion');
                                $tipoOperacionSeleccionada = 'Alquiler';
                            @endphp

                            <!-- Tipo de Operación: Alquiler (No editable) -->
                            <div class="fila-campos" style="margin-bottom: 15px;">
                                <div class="campo flex-grow">
                                    <label for="tipoOperacion" style="font-weight: bold; font-size: 13px; color: #1e293b; margin-bottom: 5px; display: block;">
                                        Tipo de operación:
                                    </label>
                                    @if(session('reserva_a_entregar'))
                                        <input type="text" value="Alquiler (Entrega de reserva confirmada)" readonly style="width: 100%; padding: 8px 12px; font-size: 14px; font-weight: bold; border-radius: 4px; border: 1.5px solid #cbd5e1; background: #f1f5f9; color: #475569; cursor: not-allowed; box-sizing: border-box;">
                                    @else
                                        <input type="text" value="Alquiler" readonly style="width: 100%; padding: 8px 12px; font-size: 14px; font-weight: bold; border-radius: 4px; border: 1.5px solid #cbd5e1; background: #f1f5f9; color: #475569; cursor: not-allowed; box-sizing: border-box;">
                                    @endif
                                    <input type="hidden" id="tipoOperacion" name="tipoOperacion" value="Alquiler">
                                </div>
                            </div>

                            <!-- Tipo de Cliente -->
                            <div class="fila-campos" style="margin-bottom: 15px;">
                                <div class="campo flex-grow">
                                    <label for="tipo_cliente">Tipo de cliente:</label>
                                    <select id="tipo_cliente" name="tipo_cliente" onchange="cambiarTipoCliente()" required>
                                        <option value="">Seleccionar tipo de cliente</option>
                                        <option value="estudiante" {{ $tipoClienteActual == 'estudiante' ? 'selected' : '' }}>Estudiante</option>
                                        <option value="fraternidad" {{ $tipoClienteActual == 'fraternidad' ? 'selected' : '' }}>Integrante de fraternidad</option>
                                        <option value="publico" {{ $tipoClienteActual == 'publico' ? 'selected' : '' }}>Público general</option>
                                        <option value="coreografo" {{ $tipoClienteActual == 'coreografo' ? 'selected' : '' }}>Coreógrafo</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Nombre y Teléfono -->
                            <div class="fila-campos">
                                <div class="campo flex-grow">
                                    <label for="nombre">Nombre completo:</label>
                                    <input type="text" id="nombre" name="nombre" value="{{ $nombreActual }}" placeholder="" required>
                                </div>
                            </div>

                            <div class="fila-campos" style="margin-top: 10px;">
                                <div class="campo">
                                    <label for="telefono">Teléfono:</label>
                                    <input type="text" id="telefono" name="telefono" value="{{ $telefonoActual }}" placeholder="">
                                </div>
                            </div>

                            <!-- Campos dinámicos para Estudiante -->
                            <div id="bloque-estudiante" class="fila-campos dinamico-cliente" style="display: {{ $tipoClienteActual == 'estudiante' ? 'flex' : 'none' }}; gap: 15px; margin-top: 10px;">
                                <div class="campo" style="flex: 1;">
                                    <label for="colegio">Colegio:</label>
                                    <input type="text" id="colegio" name="colegio" value="{{ $colegioActual }}" placeholder="">
                                </div>   
                                <div class="campo" style="flex: 1;">
                                    <label for="curso">Curso:</label>
                                    <select id="curso" name="curso">
                                        <option value="">Seleccionar</option>
                                        <optgroup label="Inicial">
                                            <option value="Prekínder A" {{ $cursoActual == 'Prekínder A' ? 'selected' : '' }}>Prekínder "A"</option>
                                            <option value="Prekínder B" {{ $cursoActual == 'Prekínder B' ? 'selected' : '' }}>Prekínder "B"</option>
                                            <option value="Prekínder C" {{ $cursoActual == 'Prekínder C' ? 'selected' : '' }}>Prekínder "C"</option>
                                            <option value="Kínder A" {{ $cursoActual == 'Kínder A' ? 'selected' : '' }}>Kínder "A"</option>
                                            <option value="Kínder B" {{ $cursoActual == 'Kínder B' ? 'selected' : '' }}>Kínder "B"</option>
                                            <option value="Kínder C" {{ $cursoActual == 'Kínder C' ? 'selected' : '' }}>Kínder "C"</option>
                                        </optgroup>
                                        <optgroup label="Primaria">
                                            <option value="1° Primaria A" {{ $cursoActual == '1° Primaria A' ? 'selected' : '' }}>1° Primaria "A"</option>
                                            <option value="1° Primaria B" {{ $cursoActual == '1° Primaria B' ? 'selected' : '' }}>1° Primaria "B"</option>
                                            <option value="1° Primaria C" {{ $cursoActual == '1° Primaria C' ? 'selected' : '' }}>1° Primaria "C"</option>
                                            <option value="2° Primaria A" {{ $cursoActual == '2° Primaria A' ? 'selected' : '' }}>2° Primaria "A"</option>
                                            <option value="2° Primaria B" {{ $cursoActual == '2° Primaria B' ? 'selected' : '' }}>2° Primaria "B"</option>
                                            <option value="2° Primaria C" {{ $cursoActual == '2° Primaria C' ? 'selected' : '' }}>2° Primaria "C"</option>
                                            <option value="3° Primaria A" {{ $cursoActual == '3° Primaria A' ? 'selected' : '' }}>3° Primaria "A"</option>
                                            <option value="3° Primaria B" {{ $cursoActual == '3° Primaria B' ? 'selected' : '' }}>3° Primaria "B"</option>
                                            <option value="3° Primaria C" {{ $cursoActual == '3° Primaria C' ? 'selected' : '' }}>3° Primaria "C"</option>
                                            <option value="4° Primaria A" {{ $cursoActual == '4° Primaria A' ? 'selected' : '' }}>4° Primaria "A"</option>
                                            <option value="4° Primaria B" {{ $cursoActual == '4° Primaria B' ? 'selected' : '' }}>4° Primaria "B"</option>
                                            <option value="4° Primaria C" {{ $cursoActual == '4° Primaria C' ? 'selected' : '' }}>4° Primaria "C"</option>
                                            <option value="5° Primaria A" {{ $cursoActual == '5° Primaria A' ? 'selected' : '' }}>5° Primaria "A"</option>
                                            <option value="5° Primaria B" {{ $cursoActual == '5° Primaria B' ? 'selected' : '' }}>5° Primaria "B"</option>
                                            <option value="5° Primaria C" {{ $cursoActual == '5° Primaria C' ? 'selected' : '' }}>5° Primaria "C"</option>
                                            <option value="6° Primaria A" {{ $cursoActual == '6° Primaria A' ? 'selected' : '' }}>6° Primaria "A"</option>
                                            <option value="6° Primaria B" {{ $cursoActual == '6° Primaria B' ? 'selected' : '' }}>6° Primaria "B"</option>
                                            <option value="6° Primaria C" {{ $cursoActual == '6° Primaria C' ? 'selected' : '' }}>6° Primaria "C"</option>
                                        </optgroup>
                                        <optgroup label="Secundaria">
                                            <option value="1° Secundaria A" {{ $cursoActual == '1° Secundaria A' ? 'selected' : '' }}>1° Secundaria "A"</option>
                                            <option value="1° Secundaria B" {{ $cursoActual == '1° Secundaria B' ? 'selected' : '' }}>1° Secundaria "B"</option>
                                            <option value="1° Secundaria C" {{ $cursoActual == '1° Secundaria C' ? 'selected' : '' }}>1° Secundaria "C"</option>
                                            <option value="2° Secundaria A" {{ $cursoActual == '2° Secundaria A' ? 'selected' : '' }}>2° Secundaria "A"</option>
                                            <option value="2° Secundaria B" {{ $cursoActual == '2° Secundaria B' ? 'selected' : '' }}>2° Secundaria "B"</option>
                                            <option value="2° Secundaria C" {{ $cursoActual == '2° Secundaria C' ? 'selected' : '' }}>2° Secundaria "C"</option>
                                            <option value="3° Secundaria A" {{ $cursoActual == '3° Secundaria A' ? 'selected' : '' }}>3° Secundaria "A"</option>
                                            <option value="3° Secundaria B" {{ $cursoActual == '3° Secundaria B' ? 'selected' : '' }}>3° Secundaria "B"</option>
                                            <option value="3° Secundaria C" {{ $cursoActual == '3° Secundaria C' ? 'selected' : '' }}>3° Secundaria "C"</option>
                                            <option value="4° Secundaria A" {{ $cursoActual == '4° Secundaria A' ? 'selected' : '' }}>4° Secundaria "A"</option>
                                            <option value="4° Secundaria B" {{ $cursoActual == '4° Secundaria B' ? 'selected' : '' }}>4° Secundaria "B"</option>
                                            <option value="4° Secundaria C" {{ $cursoActual == '4° Secundaria C' ? 'selected' : '' }}>4° Secundaria "C"</option>
                                            <option value="5° Secundaria A" {{ $cursoActual == '5° Secundaria A' ? 'selected' : '' }}>5° Secundaria "A"</option>
                                            <option value="5° Secundaria B" {{ $cursoActual == '5° Secundaria B' ? 'selected' : '' }}>5° Secundaria "B"</option>
                                            <option value="5° Secundaria C" {{ $cursoActual == '5° Secundaria C' ? 'selected' : '' }}>5° Secundaria "C"</option>
                                            <option value="6° Secundaria A" {{ $cursoActual == '6° Secundaria A' ? 'selected' : '' }}>6° Secundaria "A"</option>
                                            <option value="6° Secundaria B" {{ $cursoActual == '6° Secundaria B' ? 'selected' : '' }}>6° Secundaria "B"</option>
                                            <option value="6° Secundaria C" {{ $cursoActual == '6° Secundaria C' ? 'selected' : '' }}>6° Secundaria "C"</option>
                                        </optgroup>
                                        @if(!empty($cursoActual) && !in_array($cursoActual, ['Prekínder A','Prekínder B','Prekínder C','Kínder A','Kínder B','Kínder C','1° Primaria A','1° Primaria B','1° Primaria C','2° Primaria A','2° Primaria B','2° Primaria C','3° Primaria A','3° Primaria B','3° Primaria C','4° Primaria A','4° Primaria B','4° Primaria C','5° Primaria A','5° Primaria B','5° Primaria C','6° Primaria A','6° Primaria B','6° Primaria C','1° Secundaria A','1° Secundaria B','1° Secundaria C','2° Secundaria A','2° Secundaria B','2° Secundaria C','3° Secundaria A','3° Secundaria B','3° Secundaria C','4° Secundaria A','4° Secundaria B','4° Secundaria C','5° Secundaria A','5° Secundaria B','5° Secundaria C','6° Secundaria A','6° Secundaria B','6° Secundaria C']))
                                            <option value="{{ $cursoActual }}" selected>{{ $cursoActual }}</option>
                                        @endif
                                    </select>
                                </div>
                            </div>

                            <!-- Campo dinámico para Fraternidad -->
                            <div id="bloque-fraternidad" class="fila-campos dinamico-cliente" style="display: {{ $tipoClienteActual == 'fraternidad' ? 'block' : 'none' }}; margin-top: 10px;">
                                <div class="campo flex-grow">
                                    <label for="fraternidad">Nombre de la fraternidad:</label>
                                    <input type="text" id="fraternidad" name="fraternidad" value="{{ $fraternidadActual }}" placeholder="Ej. Fraternidad 100% Caporales">
                                </div>
                            </div>

                            <!-- Campo dinámico para Coreógrafo -->
                            <div id="bloque-coreografo" class="fila-campos dinamico-cliente" style="display: {{ $tipoClienteActual == 'coreografo' ? 'block' : 'none' }}; margin-top: 10px;">
                                <div class="campo flex-grow">
                                    <label for="lugar_baile">Lugar de presentación / baile:</label>
                                    <input type="text" id="lugar_baile" name="lugar_baile" value="{{ $lugarBaileActual }}" placeholder="Ej. Corso Cruceño / Entrada Folklórica">
                                </div>
                            </div>

                        </fieldset>

                        <fieldset class="seccion-bloque">
                            <legend>DETALLE DE TRAJES</legend>
                            <table class="tabla-prendas">
                                <thead>
                                    <tr>
                                        <th>Traje</th>
                                        <th>Cant.</th>
                                        <th>Precio U.</th>
                                        <th>Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $detallesReservaSession = session('detalles_reserva_trajes');
                                        $totalGeneral = 0;
                                        $trajesAgrupados = [];

                                        if ($detallesReservaSession && count($detallesReservaSession) > 0) {
                                            foreach ($detallesReservaSession as $itemRes) {
                                                $idTraje = $itemRes->idTraje;
                                                $cant = intval($itemRes->cantidad ?? 1);
                                                $precioU = floatval($itemRes->precio ?? 0);
                                                $talla = $itemRes->talla ?? 'Única';

                                                if (isset($trajesAgrupados[$idTraje])) {
                                                    $trajesAgrupados[$idTraje]['cant_total'] += $cant;
                                                    $trajesAgrupados[$idTraje]['detalles_tallas'][] = "{$talla}: {$cant}";
                                                } else {
                                                    $traje = \App\Models\Traje::find($idTraje);
                                                    $trajesAgrupados[$idTraje] = [
                                                        'id' => $idTraje,
                                                        'nombre' => $itemRes->nombre ?? ($traje->nombre ?? 'Traje Seleccionado'),
                                                        'cant_total' => $cant,
                                                        'precio_u' => $precioU,
                                                        'detalles_tallas' => ["{$talla}: {$cant}"],
                                                        'cant_piezas' => $itemRes->cantidadPiezas ?? ($traje->cant_piezas ?? 7),
                                                        'descripcion' => $itemRes->piezas ?? ($traje->descripcion ?? '')
                                                    ];
                                                }
                                            }
                                        } else {
                                            $idTrajes = request('idTraje', []);
                                            $cantidades = request('cant', []);
                                            $precios = request('precio_u', []);
                                            $tallasInput = request('talla', []); 
                                            $cantPiezasInput = request('cant_piezas', []); 
                                            $piezasInput = request('piezas', []);       
                                            
                                            if (count($idTrajes) > 0) {
                                                foreach ($idTrajes as $index => $idTraje) {
                                                    $cant = intval($cantidades[$index] ?? 1);
                                                    $precioU = floatval($precios[$index] ?? 0);
                                                    $talla = $tallasInput[$index] ?? 'Única';

                                                    if (isset($trajesAgrupados[$idTraje])) {
                                                        $trajesAgrupados[$idTraje]['cant_total'] += $cant;
                                                        $trajesAgrupados[$idTraje]['detalles_tallas'][] = "{$talla}: {$cant}";
                                                    } else {
                                                        $traje = \App\Models\Traje::find($idTraje);
                                                        $trajesAgrupados[$idTraje] = [
                                                            'id' => $idTraje,
                                                            'nombre' => $traje->nombre ?? 'Traje Seleccionado',
                                                            'cant_total' => $cant,
                                                            'precio_u' => $precioU,
                                                            'detalles_tallas' => ["{$talla}: {$cant}"],
                                                            'cant_piezas' => $cantPiezasInput[$index] ?? ($traje->cant_piezas ?? 7),
                                                            'descripcion' => $piezasInput[$index] ?? ($traje->descripcion ?? '')
                                                        ];
                                                    }
                                                }
                                            }
                                        }
                                    @endphp

                                    @if(count($trajesAgrupados) > 0)
                                        @php $contador = 1; @endphp
                                        @foreach($trajesAgrupados as $idTraje => $item)
                                            @php
                                                $subtotal = $item['cant_total'] * $item['precio_u'];
                                                $totalGeneral += $subtotal;
                                            @endphp
                                            <tr>
                                                <td>
                                                    {{ $contador }}. {{ $item['nombre'] }}
                                                    @if(!empty($item['descripcion']))
                                                        <br><small style="color: #666;">Accesorios: {{ $item['descripcion'] }}</small>
                                                    @endif
                                                    @php
                                                        $tallasTxt = implode(', ', $item['detalles_tallas']);
                                                    @endphp
                                                    @if(!empty($tallasTxt) && !str_starts_with($tallasTxt, 'Única'))
                                                        <br><small style="color: #27ae60; font-weight: bold;">Tallas: {{ $tallasTxt }}</small>
                                                    @else
                                                        <br><small style="color: #27ae60; font-weight: bold;">Talla: Única / Estándar</small>
                                                    @endif
                                                    
                                                    <input type="hidden" name="id_traje[]" value="{{ $idTraje }}">
                                                    <input type="hidden" name="talla[]" value="{{ implode(', ', $item['detalles_tallas']) }}">
                                                    <input type="hidden" name="precio_u[]" value="{{ $item['precio_u'] }}">
                                                    <input type="hidden" name="cant[]" value="{{ $item['cant_total'] }}">
                                                    <input type="hidden" name="cant_piezas[]" value="{{ $item['cant_piezas'] }}">
                                                    <input type="hidden" name="piezas[]" value="{{ $item['descripcion'] }}">
                                                </td>
                                                <td>
                                                    <span style="font-weight: bold;">{{ $item['cant_total'] }}</span>
                                                </td>
                                                <td>Bs {{ number_format($item['precio_u'], 2) }}</td>
                                                <td><strong>Bs {{ number_format($subtotal, 2) }}</strong></td>
                                            </tr>
                                            @php $contador++; @endphp
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="4" style="text-align: center; color: #7f8c8d;">No hay trajes seleccionados.</td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </fieldset>

                    </div>

                    <!-- Columna Derecha: Fechas, Garantía y Total -->
                    <div class="col-derecha">
                        
                        <fieldset class="seccion-bloque">
                            <legend id="legend-fechas">FECHAS DE ALQUILER</legend>
                            <div class="campo-linea">
                                <label id="label-recogida">Recogida:</label>
                                <input type="text" name="recogida" value="{{ \Carbon\Carbon::now('America/La_Paz')->format('d/m/Y, H:i') }}" readonly style="font-family:inherit; font-size:14px; background-color: #f9f9f9; cursor: not-allowed;">
                            </div>

                            <div class="campo-linea">
                                <label id="label-devolucion">Devolución:</label>
                                <input type="date" name="fecha_devolucion" id="input_fecha_devolucion" value="{{ old('fecha_devolucion', (isset($datosReserva->fecha_programada) && !empty($datosReserva->fecha_programada)) ? \Carbon\Carbon::parse($datosReserva->fecha_programada)->format('Y-m-d') : date('Y-m-d', strtotime('+1 day'))) }}" style="font-family: inherit; font-size: 14px;" required>
                            </div>
                        </fieldset>

                        <fieldset class="seccion-bloque garantia-bloque">
                            <legend id="legend-garantia">GARANTÍA</legend>
                            
                            @if(isset($datosReserva) && !empty($datosReserva->seña_monto) && $datosReserva->seña_monto > 0)
                                <div style="background: #eafaf1; border: 1px solid #2ecc71; color: #27ae60; padding: 8px 12px; border-radius: 4px; margin-bottom: 12px; font-size: 13px;">
                                    <strong>ℹ️ Seña pagada en reserva:</strong> Bs {{ number_format($datosReserva->seña_monto, 2) }}
                                    @if(!empty($datosReserva->seña_descripcion))
                                        <br><span style="font-size: 11px; color: #555;">Observación: {{ $datosReserva->seña_descripcion }}</span>
                                    @endif
                                </div>
                            @endif

                            <div id="bloque-opciones-garantia">
                                <div class="campo-radio">
                                    <label><strong>Tipo de Garantía:</strong></label><br>
                                    <label style="margin-right: 15px;">
                                        <input type="radio" name="tipo_garantia_opcion" value="Dinero" id="radio-dinero" onchange="cambiarTipoGarantia()" checked> Dinero (Bs + Carnet)
                                    </label>
                                    <label>
                                        <input type="radio" name="tipo_garantia_opcion" value="Objeto" id="radio-objeto" onchange="cambiarTipoGarantia()"> Objeto de Valor
                                    </label>
                                </div>
                            </div>

                            <div class="campo" id="contenedor-monto" style="margin-top: 10px; display: block;">
                                <label for="monto" id="label-monto">Monto de Garantía:</label>
                                <div class="input-grupo-bs">
                                    <span>Bs</span>
                                    <input type="number" id="monto" name="monto" value="{{ old('monto') }}" step="1" placeholder="Ej. 100">
                                </div>
                            </div>

                            <div class="campo" style="margin-top: 10px;">
                                <label for="descripcion_garantia" id="label-descripcion">Documento u Objeto</label>
                                <input type="text" id="descripcion_garantia" name="descripcion" value="{{ old('descripcion') }}" placeholder="Ej. Carnet de Identidad">
                            </div>

                            <input type="hidden" name="tipo" id="tipo_garantia_hidden" value="Dinero">

                            <div class="campo" style="margin-top: 10px;">
                                <label for="observaciones" style="font-weight: bold; font-size: 13px; color: #1e293b; margin-bottom: 5px; display: block;">Observaciones:</label>
                                <textarea id="observaciones" name="observaciones" rows="3" style="width: 100%; padding: 8px 10px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; font-family: inherit; font-size: 13px; resize: vertical;" placeholder="Observaciones adicionales sobre el alquiler o las prendas...">{{ old('observaciones') }}</textarea>
                            </div>

                            <div class="total-caja" style="margin-top: 10px;">
                                <strong>TOTAL: Bs {{ number_format($totalGeneral ?? 0, 2) }}</strong>
                            </div>
                        </fieldset>

                    </div>
                </div>

                <div class="footer-accion" style="display: flex; gap: 10px; justify-content: flex-end;">
                    <button type="button" onclick="abrirVistaPrevia()" class="btn-verizar" style="background: #6c757d; color: #fff; border: none; padding: 10px 20px; border-radius: 4px; cursor: pointer;">
                        Vista previa
                    </button>
                    <button type="submit" id="btn-submit-guardar" class="btn-guardar">{{ session('reserva_a_entregar') ? 'ENTREGAR Y GUARDAR ALQUILER ✔' : 'GUARDAR ALQUILER ✔' }}</button>
                </div>

            </form>
        </div>
    </main>

    <!-- SCRIPTS DE CONTROL -->
    <script>
        function cambiarTipoOperacion() {
            const selectOp = document.getElementById('tipoOperacion');
            if (!selectOp) return;
            const op = selectOp.value; // 'Alquiler' o 'Reserva'
            
            const legendFechas = document.getElementById('legend-fechas');
            const labelRecogida = document.getElementById('label-recogida');
            const labelDevolucion = document.getElementById('label-devolucion');
            const legendGarantia = document.getElementById('legend-garantia');
            const bloqueOpcionesGarantia = document.getElementById('bloque-opciones-garantia');
            const contenedorMonto = document.getElementById('contenedor-monto');
            const labelMonto = document.getElementById('label-monto');
            const labelDesc = document.getElementById('label-descripcion');
            const inputDesc = document.getElementById('descripcion_garantia');
            const inputTipoHidden = document.getElementById('tipo_garantia_hidden');
            const btnSubmit = document.getElementById('btn-submit-guardar');
            const tipoOperacionHidden = document.querySelector('input[name="tipo_operacion"]');

            if (op === 'Reserva') {
                if (legendFechas) legendFechas.innerText = 'FECHAS DE RESERVA';
                if (labelRecogida) labelRecogida.innerText = 'Fecha de reserva:';
                if (labelDevolucion) labelDevolucion.innerText = 'Fecha programada de uso/entrega:';
                if (legendGarantia) legendGarantia.innerText = 'MONTO DE RESERVA (SEÑA / ANTICIPO)';
                if (bloqueOpcionesGarantia) bloqueOpcionesGarantia.style.display = 'none';
                if (contenedorMonto) contenedorMonto.style.display = 'block';
                if (labelMonto) labelMonto.innerText = 'Monto de Seña / Anticipo:';
                if (labelDesc) labelDesc.innerText = 'Observación / Detalle de la seña:';
                if (inputDesc) inputDesc.placeholder = 'Ej. Seña para apartar traje, saldo al recoger';
                if (inputTipoHidden) inputTipoHidden.value = 'Seña / Anticipo';
                if (tipoOperacionHidden) tipoOperacionHidden.value = 'reservado';
                if (btnSubmit) {
                    btnSubmit.innerText = 'GUARDAR RESERVA ✔';
                    btnSubmit.style.background = '#2980b9';
                }
            } else {
                if (legendFechas) legendFechas.innerText = 'FECHAS DE ALQUILER';
                if (labelRecogida) labelRecogida.innerText = 'Recogida:';
                if (labelDevolucion) labelDevolucion.innerText = 'Devolución:';
                if (legendGarantia) legendGarantia.innerText = 'GARANTÍA';
                if (bloqueOpcionesGarantia) bloqueOpcionesGarantia.style.display = 'block';
                cambiarTipoGarantia(); // Restablece etiquetas según radio Dinero/Objeto
                if (tipoOperacionHidden) tipoOperacionHidden.value = 'activo';
                if (btnSubmit) {
                    btnSubmit.innerText = "{{ session('reserva_a_entregar') ? 'ENTREGAR Y GUARDAR ALQUILER ✔' : 'GUARDAR ALQUILER ✔' }}";
                    btnSubmit.style.background = '';
                }
            }
        }

        function cambiarTipoCliente() {
            const tipoCliente = document.getElementById('tipo_cliente').value;
            const bloqueEstudiante = document.getElementById('bloque-estudiante');
            const bloqueFraternidad = document.getElementById('bloque-fraternidad');
            const bloqueCoreografo = document.getElementById('bloque-coreografo');

            bloqueEstudiante.style.display = 'none';
            bloqueFraternidad.style.display = 'none';
            bloqueCoreografo.style.display = 'none';

            if (tipoCliente === 'estudiante') {
                bloqueEstudiante.style.display = 'flex';
            } else if (tipoCliente === 'fraternidad') {
                bloqueFraternidad.style.display = 'block';
            } else if (tipoCliente === 'coreografo') {
                bloqueCoreografo.style.display = 'block';
            }
        }

        function cambiarTipoGarantia() {
            const selectOp = document.getElementById('tipoOperacion');
            if (selectOp && selectOp.value === 'Reserva') {
                return; // Si es reserva, se mantiene como seña
            }
            const radioDinero = document.getElementById('radio-dinero');
            const contenedorMonto = document.getElementById('contenedor-monto');
            const inputMonto = document.getElementById('monto');
            const inputDesc = document.getElementById('descripcion_garantia');
            const labelDesc = document.getElementById('label-descripcion');
            const labelMonto = document.getElementById('label-monto');
            const inputTipoHidden = document.getElementById('tipo_garantia_hidden');

            if (labelMonto) labelMonto.innerText = 'Monto de Garantía:';

            if (radioDinero && radioDinero.checked) {
                if (contenedorMonto) contenedorMonto.style.display = 'block';
                if (labelDesc) labelDesc.innerText = 'Documento u Objeto';
                if (inputDesc) inputDesc.placeholder = 'Ej. Carnet de Identidad';
                if (inputTipoHidden) inputTipoHidden.value = 'Dinero';
            } else {
                if (contenedorMonto) contenedorMonto.style.display = 'none';
                if (inputMonto) inputMonto.value = '0';
                if (labelDesc) labelDesc.innerText = '¿Qué objeto de valor está dejando?';
                if (inputDesc) inputDesc.placeholder = 'Ej. Celular Samsung A52';
                if (inputTipoHidden) inputTipoHidden.value = 'Objeto de Valor';
            }
        }

        function abrirVistaPrevia() {
            const formOriginal = document.getElementById('form-alquiler') || document.querySelector('form[action*="alquiler"]');
            if (!formOriginal) return;
            
            const formTemp = document.createElement('form');
            formTemp.action = "{{ route('alquileres.vista_previa') }}";
            formTemp.method = 'POST';
            formTemp.target = '_blank';

            const csrfInput = formOriginal.querySelector('input[name="_token"]') || document.querySelector('input[name="_token"]');
            const csrfToken = csrfInput ? csrfInput.value : '';
            const inputCsrf = document.createElement('input');
            inputCsrf.type = 'hidden';
            inputCsrf.name = '_token';
            inputCsrf.value = csrfToken;
            formTemp.appendChild(inputCsrf);

            const elementos = formOriginal.querySelectorAll('input, select, textarea');
            elementos.forEach(el => {
                if (el.name && el.name !== '_token') {
                    if ((el.type === 'radio' || el.type === 'checkbox') && !el.checked) {
                        return;
                    }
                    const clone = document.createElement('input');
                    clone.type = 'hidden';
                    clone.name = el.name;
                    clone.value = el.value;
                    formTemp.appendChild(clone);
                }
            });

            document.body.appendChild(formTemp);
            formTemp.submit();
            document.body.removeChild(formTemp);
        }

        document.addEventListener('DOMContentLoaded', function() {
            cambiarTipoOperacion();
        });
    </script>

</body>
</html>