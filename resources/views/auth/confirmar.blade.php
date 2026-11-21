<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Configurar Alquiler - Creaciones y Fantasías Erlinda</title>
    <link rel="stylesheet" href="{{ asset('css/confirmar.css') }}">
    <script>
        function calcularSubtotal() {
            let totalTrajes = 0;
            // Sumamos las cantidades ingresadas en cada input de talla
            let inputsTallas = document.querySelectorAll('.input-cant-talla');
            inputsTallas.forEach(input => {
                totalTrajes += parseInt(input.value) || 0;
            });

            let precioUnitario = parseFloat(document.getElementById('precio_unitario').value) || 0;
            let subtotal = totalTrajes * precioUnitario;

            document.getElementById('cantidad_trajes_total').value = totalTrajes;
            document.getElementById('span_subtotal').innerText = 'Bs ' + subtotal.toFixed(2);
        }

        // Agrega las tallas seleccionadas con sus cantidades al carrito y vuelve a la categoría
        function agregarAlCarritoYVolver(event) {
            event.preventDefault();

            let id = document.getElementById('input_idTraje').value;
            let nombre = document.getElementById('input_nombre_traje').value;
            let precio = parseFloat(document.getElementById('precio_unitario').value) || 0;
            
            let carrito = JSON.parse(localStorage.getItem('carritoAlquiler')) || [];
            let inputsTallas = document.querySelectorAll('.input-cant-talla');
            let agregadoAlMenosUno = false;

            inputsTallas.forEach(input => {
                let cant = parseInt(input.value) || 0;
                if (cant > 0) {
                    let talla = input.getAttribute('data-talla');
                    agregadoAlMenosUno = true;

                    // Verificamos si ya existe en el carrito este traje con esta talla
                    let itemExistente = carrito.find(item => item.id == id && item.talla === talla);
                    if (itemExistente) {
                        itemExistente.cant += cant;
                    } else {
                        carrito.push({ id: id, nombre: nombre + ' (' + talla + ')', precio: precio, talla: talla, cant: cant });
                    }
                }
            });

            if (!agregadoAlMenosUno) {
                alert('Debes seleccionar al menos una cantidad mayor a 0 en alguna talla.');
                return;
            }

            localStorage.setItem('carritoAlquiler', JSON.stringify(carrito));
            volverACategoria();
        }

        function volverACategoria() {
            let urlCategoria = localStorage.getItem('urlCategoriaActual');
            if (urlCategoria) {
                window.location.href = urlCategoria;
            } else {
                window.location.href = "{{ route('stock') }}?modo=alquilar";
            }
        }

        window.onload = function() {
            calcularSubtotal();
        };
    </script>
