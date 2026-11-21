<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Recibo de Alquiler - Código #{{ $alquiler->id ?? 'N/A' }}</title>
    <link rel="stylesheet" href="{{ asset('css/recibo.css') }}">
</head>
<body>

    <div class="recibo-box">
        <div class="recibo-header">
            <h2>CREACIONES Y FANTASÍAS "ERLINDA"</h2>
            <p>Alquiler de Trajes Típicos y Fantasía en General!!!</p>
            <p>Plan 3.000 Av. Paurito zona Los Pocitos</p>
            <p>Telf.: 67793706 - 71686142</p>
            <hr style="border: 0; border-top: 1px solid #5b2c6f; margin: 10px auto; width: 80%;">
            
            <p style="font-size: 15px; font-weight: bold; color: #333;">RECIBO Nº {{ $alquiler->id ?? 'N/A' }}</p>
        </div>

        <!-- DATOS DEL CLIENTE DINÁMICOS SEGÚN EL TIPO -->
        <div class="info-cliente">
            <div><strong>Nombre:</strong> {{ $alquiler->nombre ?? '' }}</div>

            @if(!empty($alquiler->tipo_cliente))
                @if($alquiler->tipo_cliente == 'estudiante')
                    <div><strong>Colegio:</strong> {{ $alquiler->colegio ?? '' }}</div>
                    <div><strong>Curso:</strong> {{ $alquiler->curso ?? '' }}</div>
                @elseif($alquiler->tipo_cliente == 'fraternidad')
                    <div><strong>Fraternidad:</strong> {{ $alquiler->fraternidad ?? '' }}</div>
                @elseif($alquiler->tipo_cliente == 'coreografo')
                <div><strong>Lugar de presentación</strong> {{ $alquiler->lugar_baile ?? '' }}</div>                @endif
            @else
                @if(!empty($alquiler->colegio))
                    <div><strong>Colegio:</strong> {{ $alquiler->colegio }}</div>
                    <div><strong>Curso:</strong> {{ $alquiler->curso ?? '' }}</div>
                @endif
            @endif

            <div><strong>Telf.:</strong> {{ $alquiler->telefono ?? '' }}</div>
        </div>

        <div class="info-fechas">
            <div><strong>Fecha de Recogida:</strong> {{ $alquiler->fecha_recogida ? \Carbon\Carbon::parse($alquiler->fecha_recogida)->subHours(4)->format('d-m-Y H:i') : '' }}</div>
            <div><strong>Fecha de Devolución:</strong> {{ $alquiler->fecha_devolucion ? \Carbon\Carbon::parse($alquiler->fecha_devolucion)->format('d-m-Y') : 'No especificada' }}</div>
            <div><strong>Garantía:</strong> {{ $alquiler->garantia ?? '' }}</div>
        </div>

        <table>
            <thead>
                <tr>
                    <th>Trajes</th>
                    <th style="text-align: center; width: 70px;">Piezas</th>
                    <th style="text-align: center; width: 60px;">Cant.</th>
                    <th>Precio U.</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @if(isset($detalles) && count($detalles) > 0)
                    @foreach($detalles as $det)
                    <tr>
                        <td>
                            <strong>{{ $det->nombreTraje ?? 'Sin nombre' }}</strong>
                            
                            @if(!empty($det->talla))
                                <br><span style="font-size: 11px; color: #27ae60; font-weight: bold;">Talla: {{ $det->talla }}</span>
                            @endif

                            @if(!empty($det->piezas))
                                <br><span style="font-size: 11px; color: #555;">Piezas: {{ $det->piezas }}</span>
                            @endif
                        </td>
                        
                        <td style="text-align: center; font-weight: bold; color: #5b2c6f;">
                            {{ $det->cantidadPiezas ?? 0 }}
                        </td>                        
                        <td style="text-align: center;">{{ $det->cantidad ?? 1 }}</td>                      
                        <td>Bs {{ number_format($det->precio ?? 0, 2) }}</td>
                        <td><strong>Bs {{ number_format($det->subTotal ?? 0, 2) }}</strong></td>
                    </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="5" style="text-align: center; color: red; padding: 20px;">
                            <strong>Error:</strong> No hay detalles de trajes para este recibo.
                        </td>
                    </tr>
                @endif
            </tbody>
        </table>

        <div class="total-seccion">
            <strong>TOTAL A PAGAR: Bs {{ number_format($totalGeneral ?? 0, 2) }}</strong>
        </div>

        @if(isset($alquiler->cobro_danio) && $alquiler->cobro_danio > 0)
        <div style="margin-top: 15px; padding: 12px 15px; background-color: #fff9db; border: 1.5px dashed #f59f00; border-radius: 6px; font-size: 13px;">
            <div style="font-weight: bold; font-size: 13px; color: #d9480f; margin-bottom: 6px; text-transform: uppercase;">
                ⚠️ Registro de Devolución - Cobro por Daño
            </div>
            <div style="display: flex; justify-content: space-between; flex-wrap: wrap; gap: 8px; margin-bottom: 4px;">
                <span><strong>Garantía:</strong> Bs {{ number_format($alquiler->monto_garantia ?? 0, 2) }}</span>
                <span style="color: #c92a2a;"><strong>Cobro por daño:</strong> - Bs {{ number_format($alquiler->cobro_danio, 2) }}</span>
                <span style="color: #2b8a3e;"><strong>Total devuelto al cliente:</strong> <strong>Bs {{ number_format($alquiler->monto_devuelto ?? 0, 2) }}</strong></span>
            </div>
            @if(!empty($alquiler->observacion_danio))
            <div style="margin-top: 5px; color: #495057;">
                <strong>Observación:</strong> {{ $alquiler->observacion_danio }}
            </div>
            @endif
        </div>
        @elseif(isset($alquiler->monto_devuelto) && $alquiler->monto_devuelto !== null)
        <div style="margin-top: 15px; padding: 10px 15px; background-color: #ebfbee; border: 1px dashed #40c057; border-radius: 6px; font-size: 13px; color: #2b8a3e; display: flex; justify-content: space-between;">
            <span><strong>Estado:</strong> Devolución completada sin daños</span>
            <span><strong>Garantía devuelta al cliente:</strong> <strong>Bs {{ number_format($alquiler->monto_devuelto, 2) }}</strong></span>
        </div>
        @endif

        <div class="nota-legal">
            <strong>NOTA:</strong> Al alquilar los trajes de este local no se aceptan devoluciones de dinero o cambio de traje. LOS TRAJES DEBEN SER DEVUELTOS ANTES DEL MEDIO DÍA SIGUIENTE, caso contrario será tomado como nuevo flete. Las prendas deben ser retiradas con este recibo, dentro de los 30 días. Después de esta fecha, la casa no se responsabiliza. GRACIAS.
        </div>
    </div>

    <div class="no-print" style="text-align: center; margin-top: 20px;">
        <button class="btn-print" onclick="window.print()" style="background-color: #27ae60; color: white; padding: 10px 20px; border: none; border-radius: 4px; cursor: pointer; font-weight: bold;">Imprimir Comprobante 🖨️</button>
        <button class="btn-print cerrar" onclick="volverACategorias()" style="background-color: #6c757d; color: white; padding: 10px 20px; border: none; border-radius: 4px; cursor: pointer; font-weight: bold; margin-left: 10px;">Volver a Categorías</button>
    </div>

    <script>
        // Linisanna ti history tapno saan a mabalin ti agsubli kadagiti napalabas a paset
        if (window.history.replaceState) {
            window.history.replaceState(null, "", "{{ url('/stock?modo=alquilar') }}");
        }

        // Function a mangiturong kenka a direkta kadagiti uppat a kategoria
        function volverACategorias() {
            window.location.replace("{{ url('/stock?modo=alquilar') }}");
        }

        // No i-klik ti ukom ti flecha "Atrás" ti navegador, mapan a direkta iti Dashboard
        window.onpopstate = function() {
            window.location.href = "{{ route('dashboard') }}";
        };
    </script>

</body>
</html>