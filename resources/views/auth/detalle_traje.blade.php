<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $traje->nombre }} - Creaciones y Fantasías Erlinda</title>
    <link rel="stylesheet" href="{{ asset('css/detalle_traje.css') }}">
</head>
<body>

    <div class="detalle-container">
        <div class="detalle-header">
            <h3>Creaciones y Fantasías Erlinda</h3>
            <a href="javascript:history.back()" class="btn-volver">← Volver al Catálogo</a>
        </div>

        <div class="detalle-grid">
            <!-- Imagen del Traje -->
            <div class="detalle-img-box">
                <img src="{{ asset('img/' . ($traje->imagen ?? 'bolivia.jpeg')) }}" alt="{{ $traje->nombre }}">
            </div>

            <!-- Datos e Información del Traje -->
            <div class="detalle-info-box">
                <h2>{{ $traje->nombre }}</h2>
                <span class="detalle-precio">Bs. {{ number_format((float) ($traje->precio ?? 0), 2) }}</span>

                <div class="detalle-Atributo">
                    <strong>Piezas:</strong> {{ $traje->pieza ?? 'Conjunto completo' }}
                </div>

                <div class="detalle-Atributo">
                    <strong>Cantidad Total de Piezas:</strong> {{ $traje->cant_piezas ?? 1 }}
                </div>

                <div class="detalle-Atributo">
                    <strong>Tallas Disponibles:</strong> 
                    <div style="margin-top: 5px;">
                        @if(isset($traje->tallas) && count($traje->tallas) > 0)
                            @foreach($traje->tallas as $t)
                                <div>• Talla {{ is_object($t) ? $t->talla : ($t['talla'] ?? 'Única') }}: {{ is_object($t) ? $t->stock : ($t['stock'] ?? 1) }} unidades</div>
                            @endforeach
                        @else
                            <div>• Talla Única</div>
                        @endif
                    </div>
                </div>

                <div class="detalle-Atributo">
                    <strong>Cantidad en Stock Total:</strong> 
                    @php
                        $stockTotal = $traje->stock ?? 0;
                    @endphp
                    {{ $stockTotal }} {{ $stockTotal == 1 ? 'unidad disponible' : 'unidades disponibles' }}
                </div>

                @if(!empty($traje->descripcion))
                    <div class="descripcion-box">
                        <strong>Descripción:</strong>
                        <p>{{ $traje->descripcion }}</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

</body>
</html>