<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Alquiler - Creaciones y Fantasías Erlinda</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/stock_categoria.css') }}">
    <link rel="stylesheet" href="{{ asset('css/lista_alquiler.css') }}">
</head>
<body>

    <!-- BARRA SUPERIOR -->
    <header class="topbar">
        <div class="logo-container">
            <span class="logo-text">Creaciones y Fantasías Erlinda</span>
        </div>
        <div class="user-info">
            <a href="javascript:history.back()" class="btn-regresar">← Volver al Catálogo</a>
            <span class="user-role">
                @if(auth()->user()->rol && auth()->user()->rol->idRol == 1)
                    Administrador
                @else
                    Empleado
                @endif
            </span>
        </div>
    </header>

    <!-- CONTENIDO PRINCIPAL -->
    <main class="main-content">
        <div class="lista-header-titulo">
            <h1>📋 Lista de Trajes Seleccionados para el Alquiler</h1>
            <p>Verifica los trajes agregados, completa los datos del cliente y registra el alquiler.</p>
        </div>

        @if(session('success'))
            <div class="alert-success">{{ session('success') }}</div>
        @endif

        @if(session('error'))
            <div class="alert-error">{{ session('error') }}</div>
        @endif

        <div class="lista-alquiler-grid">
            
            <!-- SECCIÓN IZQUIERDA: Tabla de ítems y Formulario de Cliente -->
            <div class="lista-columna-principal">
                
                <!-- TABLA DE TRAJES SELECCIONADOS -->
                <div class="card-seccion">
                    <h3>Trajes en el Carrito / Selección</h3>
                    <div class="table-responsive">
                        <table class="tabla-carrito">
                            <thead>
                                <tr>
                                    <th>Imagen</th>
                                    <th>Traje</th>
                                    <th>Talla</th>
                                    <th>Precio</th>
                                    <th>Acción</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($trajesSeleccionados ?? [] as $index => $item)
                                    <tr>
                                        <td>
                                            <img src="{{ asset('img/' . ($item->imagen ?? 'bolivia.jpeg')) }}" alt="Traje" class="img-tabla-mini">
                                        </td>
                                        <td><strong>{{ $item->nombre ?? 'Traje' }}</strong></td>
                                        <td>{{ $item->tallas ?? $item->talla ?? 'S/N' }}</td>
                                        <td>Bs. {{ number_format((float)($item->precio ?? 0), 2) }}</td>
                                        <td>
                                            <!-- Ruta para quitar el ítem de la sesión de alquiler -->
                                            <form action="{{ route('alquileres.quitar', $item->idTraje ?? $item->id) }}" method="POST" style="margin: 0;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn-quitar-item" title="Quitar de la lista">❌</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="empty-tabla">No hay trajes seleccionados en la lista de alquiler.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- FORMULARIO DE DATOS DEL CLIENTE -->
                <div class="card-seccion" style="margin-top: 20px;">
                    <h3>Datos del Cliente y Fechas de Alquiler</h3>
                    
                    <form action="{{ route('alquileres.store') }}" method="POST" id="formAlquilerFinal">
                        @csrf
                        <div class="form-cliente-grid">
                            <div class="modal-field">
                                <label>Nombre Completo del Cliente</label>
                                <input type="text" name="nombre_cliente" required placeholder="Ej. Juan Pérez">
                            </div>

                            <div class="modal-field">
                                <label>C.I. / NIT</label>
                                <input type="text" name="ci_cliente" required placeholder="Ej. 1234567 LP">
                            </div>

                            <div class="modal-field">
                                <label>Teléfono / Celular</label>
                                <input type="text" name="telefono_cliente" required placeholder="Ej. 70000000">
                            </div>

                            <div class="modal-field">
                                <label>Monto de Garantía (Bs.)</label>
                                <input type="number" step="0.01" name="garantia" id="inputGarantia" value="0.00" oninput="calcularTotal()">
                            </div>

                            <div class="modal-field">
                                <label>Fecha de Entrega (Retiro)</label>
                                <input type="date" name="fecha_entrega" required value="{{ date('Y-m-d') }}">
                            </div>

                            <div class="modal-field">
                                <label>Fecha de Devolución</label>
                                <input type="date" name="fecha_devolucion" required value="{{ date('Y-m-d', strtotime('+3 days')) }}">
                            </div>
                        </div>
                </div>

            </div>

            <!-- SECCIÓN DERECHA: Resumen Financiero y Botón de Confirmación -->
            <div class="lista-columna-secundaria">
                <div class="card-seccion card-resumen">
                    <h3>Resumen Financiero</h3>
                    
                    <div class="resumen-linea">
                        <span>Subtotal Trajes:</span>
                        <span id="txtSubtotal">Bs. {{ number_format($subtotal ?? 0, 2) }}</span>
                    </div>

                    <div class="resumen-linea">
                        <span>Garantía:</span>
                        <span id="txtGarantia">Bs. 0.00</span>
                    </div>

                    <hr class="resumen-divider">

                    <div class="resumen-linea resumen-total">
                        <span>TOTAL A PAGAR:</span>
                        <span id="txtTotal">Bs. {{ number_format($subtotal ?? 0, 2) }}</span>
                    </div>

                    <button type="submit" class="btn-confirmar-alquiler" @if(empty($trajesSeleccionados) || count($trajesSeleccionados) == 0) disabled style="opacity: 0.5; cursor: not-allowed;" @endif>
                        💾 Confirmar y Registrar Alquiler
                    </button>
                </div>
            </div>

            </form>
        </div>
    </main>

    <script>
        function calcularTotal() {
            let subtotal = parseFloat("{{ $subtotal ?? 0 }}");
            let garantiaInput = document.getElementById('inputGarantia').value;
            let garantia = garantiaInput ? parseFloat(garantiaInput) : 0;

            let total = subtotal + garantia;

            document.getElementById('txtGarantia').innerText = 'Bs. ' + garantia.toFixed(2);
            document.getElementById('txtTotal').innerText = 'Bs. ' + total.toFixed(2);
        }

        // Ejecutar al cargar por si hay valores previos
        window.onload = function() {
            calcularTotal();
        }
    </script>
</body>
</html>