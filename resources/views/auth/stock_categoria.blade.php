<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ request('modo') == 'alquilar' ? '' : 'Gestión de Stock' }}  {{ $nombreCategoria }}</title>
    <!-- Enlace al archivo CSS externo -->
    <link rel="stylesheet" href="{{ asset('css/stock_categoria.css') }}">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>

    <!-- BARRA SUPERIOR -->
    <header class="topbar">
        <div class="logo-container">
            <span class="logo-text">Creaciones y Fantasías Erlinda</span>
        </div>
        <div class="user-info">
            @auth
                <a href="{{ request('modo') == 'alquilar' ? route('alquiler.seleccionar') : route('stock') }}" class="btn-regresar">← Volver</a>
                <span class="user-role">
                    👤 {{ trim(auth()->user()->nombre . ' ' . (auth()->user()->apellido ?? '')) }}
                </span>
                <form action="{{ route('logout') }}" method="POST" style="display: inline; margin: 0;">
                    @csrf
                    <button type="submit" class="btn-regresar" style="background-color: #c0392b; border: none; cursor: pointer; color: white;">Cerrar sesión</button>
                </form>
            @else
                <span class="user-role" style="color: #7f8c8d; font-size: 14px;">Catálogo Público</span>
            @endauth
        </div>
    </header>

    <!-- CONTENIDO PRINCIPAL -->
    <main class="main-content {{ request('modo') == 'alquilar' ? 'modo-alquiler' : '' }}">
        <div class="header-acciones-grid">
            <div class="search-box-cat">
                <span class="search-icon">🔍</span>
                <input type="text" id="inputBuscar" placeholder="Buscar traje...">
            </div>
            
            @if(auth()->check() && auth()->user()->esAdmin() && request('modo') != 'alquilar')
                <button type="button" onclick="abrirModalCrear()" class="btn-anadir-nuevo">Añadir traje +</button>
            @endif
        </div>

        <div class="stock-header">
            <h1>
                @if(request('modo') == 'alquilar')
                 {{ $nombreCategoria }}
                @else
                     {{ $nombreCategoria }}
                @endif
            </h1>
        </div>

        <div class="catalogo-grid">
            @if(isset($trajes) && count($trajes) > 0)
                @foreach($trajes as $traje)
                    @php
                        $isAlquilar = (request('modo') == 'alquilar');
                        $trajeId = $traje->idTraje ?? $traje->id;
                    @endphp

                    <div class="traje-catalogo-card {{ $isAlquilar ? 'modo-alquilar-card' : '' }}" 
                         @if(!$isAlquilar) onclick="window.location.href='{{ route('trajes.show', $trajeId) }}'" style="cursor: pointer;" @endif>

                        @if(auth()->check() && auth()->user()->esAdmin() && !$isAlquilar)
                            <div class="acciones-admin-card" onclick="event.stopPropagation();">
                                <!-- Botón Editar utilizando atributos data- para evitar errores de sintaxis -->
                                <button type="button" class="btn-accion-editar" 
                                        data-id="{{ $trajeId }}"
                                        data-nombre="{{ $traje->nombre ?? '' }}"
                                        data-pieza="{{ $traje->pieza ?? '' }}"
                                        data-cant_piezas="{{ $traje->cant_piezas ?? 1 }}"
                                        data-stock="{{ $traje->stock ?? 0 }}"
                                        data-precio="{{ $traje->precio ?? 0 }}"
                                        data-descripcion="{{ $traje->descripcion ?? '' }}"
                                        data-tallas="{{ json_encode($traje->tallas ?? []) }}"
                                        onclick="abrirModalEditarDesdeBoton(this)" title="Editar">
                                    <img src="{{ asset('images/edt.jpeg') }}" alt="Editar" style="width: 16px; height: 16px; vertical-align: middle;">
                                </button>
                                
                                <form id="form-eliminar-{{ $trajeId }}" action="{{ route('trajes.destroy', $trajeId) }}" method="POST" style="margin: 0; display: inline-block;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="btn-accion-eliminar" onclick="confirmarEliminacion({{ $trajeId }}, '{{ addslashes($traje->nombre ?? 'este traje') }}')" title="Eliminar">
                                        <img src="{{ asset('images/elim.jpeg') }}" alt="Eliminar" style="width: 16px; height: 16px; vertical-align: middle;">
                                    </button>
                                </form>
                            </div>
                        @endif

                        <div class="catalogo-img-container">
                            <img src="{{ asset('img/' . ($traje->imagen ?? 'bolivia.jpeg')) }}" alt="{{ $traje->nombre ?? 'Traje' }}">
                        </div>

                        <div class="catalogo-info">
                            <span class="catalogo-precio">Bs. {{ number_format((float) ($traje->precio ?? 0), 0) }}</span>
                            <h4 class="catalogo-nombre">{{ $traje->nombre ?? 'Sin nombre' }}</h4>
                            
                            <div class="catalogo-tag">
                                @if(($traje->stock ?? 0) > 0)
                                    <span class="badge-stock" style="background: #e0f2fe; color: #0369a1; font-weight: 600;">
                                        Stock: <strong>{{ $traje->stock }}</strong>
                                    </span>
                                @else
                                    <span class="badge-stock" style="background: #fee2e2; color: #b91c1c; font-weight: 600;">
                                        Agotado (0)
                                    </span>
                                @endif

                                @if(isset($traje->tallas) && count($traje->tallas) > 0)
                                    @foreach($traje->tallas as $t)
                                        <span class="badge-talla" style="{{ ($t->stock ?? 0) <= 0 ? 'color: #94a3b8; text-decoration: line-through;' : '' }}">
                                            {{ $t->talla }}: <strong>{{ $t->stock }}</strong>
                                        </span>
                                    @endforeach
                                @endif
                                <span style="background: #f1f5f9; color: #475569; font-weight: 600; padding: 2px 6px; border-radius: 4px; font-size: 11px; white-space: nowrap;">
                                    {{ $traje->cant_piezas ?? 1 }} {{ ($traje->cant_piezas ?? 1) == 1 ? 'pieza' : 'piezas' }}
                                </span>
                            </div>

                            @if($isAlquilar)
                                <div class="container-seleccionar-talla" onclick="event.stopPropagation();">
                                    @if(($traje->stock ?? 0) > 0)
                                        <button type="button" class="btn-seleccionar-traje" onclick="seleccionarTrajeParaConfirmar({{ $trajeId }})">
                                            Seleccionar
                                        </button>
                                    @else
                                        <button type="button" class="btn-seleccionar-traje" disabled style="background: #cbd5e1; cursor: not-allowed; color: #64748b;">
                                            Sin Stock
                                        </button>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            @else
                <p class="empty-message" style="grid-column: 1 / -1; text-align: center; color: #7f8c8d;">No hay trajes registrados en esta categoría.</p>
            @endif
        </div>
    </main>

    <!-- CARRITO FLOTANTE: Solo visible en Modo Alquilar -->
    @if(request('modo') == 'alquilar')
    <div id="carritoFlotante" class="carrito-flotante">
        <div class="carrito-header">
            <span>Trajes Seleccionados <span id="carritoContador">0</span></span>
        </div>
        
        <!-- Contenedor donde se inyectarán los trajes agrupados -->
        <div class="carrito-body" id="carritoItemsContainer">
            <p style="text-align: center; color: #888; font-size: 12px; margin: 15px 0;">Aún no has agregado trajes.</p>
        </div>

        <div class="carrito-footer" style="display: flex; flex-direction: column; gap: 8px; padding: 10px;">
            <form id="formConfirmarAlquiler" action="{{ route('registrar') }}" method="GET">
                <input type="hidden" name="tipo_operacion" value="activo">
                <div id="inputsHiddenCarrito"></div>
                <button type="button" onclick="enviarOperacion('activo')" class="btn-confirmar-carrito" style="background: #27ae60; color: white; border: none; padding: 9px 10px; width: 100%; border-radius: 4px; font-weight: bold; cursor: pointer; font-size: 13px;">
                    Registrar Alquiler
                </button>
            </form>

            <form id="formConfirmarReserva" action="{{ route('reservas.create') }}" method="GET">
                <div id="inputsHiddenCarritoReserva"></div>
                <button type="button" onclick="enviarOperacion('reservado')" class="btn-reservar-carrito" style="background: #2980b9; color: white; border: none; padding: 9px 10px; width: 100%; border-radius: 4px; font-weight: bold; cursor: pointer; font-size: 13px;">
                    Crear Reserva 
                </button>
            </form>
        </div>
    </div>