</head>
<body>

    <div class="modal-dialog-container" style="max-width: 850px;">
        <div class="modal-header">
            <h3>Detalles del Traje</h3>
            <button type="button" onclick="volverACategoria()" class="btn-close" style="background:none; border:none; font-size:24px; cursor:pointer;">&times;</button>
        </div>

        <form onsubmit="agregarAlCarritoYVolver(event)" class="modal-body">
            
            <input type="hidden" id="input_idTraje" value="{{ $trajeSeleccionado->idTraje ?? $trajeSeleccionado->id ?? '' }}">
            <input type="hidden" id="input_nombre_traje" value="{{ $trajeSeleccionado->nombre ?? '' }}">

            <div class="grid-layout">
                <div>
                    <div class="image-box">
                        <img src="{{ asset('img/' . ($trajeSeleccionado->imagen ?? 'bolivia.jpeg')) }}" alt="{{ $trajeSeleccionado->nombre ?? 'Traje' }}">
                    </div>
                </div>

                <div>
                    <div class="row-grid-2">
                        <div class="form-group">
                            <label>Categoría</label>
                            <input type="text" class="form-control" value="{{ $trajeSeleccionado->categoria ?? 'Trajes' }}" readonly style="background-color: #e9ecef; cursor: not-allowed;">
                        </div>
                        <div class="form-group">
                            <label>Nombre del Traje</label>
                            <input type="text" class="form-control" value="{{ $trajeSeleccionado->nombre ?? '' }}" readonly style="background-color: #e9ecef; cursor: not-allowed;">
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Piezas</label>
                        <input type="text" name="pieza" class="form-control" value="{{ $trajeSeleccionado->pieza ?? '' }}">
                    </div>

                    <!-- SECCIÓN DE SELECCIÓN MÚLTIPLE POR TALLAS Y STOCK -->
                    <div class="form-group" style="background: #f8fafc; padding: 12px; border-radius: 6px; border: 1px solid #cbd5e1;">
                        <label style="font-weight: bold; color: #2c3e50; margin-bottom: 8px; display: block;">Seleccionar Cantidad por Talla a Llevar</label>
                        
                        <div style="display: flex; flex-direction: column; gap: 8px; max-height: 150px; overflow-y: auto;">
                            @if(isset($trajeSeleccionado->lista_tallas) && count($trajeSeleccionado->lista_tallas) > 0)
                                @foreach($trajeSeleccionado->lista_tallas as $t)
                                    @php
                                        $nombreTalla = is_array($t) ? ($t['talla'] ?? '') : (is_object($t) ? ($t->talla ?? '') : $t);
                                        $stockTalla = is_array($t) ? ($t['stock'] ?? 0) : (is_object($t) ? ($t->stock ?? 0) : 0);
                                    @endphp
                                    <div style="display: flex; justify-content: space-between; align-items: center; background: white; padding: 6px 10px; border-radius: 4px; border: 1px solid #e2e8f0;">
                                        <span style="font-size: 13px; font-weight: 600; color: #334155;">
                                            Talla: {{ $nombreTalla }} <small style="color: #64748b;">(Disponibles: {{ $stockTalla }})</small>
                                        </span>
                                        <input type="number" class="input-cant-talla form-control" data-talla="{{ $nombreTalla }}" value="0" min="0" max="{{ $stockTalla }}" oninput="calcularSubtotal()" style="width: 80px; text-align: center; padding: 4px;">
                                    </div>
                                @endforeach
                            @else
                                <div style="display: flex; justify-content: space-between; align-items: center; background: white; padding: 6px 10px; border-radius: 4px; border: 1px solid #e2e8f0;">
                                    <span style="font-size: 13px; font-weight: 600; color: #334155;">
                                        Talla Única <small style="color: #64748b;">(Stock: {{ $trajeSeleccionado->stock ?? 1 }})</small>
                                    </span>
                                    <input type="number" class="input-cant-talla form-control" data-talla="Única" value="1" min="1" max="{{ $trajeSeleccionado->stock ?? 1 }}" oninput="calcularSubtotal()" style="width: 80px; text-align: center; padding: 4px;">
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="row-grid-2" style="margin-top: 10px;">
                        <div class="form-group">
                            <label>Cantidad Total de Trajes</label>
                            <input type="number" id="cantidad_trajes_total" class="form-control" value="1" readonly style="background-color: #e9ecef; font-weight: bold;">
                        </div>
                        <div class="form-group">
                            <label>Cantidad de piezas por traje</label>
                            <input type="number" name="cant_piezas" class="form-control" value="{{ $trajeSeleccionado->cant_piezas ?? 1 }}">
                        </div>
                    </div>

                    <div class="row-grid-2">
                        <div class="form-group">
                            <label>Precio Unitario (Bs.)</label>
                            <input type="number" step="1" id="precio_unitario" class="form-control" value="{{ $trajeSeleccionado->precio ?? 0 }}" oninput="calcularSubtotal()">
                        </div>
                        <div class="form-group">
                            <label>Subtotal</label>
                            <div style="padding: 10px; background: #f8f9fa; border: 1px solid #ced4da; border-radius: 4px; font-weight: bold; color: #27ae60;">
                                <span id="span_subtotal">Bs {{ number_format($trajeSeleccionado->precio ?? 0, 2) }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal-footer" style="margin-top: 20px; display: flex; justify-content: space-between;">
                <button type="button" onclick="volverACategoria()" class="btn-cancelar" style="background: #6c757d; color: white; border: none; padding: 8px 15px; border-radius: 4px; cursor: pointer;">Cancelar</button>
                <button type="submit" class="btn-confirmar">Confirmar ✔</button>
            </div>
        </form>
    </div>

</body>
</html>