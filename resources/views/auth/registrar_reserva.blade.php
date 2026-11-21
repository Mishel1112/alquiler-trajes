<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar Reserva - Creaciones y Fantasías Erlinda</title>
    <link rel="stylesheet" href="{{ asset('css/registrar.css') }}">
    <link rel="stylesheet" href="{{ asset('css/registrar_reserva.css') }}">
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
        <div style="display: flex; align-items: center; gap: 10px;">
            <span class="figura"></span>
            <span class="titulo-header">Creaciones y Fantasías Erlinda</span>
        </div>
        <div style="display: flex; align-items: center; gap: 15px;">
            <a href="{{ url()->previous() }}" class="btn-regresar" style="color: white; text-decoration: none; margin-right: 5px;">← Volver</a>
            <span class="user-role" style="color: white; font-weight: normal; font-size: 14px; display: inline-flex; align-items: center; gap: 5px;">
                👤 {{ auth()->check() ? trim(auth()->user()->nombre . ' ' . (auth()->user()->apellido ?? '')) : 'Erlinda Pedrazas' }}
            </span>
            <form action="{{ route('logout') }}" method="POST" style="margin: 0;">
                @csrf
                <button type="submit" style="background: white; color: #e24f22; border: none; padding: 6px 14px; border-radius: 6px; font-size: 13px; font-weight: bold; cursor: pointer; display: inline-flex; align-items: center; gap: 6px;" title="Cerrar sesión">
                    <span>🚪</span>
                    <span>Cerrar sesión</span>
                </button>
            </form>
        </div>
    </header>

    <!-- CONTENIDO CENTRAL DINÁMICO -->
    <main class="contenido">
        <div class="alquiler-container">
            
            <div class="header-titulo">
                <h1>Crear Reserva de Trajes</h1>
            </div>

            <!-- Alerta de errores -->
            @if($errors->any())
                <div style="background: #f8d7da; color: #721c24; padding: 10px; margin-bottom: 15px; border-radius: 5px;">
                    {{ $errors->first() }}
                </div>
            @endif

            <form action="{{ route('reservas.store') }}" method="POST" id="form-reserva">
                @csrf
                <input type="hidden" name="numero_recibo" value="{{ $nextId }}">
                <input type="hidden" name="tipo_operacion" value="reservado">

                <div class="layout-grid" style="display: flex; gap: 20px; margin-top: 20px;">
                    
                    <!-- Columna Izquierda: Datos del Cliente y Trajes Apartados -->
                    <div class="col-izquierda" style="flex: 1;">
                        
                        <fieldset class="seccion-bloque" style="border: 1px solid #ccc; padding: 15px; border-radius: 5px; margin-bottom: 20px; background: #fff;">
                            <legend style="font-weight: bold; padding: 0 5px;">DATOS DEL CLIENTE</legend>
                            
                            <!-- Tipo de Cliente -->
                            <div class="fila-campos" style="margin-bottom: 15px;">
                                <div class="campo flex-grow">
                                    <label for="tipo_cliente">Tipo de cliente:</label>
                                    <select id="tipo_cliente" name="tipo_cliente" onchange="cambiarTipoCliente()" required style="width: 100%; padding: 8px; margin-top: 5px;">
                                        <option value="">Seleccionar tipo de cliente</option>
                                        <option value="estudiante">Estudiante</option>
                                        <option value="fraternidad">Integrante de fraternidad</option>
                                        <option value="publico">Público general</option>
                                        <option value="coreografo">Coreógrafo</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Nombre y Teléfono -->
                            <div class="fila-campos" style="margin-bottom: 15px;">
                                <div class="campo flex-grow">
                                    <label for="nombre">Nombre completo:</label>
                                    <input type="text" id="nombre" name="nombre" value="" required style="width: 100%; padding: 8px; margin-top: 5px;">
                                </div>
                            </div>

                            <div class="fila-campos" style="margin-bottom: 15px;">
                                <div class="campo">
                                    <label for="telefono">Teléfono:</label>
                                    <input type="text" id="telefono" name="telefono" value="" style="width: 100%; padding: 8px; margin-top: 5px;">
                                </div>
                            </div>

                            <!-- Campos dinámicos para Estudiante -->
                            <div id="bloque-estudiante" class="fila-campos dinamico-cliente" style="display: none; gap: 15px; margin-bottom: 15px;">
                                <div class="campo" style="flex: 1;">
                                    <label for="colegio">Colegio:</label>
                                    <input type="text" id="colegio" name="colegio" value="" style="width: 100%; padding: 8px; margin-top: 5px;">
                                </div>   
                                <div class="campo" style="flex: 1;">
                                    <label for="curso">Curso:</label>
                                    <select id="curso" name="curso" style="width: 100%; padding: 8px; margin-top: 5px;">
                                        <option value="">Seleccionar</option>
                                        <optgroup label="Inicial">
                                            <option value="Prekínder A">Prekínder "A"</option>
                                            <option value="Prekínder B">Prekínder "B"</option>
                                            <option value="Prekínder C">Prekínder "C"</option>
                                            <option value="Kínder A">Kínder "A"</option>
                                            <option value="Kínder B">Kínder "B"</option>
                                            <option value="Kínder C">Kínder "C"</option>
                                        </optgroup>
                                        <optgroup label="Primaria">
                                            <option value="1° Primaria A">1° Primaria "A"</option>
                                            <option value="1° Primaria B">1° Primaria "B"</option>
                                            <option value="1° Primaria C">1° Primaria "C"</option>
                                            <option value="2° Primaria A">2° Primaria "A"</option>
                                            <option value="2° Primaria B">2° Primaria "B"</option>
                                            <option value="2° Primaria C">2° Primaria "C"</option>
                                            <option value="3° Primaria A">3° Primaria "A"</option>
                                            <option value="3° Primaria B">3° Primaria "B"</option>
                                            <option value="3° Primaria C">3° Primaria "C"</option>
                                            <option value="4° Primaria A">4° Primaria "A"</option>
                                            <option value="4° Primaria B">4° Primaria "B"</option>
                                            <option value="4° Primaria C">4° Primaria "C"</option>
                                            <option value="5° Primaria A">5° Primaria "A"</option>
                                            <option value="5° Primaria B">5° Primaria "B"</option>
                                            <option value="5° Primaria C">5° Primaria "C"</option>
                                            <option value="6° Primaria A">6° Primaria "A"</option>
                                            <option value="6° Primaria B">6° Primaria "B"</option>
                                            <option value="6° Primaria C">6° Primaria "C"</option>
                                        </optgroup>
                                        <optgroup label="Secundaria">
                                            <option value="1° Secundaria A">1° Secundaria "A"</option>
                                            <option value="1° Secundaria B">1° Secundaria "B"</option>
                                            <option value="1° Secundaria C">1° Secundaria "C"</option>
                                            <option value="2° Secundaria A">2° Secundaria "A"</option>
                                            <option value="2° Secundaria B">2° Secundaria "B"</option>
                                            <option value="2° Secundaria C">2° Secundaria "C"</option>
                                            <option value="3° Secundaria A">3° Secundaria "A"</option>
                                            <option value="3° Secundaria B">3° Secundaria "B"</option>
                                            <option value="3° Secundaria C">3° Secundaria "C"</option>
                                            <option value="4° Secundaria A">4° Secundaria "A"</option>
                                            <option value="4° Secundaria B">4° Secundaria "B"</option>
                                            <option value="4° Secundaria C">4° Secundaria "C"</option>
                                            <option value="5° Secundaria A">5° Secundaria "A"</option>
                                            <option value="5° Secundaria B">5° Secundaria "B"</option>
                                            <option value="5° Secundaria C">5° Secundaria "C"</option>
                                            <option value="6° Secundaria A">6° Secundaria "A"</option>
                                            <option value="6° Secundaria B">6° Secundaria "B"</option>
                                            <option value="6° Secundaria C">6° Secundaria "C"</option>
                                        </optgroup>
                                    </select>
                                </div>
                            </div>

                            <!-- Campo dinámico para Fraternidad -->
                            <div id="bloque-fraternidad" class="fila-campos dinamico-cliente" style="display: none; margin-bottom: 15px;">
                                <div class="campo flex-grow">
                                    <label for="fraternidad">Nombre de la fraternidad:</label>
                                    <input type="text" id="fraternidad" name="fraternidad" value="" placeholder="Ej. Fraternidad 100% Caporales" style="width: 100%; padding: 8px; margin-top: 5px;">
                                </div>
                            </div>

                            <!-- Campo dinámico para Coreógrafo -->
                            <div id="bloque-coreografo" class="fila-campos dinamico-cliente" style="display: none; margin-bottom: 15px;">
                                <div class="campo flex-grow">
                                    <label for="lugar_baile">Lugar de presentación / baile:</label>
                                    <input type="text" id="lugar_baile" name="lugar_baile" value="" placeholder="Ej. Corso Cruceño / Entrada Folklórica" style="width: 100%; padding: 8px; margin-top: 5px;">
                                </div>
                            </div>

                        </fieldset>

                        <fieldset class="seccion-bloque" style="border: 1px solid #ccc; padding: 15px; border-radius: 5px; background: #fff;">
                            <legend style="font-weight: bold; padding: 0 5px;">DETALLE DE TRAJES APARTADOS</legend>
                            <table class="tabla-prendas" style="width: 100%; border-collapse: collapse; margin-top: 10px;">
                                <thead>
                                    <tr style="border-bottom: 2px solid #ddd; text-align: left;">
                                        <th style="padding: 8px;">Traje</th>
                                        <th style="padding: 8px;">Cant.</th>
                                        <th style="padding: 8px;">Precio U.</th>
                                        <th style="padding: 8px;">Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody id="contenedorTrajesReserva">
                                    @php
                                        $idTrajes = request('idTraje', []);
                                        $cantidades = request('cant', []);
                                        $precios = request('precio_u', []);
                                        $tallasInput = request('talla', []); 
                                        $totalGeneral = 0;
                                        
                                        $trajesAgrupados = [];

                                        if (count($idTrajes) > 0) {
                                            foreach ($idTrajes as $index => $idTraje) {
                                                $cant = intval($cantidades[$index] ?? 1);
                                                $precioU = floatval($precios[$index] ?? 0);
                                                $talla = $tallasInput[$index] ?? 'Única';

                                                if (isset($trajesAgrupados[$idTraje])) {
                                                    $trajesAgrupados[$idTraje]['cant_total'] += $cant;
                                                    $trajesAgrupados[$idTraje]['detalles_tallas'][] = "{$talla}: {$cant}";
                                                } else {
                                                    $trajeModel = \App\Models\Traje::find($idTraje);
                                                    $trajesAgrupados[$idTraje] = [
                                                        'id' => $idTraje,
                                                        'nombre' => $trajeModel->nombre ?? 'Traje Seleccionado',
                                                        'cant_total' => $cant,
                                                        'precio_u' => $precioU,
                                                        'detalles_tallas' => ["{$talla}: {$cant}"]
                                                    ];
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
                                            <tr style="border-bottom: 1px solid #eee;">
                                                <td style="padding: 10px;">
                                                    <strong>{{ $contador }}. {{ $item['nombre'] }}</strong>
                                                    <br><small style="color: #27ae60; font-weight: bold;">Tallas: {{ implode(', ', $item['detalles_tallas']) }}</small>
                                                    
                                                    <input type="hidden" name="idTraje[]" value="{{ $idTraje }}">
                                                    <input type="hidden" name="precio_u[]" value="{{ $item['precio_u'] }}">
                                                    <input type="hidden" name="cant[]" value="{{ $item['cant_total'] }}">
                                                    <input type="hidden" name="talla[]" value="Única">
                                                </td>
                                                <td style="padding: 10px;">
                                                    <span style="font-weight: bold;">{{ $item['cant_total'] }}</span>
                                                </td>
                                                <td style="padding: 10px;">Bs {{ number_format($item['precio_u'], 2) }}</td>
                                                <td style="padding: 10px;"><strong>Bs {{ number_format($subtotal, 2) }}</strong></td>
                                            </tr>
                                            @php $contador++; @endphp
                                        @endforeach
                                    @else
                                        <!-- JS will populate if available in localStorage -->
                                    @endif
                                </tbody>
                            </table>
                        </fieldset>

                    </div>

                    <!-- Columna Derecha: Fecha de Reserva, Fecha Programada, Monto y Botón -->
                    <div class="col-derecha" style="width: 350px;">
                        
                        <fieldset class="seccion-bloque" style="border: 1px solid #ccc; padding: 15px; border-radius: 5px; margin-bottom: 20px; background: #fff;">
                            <legend style="font-weight: bold; padding: 0 5px;">FECHA DE RESERVA</legend>
                            <div class="campo-linea" style="margin-bottom: 15px;">
                                <label style="display: block; font-size: 13px; font-weight: bold; margin-bottom: 5px;">Fecha y Hora del Registro:</label>
                                <input type="text" id="fecha_actual_texto" class="form-control" readonly style="width: 100%; padding: 8px; background-color: #e9ecef; font-weight: bold; color: #495057;">
                                <input type="hidden" name="fecha_reserva" id="fecha_actual_input">
                            </div>

                            <div class="campo-linea">
                                <label for="fecha_programada" style="display: block; font-size: 13px; font-weight: bold; margin-bottom: 5px; color: #b9770e;">
                                    Fecha Programada de Entrega:
                                </label>
                                <input type="date" 
                                       id="fecha_programada" 
                                       name="fecha_programada" 
                                       class="form-control" 
                                       value="{{ old('fecha_programada', request('fecha_programada', date('Y-m-d'))) }}" 
                                       required 
                                       style="width: 100%; padding: 8px; border: 1px solid #5b2c6f; border-radius: 4px; font-weight: bold; color: #5b2c6f;">
                            </div>
                        </fieldset>

                        <fieldset class="seccion-bloque" style="border: 1px solid #ccc; padding: 15px; border-radius: 5px; margin-bottom: 20px; background: #fff;">
                            <legend style="font-weight: bold; padding: 0 5px;">MONTO DE RESERVA (SEÑA)</legend>
                            <p style="font-size: 12px; color: #555; margin-bottom: 10px;">Ingrese el monto que el cliente deja para apartar los trajes.</p>
                            
                            <div class="campo" style="margin-bottom: 15px;">
                                <label for="monto">Monto Abonado:</label>
                                <div class="input-grupo-bs" style="margin-top: 5px;">
                                    <span>Bs</span>
                                    <input type="number" step="1" id="monto" name="monto" required placeholder="Ej. 200" value="{{ request('monto') }}">
                                </div>
                            </div>

                            <div class="campo" style="margin-bottom: 15px;">
                                <label for="descripcion_seña">Observación de seña:</label>
                                <input type="text" id="descripcion_seña" name="descripcion" value="{{ request('descripcion', 'Seña para reserva de trajes') }}" style="width: 100%; padding: 8px; margin-top: 5px;">
                            </div>

                            <div class="total-caja" style="margin-top: 15px; font-size: 16px; border-top: 1px solid #ddd; padding-top: 10px;">
                                <strong id="textoTotalGeneral">TOTAL GENERAL: Bs {{ number_format($totalGeneral ?? 0, 2) }}</strong>
                            </div>
                        </fieldset>

                        <div class="footer-accion" style="display: flex; gap: 10px; justify-content: flex-end;">
                            <button type="button" onclick="abrirVistaPrevia()" class="btn-verizar" style="background: #6c757d; color: #fff; border: none; padding: 12px 20px; border-radius: 4px; font-weight: bold; cursor: pointer;">
                                Vista previa
                            </button>
                            <button type="submit" class="btn-guardar-reserva" style="flex: 1; background: #27ae60; color: white; border: none; padding: 12px; border-radius: 4px; font-weight: bold; cursor: pointer;">
                                Guardar Reserva 📅
                            </button>
                        </div>

                    </div>
                </div>

            </form>
        </div>
    </main>

    <!-- SCRIPTS DE CONTROL -->
    <script>
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

        const fechaHoy = new Date();
        const anio = fechaHoy.getFullYear();
        const mes = String(fechaHoy.getMonth() + 1).padStart(2, '0');
        const dia = String(fechaHoy.getDate()).padStart(2, '0');
        const horas = String(fechaHoy.getHours()).padStart(2, '0');
        const minutos = String(fechaHoy.getMinutes()).padStart(2, '0');

        document.getElementById('fecha_actual_texto').value = `${dia}/${mes}/${anio}, ${horas}:${minutos}`;
        document.getElementById('fecha_actual_input').value = `${anio}-${mes}-${dia} ${horas}:${minutos}`;

        document.addEventListener("DOMContentLoaded", () => {
            const contenedor = document.getElementById('contenedorTrajesReserva');
            if (contenedor.querySelectorAll('tr').length > 0) {
                return;
            }

            let carrito = JSON.parse(localStorage.getItem('carritoAlquiler')) || [];
            let totalGeneral = 0;

            if (carrito.length === 0) {
                contenedor.innerHTML = '<tr><td colspan="4" style="text-align: center; color: #7f8c8d; padding: 15px;">No hay trajes seleccionados para la reserva.</td></tr>';
                return;
            }

            let agrupados = {};
            carrito.forEach(item => {
                let id = item.id;
                let cant = parseInt(item.cantidad) || 1;
                let precioU = parseFloat(item.precio) || 0;
                let talla = item.talla || 'Única';

                if (agrupados[id]) {
                    agrupados[id].cant_total += cant;
                    agrupados[id].detalles_tallas.push(`${talla}: ${cant}`);
                } else {
                    agrupados[id] = {
                        id: id,
                        nombre: item.nombre,
                        cant_total: cant,
                        precio_u: precioU,
                        detalles_tallas: [`${talla}: ${cant}`]
                    };
                }
            });

            let html = '';
            let index = 1;
            for (let id in agrupados) {
                let item = agrupados[id];
                let subtotal = item.cant_total * item.precio_u;
                totalGeneral += subtotal;

                html += `
                    <tr style="border-bottom: 1px solid #eee;">
                        <td style="padding: 10px;">
                            <strong>${index}. ${item.nombre}</strong>
                            <br><small style="color: #27ae60; font-weight: bold;">Tallas: ${item.detalles_tallas.join(', ')}</small>
                            <input type="hidden" name="idTraje[]" value="${item.id}">
                            <input type="hidden" name="precio_u[]" value="${item.precio_u}">
                            <input type="hidden" name="cant[]" value="${item.cant_total}">
                            <input type="hidden" name="talla[]" value="Única">
                        </td>
                        <td style="padding: 10px;"><strong>${item.cant_total}</strong></td>
                        <td style="padding: 10px;">Bs ${item.precio_u.toFixed(2)}</td>
                        <td style="padding: 10px;"><strong>Bs ${subtotal.toFixed(2)}</strong></td>
                    </tr>
                `;
                index++;
            }

            contenedor.innerHTML = html;
            document.getElementById('textoTotalGeneral').innerText = `TOTAL GENERAL: Bs ${totalGeneral.toFixed(2)}`;
        });

        function abrirVistaPrevia() {
            const formOriginal = document.getElementById('form-reserva') || document.querySelector('form[action*="reserva"]');
            if (!formOriginal) return;
            
            const formTemp = document.createElement('form');
            formTemp.action = "{{ route('reservas.vista_previa') }}";
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
    </script>

</body>
</html>