@endif

    <!-- MODAL DE MANTENIMIENTO: Solo en Modo Stock -->
    @if(auth()->check() && auth()->user()->esAdmin() && request('modo') != 'alquilar')
    <div id="modalNuevoTraje" class="modal-overlay">
        <div class="modal-container">
            <div class="modal-header">
                <h3 id="modalTitulo">+ Añadir Nuevo Traje</h3>
                <button type="button" class="modal-close" onclick="cerrarModal()">&times;</button>
            </div>
            
            <form id="formTraje" action="{{ route('trajes.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div id="methodFieldContainer"></div>
                <input type="hidden" name="idCategoria" value="{{ request()->route('categoria') }}">

                <div class="modal-body">
                    <div class="modal-upload-section" onclick="document.getElementById('inputImagen').click()">
                        <div id="previewContainer">
                            <span id="uploadIcon">+</span>
                            <p id="uploadText">Arrastra una imagen para subir</p>
                        </div>
                        <input type="file" id="inputImagen" name="imagen" accept="image/*" style="display: none;" onchange="verImagenPrevia(event)">
                    </div>

                    <div class="modal-form-fields">
                        <div class="modal-field">
                            <label>Categoría</label>
                            <input type="text" value="{{ $nombreCategoria }}" readonly style="background: #f1f5f9;">
                        </div>

                        <div class="modal-field">
                            <label>Nombre del Traje</label>
                            <input type="text" name="nombre" required placeholder="Ej. Traje de Morenada">
                        </div>

                        <div class="modal-field">
                            <label>Piezas que componen el traje (separadas por coma)</label>
                            <input type="text" name="pieza" id="inputPiezasTexto" placeholder="Ej. Sombrero, manta, tulmas, blusa" oninput="contarPiezasAutomaticamente()">
                        </div>
                        
                        <div class="modal-field">
                            <label>Cantidad Total de Piezas</label>
                            <input type="number" name="cant_piezas" id="inputCantidadPiezas" required min="1" value="1" readonly style="background: #f1f5f9; font-weight: bold;">
                        </div>

                        <div class="modal-field form-field-full" style="background: #f8fafc; padding: 12px; border-radius: 6px; border: 1px solid #e2e8f0;">
                            <label style="font-weight: bold; color: #2c3e50; margin-bottom: 8px; display: block;">Tallas y Stock Disponible</label>
                            <div id="contenedorTallasDinamicas"></div>
                            <button type="button" onclick="agregarFilaTalla()" style="margin-top: 8px; background: #3498db; color: white; border: none; padding: 5px 10px; border-radius: 4px; cursor: pointer; font-size: 12px;">+ Añadir otra talla</button>
                        </div>

                        <div class="modal-field">
                            <label>Cantidad en Stock</label>
                            <input type="number" name="stock" readonly required min="0" value="0" style="background: #f1f5f9; font-weight: bold;" placeholder="Stock total">
                        </div>

                        <div class="modal-field">
                            <label>Precio</label>
                            <input type="number" step="1" name="precio" required placeholder="0.00">
                        </div>

                        <div class="modal-field form-field-full">
                            <label>Descripción Detallada</label>
                            <textarea name="descripcion" rows="3" placeholder="Detalles o características especiales..."></textarea>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn-modal-cancelar" onclick="cerrarModal()">Cancelar</button>
                    <button type="submit" class="btn-modal-guardar" id="btnGuardarModal">Guardar Traje</button>
                </div>
            </form>
        </div>
    </div>
    @endif

    <!-- LÓGICA JAVASCRIPT -->
    <script>
        let carrito = JSON.parse(localStorage.getItem('carritoAlquiler')) || [];
        actualizarCarritoUI();

        function seleccionarTrajeParaConfirmar(id) {
            localStorage.setItem('urlCategoriaActual', window.location.href);

            const selectTalla = document.getElementById('talla-select-' + id);
            const talla = selectTalla ? selectTalla.value : 'Única';
            
            window.location.href = "/alquileres/confirmar/" + id + "?modo=alquilar&talla=" + encodeURIComponent(talla);
        }

        function quitarDeCarrito(index) {
            carrito.splice(index, 1);
            guardarYActualizar();
        }

        function guardarYActualizar() {
            localStorage.setItem('carritoAlquiler', JSON.stringify(carrito));
            actualizarCarritoUI();
        }

        function actualizarCarritoUI() {
            const container = document.getElementById('carritoItemsContainer');
            const contador = document.getElementById('carritoContador');
            const inputsContainer = document.getElementById('inputsHiddenCarrito');
            const inputsContainerReserva = document.getElementById('inputsHiddenCarritoReserva');
            
            if (!container) return;

            container.innerHTML = '';
            if (inputsContainer) inputsContainer.innerHTML = '';
            if (inputsContainerReserva) inputsContainerReserva.innerHTML = '';

            if (carrito.length === 0) {
                container.innerHTML = '<p style="text-align: center; color: #888; font-size: 12px; margin: 15px 0;">Aún no has agregado trajes.</p>';
                contador.innerText = '0';
                return;
            }

            // Agrupar los elementos del carrito por el ID del traje
            const agrupados = {};
            carrito.forEach((item, indexOriginal) => {
                if (!agrupados[item.id]) {
                    agrupados[item.id] = {
                        nombre: item.nombre,
                        tallas: []
                    };
                }
                agrupados[item.id].tallas.push({
                    talla: item.talla,
                    cant: item.cant,
                    precio: item.precio,
                    index: indexOriginal
                });
            });

            let totalItems = 0;
            let htmlItems = '';
            let inputsHTML = '';

            Object.keys(agrupados).forEach(idTraje => {
                const grupo = agrupados[idTraje];
                
                htmlItems += `
                    <div style="padding: 10px; border-bottom: 1px solid #eee; position: relative;">
                        <div style="display: flex; justify-content: space-between; align-items: center; font-weight: bold; font-size: 13px; color: #333;">
                            <span>${grupo.nombre}</span>
                        </div>
                        <div style="margin-top: 5px; padding-left: 4px;">
                `;

                grupo.tallas.forEach(t => {
                    totalItems += parseInt(t.cant);
                    htmlItems += `
                        <div style="font-size: 11px; color: #666; display: flex; justify-content: space-between; align-items: center; margin-top: 3px;">
                            <span>• Talla: ${t.talla} | Cant: ${t.cant}</span>
                            <button type="button" onclick="quitarDeCarrito(${t.index})" style="background: #e74c3c; color: white; border: none; border-radius: 50%; width: 16px; height: 16px; font-size: 9px; cursor: pointer; display: flex; align-items: center; justify-content: center;" title="Quitar esta talla">×</button>
                        </div>
                    `;

                    inputsHTML += `
                        <input type="hidden" name="idTraje[]" value="${idTraje}">
                        <input type="hidden" name="cant[]" value="${t.cant}">
                        <input type="hidden" name="precio_u[]" value="${t.precio}">
                        <input type="hidden" name="talla[]" value="${t.talla}">
                    `;
                });

                htmlItems += `
                        </div>
                    </div>
                `;
            });

            container.innerHTML = htmlItems;
            contador.innerText = totalItems;

            if (inputsContainer) inputsContainer.innerHTML = inputsHTML;
            if (inputsContainerReserva) inputsContainerReserva.innerHTML = inputsHTML;
        }

        function enviarOperacion(tipo) {
            if (carrito.length === 0) {
                Swal.fire('Atención', 'Debes agregar al menos un traje al carrito para continuar.', 'warning');
                return;
            }
            localStorage.removeItem('carritoAlquiler');
            localStorage.removeItem('urlCategoriaActual');

            if (tipo === 'activo') {
                document.getElementById('formConfirmarAlquiler').submit();
            } else {
                document.getElementById('formConfirmarReserva').submit();
            }
        }

        function confirmarEliminacion(id, nombreTraje) {
            Swal.fire({
                title: '¿Estás seguro de eliminar ' + nombreTraje + '?',
                showCancelButton: true,
                width: '420px',
                padding: '1rem',
                confirmButtonColor: '#e74c3c',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Eliminar',
                cancelButtonText: 'Cancelar',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('form-eliminar-' + id).submit();
                }
            });
        }

        document.getElementById('inputBuscar').addEventListener('input', function() {
            let filtro = this.value.toLowerCase().trim();
            let tarjetasTrajes = document.querySelectorAll('.traje-catalogo-card');
            let hayResultados = false;

            tarjetasTrajes.forEach(tarjeta => {
                let elementoNombre = tarjeta.querySelector('.catalogo-nombre');
                if (elementoNombre) {
                    let nombreTraje = elementoNombre.textContent.toLowerCase();
                    if (nombreTraje.includes(filtro)) {
                        tarjeta.style.display = ''; 
                        hayResultados = true;
                    } else {
                        tarjeta.style.display = 'none'; 
                    }
                }
            });

            let mensajeVacio = document.querySelector('.empty-message-busqueda');
            if (!hayResultados && filtro !== '') {
                if (!mensajeVacio) {
                    let catalogoGrid = document.querySelector('.catalogo-grid');
                    let p = document.createElement('p');
                    p.className = 'empty-message-busqueda';
                    p.style.cssText = "grid-column: 1 / -1; text-align: center; color: #7f8c8d; margin-top: 20px;";
                    p.innerText = "No se encontraron trajes con ese nombre.";
                    catalogoGrid.appendChild(p);
                }
            } else {
                if (mensajeVacio) mensajeVacio.remove();
            }
        });

        function cerrarModal() {
            let modal = document.getElementById('modalNuevoTraje');
            if(modal) modal.style.display = 'none';
        }

        window.onclick = function(event) {
            let modal = document.getElementById('modalNuevoTraje');
            if (modal && event.target == modal) modal.style.display = 'none';
        }

        function agregarFilaTalla(tallaVal = '', stockVal = 1) {
            let container = document.getElementById('contenedorTallasDinamicas');
            if (!container) return;
            let index = container.children.length;
            
            let row = document.createElement('div');
            row.style.cssText = "display: flex; gap: 10px; margin-bottom: 6px; align-items: center;";
            row.innerHTML = `
                <input type="text" name="tallas_arr[${index}][talla]" value="${tallaVal}" placeholder="Ej. M, L, 38" required style="flex: 2; padding: 6px; border: 1px solid #ccc; border-radius: 4px;">
                <input type="number" name="tallas_arr[${index}][stock]" value="${stockVal}" min="0" placeholder="Stock" required class="input-stock-talla" oninput="recalcularStockTotal()" style="flex: 1; padding: 6px; border: 1px solid #ccc; border-radius: 4px;">
                <button type="button" onclick="this.parentElement.remove(); recalcularStockTotal();" style="background: #e74c3c; color: white; border: none; border-radius: 4px; width: 28px; height: 28px; cursor: pointer;">×</button>
            `;
            container.appendChild(row);
            recalcularStockTotal();
        }

        function contarPiezasAutomaticamente() {
            let texto = document.getElementById('inputPiezasTexto').value.trim();
            let inputCant = document.getElementById('inputCantidadPiezas');
            if (!inputCant) return;
            
            if (texto === '') {
                inputCant.value = 1;
                return;
            }

            let piezasArray = texto.split(',').filter(item => item.trim() !== '');
            inputCant.value = piezasArray.length > 0 ? piezasArray.length : 1;
        }

        function recalcularStockTotal() {
            let inputsStock = document.querySelectorAll('.input-stock-talla');
            let totalStock = 0;
            
            inputsStock.forEach(input => {
                let val = parseInt(input.value) || 0;
                totalStock += val;
            });

            let inputStockTotal = document.querySelector('input[name="stock"]');
            if (inputStockTotal) {
                inputStockTotal.value = totalStock;
            }
        }

        function abrirModalCrear() {
            let modal = document.getElementById('modalNuevoTraje');
            let form = document.getElementById('formTraje');
            let titulo = document.getElementById('modalTitulo');
            let methodContainer = document.getElementById('methodFieldContainer');

            form.action = "{{ route('trajes.store') }}";
            form.reset();
            methodContainer.innerHTML = ''; 
            
            document.getElementById('contenedorTallasDinamicas').innerHTML = '';
            agregarFilaTalla('M', 1);
            contarPiezasAutomaticamente();

            titulo.innerText = "+ Añadir Nuevo Traje";
            document.getElementById('previewContainer').innerHTML = '<span id="uploadIcon">+</span><p id="uploadText">Arrastra una imagen para subir</p>';
            modal.style.display = 'flex';
        }

        function abrirModalEditarDesdeBoton(button) {
            let id = button.getAttribute('data-id');
            let nombre = button.getAttribute('data-nombre');
            let pieza = button.getAttribute('data-pieza');
            let cant_piezas = button.getAttribute('data-cant_piezas');
            let stock = button.getAttribute('data-stock');
            let precio = button.getAttribute('data-precio');
            let descripcion = button.getAttribute('data-descripcion');
            
            let tallasTraje = [];
            try {
                tallasTraje = JSON.parse(button.getAttribute('data-tallas'));
            } catch(e) {
                tallasTraje = [];
            }

            let modal = document.getElementById('modalNuevoTraje');
            let form = document.getElementById('formTraje');
            let titulo = document.getElementById('modalTitulo');
            let methodContainer = document.getElementById('methodFieldContainer');

            form.action = "/trajes/" + id;
            methodContainer.innerHTML = '<input type="hidden" name="_method" value="PUT">';

            form.querySelector('input[name="nombre"]').value = nombre;
            form.querySelector('input[name="pieza"]').value = (pieza !== 'null' && pieza !== 'undefined') ? pieza : '';
            form.querySelector('input[name="stock"]').value = stock;
            form.querySelector('input[name="cant_piezas"]').value = (cant_piezas !== 'null' && cant_piezas !== 'undefined') ? cant_piezas : 1;
            form.querySelector('input[name="precio"]').value = precio;
            form.querySelector('textarea[name="descripcion"]').value = (descripcion !== 'null' && descripcion !== 'undefined') ? descripcion : '';

            let containerTallas = document.getElementById('contenedorTallasDinamicas');
            containerTallas.innerHTML = '';
            
            if (tallasTraje && tallasTraje.length > 0) {
                tallasTraje.forEach(t => { agregarFilaTalla(t.talla, t.stock); });
            } else {
                agregarFilaTalla('', 1);
            }
            
            recalcularStockTotal();
            contarPiezasAutomaticamente();

            titulo.innerText = "Editar Traje";
            document.getElementById('previewContainer').innerHTML = '<p style="font-size: 13px; color: #555;">Imagen actual mantenida (sube otra solo si deseas cambiarla)</p>';
            modal.style.display = 'flex';
        }

        function verImagenPrevia(event) {
            let reader = new FileReader();
            reader.onload = function(){
                let container = document.getElementById('previewContainer');
                container.innerHTML = '<img src="' + reader.result + '" style="max-width: 100%; max-height: 180px; object-fit: contain; border-radius: 8px;">';
            }
            if(event.target.files[0]){
                reader.readAsDataURL(event.target.files[0]);
            }
        }
    </script>
</body>
</html>