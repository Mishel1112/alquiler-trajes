<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Comprobante de Reserva - Creaciones y Fantasías Erlinda</title>
    <link rel="stylesheet" href="{{ asset('css/recibo.css') }}">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }
        .recibo-box {
            max-width: 700px;
            margin: 0 auto;
            background: #fff;
            padding: 25px;
            border: 1px dashed #5b2c6f;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        .recibo-header {
            text-align: center;
        }
        .recibo-header h2 {
            margin: 0;
            color: #5b2c6f;
            font-size: 20px;
        }
        .recibo-header p {
            margin: 2px 0;
            font-size: 12px;
            color: #555;
        }
        .info-cliente, .info-fechas {
            margin-top: 15px;
            font-size: 13px;
            line-height: 1.6;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
            font-size: 13px;
        }
        table th, table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        table th {
            background-color: #f8f9fa;
        }
        .totales-grid {
            margin-top: 15px;
            border-top: 2px solid #5b2c6f;
            padding-top: 10px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 13px;
            background: #faf9fb;
            padding: 10px 15px;
            border-radius: 4px;
        }
        .totales-grid div {
            text-align: center;
        }
        .totales-grid .label {
            font-size: 11px;
            color: #666;
            margin-bottom: 2px;
            text-transform: uppercase;
        }
        .totales-grid .valor {
            font-weight: bold;
            font-size: 14px;
        }
        .nota-legal {
            margin-top: 15px;
            font-size: 11px;
            color: #555;
            text-align: justify;
            background: #fdfefe;
            padding: 10px;
            border: 1px solid #ebedef;
            border-radius: 4px;
        }
        .no-print {
            text-align: center;
            margin-top: 20px;
        }
        .btn-print {
            background-color: #27ae60;
            color: white;
            border: none;
            padding: 10px 20px;
            font-size: 14px;
            border-radius: 4px;
            cursor: pointer;
            font-weight: bold;
        }
        .btn-print.cerrar {
            background-color: #6c757d;
            margin-left: 10px;
        }
        @media print {
            .no-print {
                display: none;
            }
            body {
                background: white;
                padding: 0;
            }
            .recibo-box {
                border: 1px solid #000;
                box-shadow: none;
            }
        }
    </style>
</head>
<body>

    <div class="recibo-box">
        <!-- ENCABEZADO OFICIAL -->
        <div class="recibo-header">
            <h2>CREACIONES Y FANTASÍAS "ERLINDA"</h2>
            <p>Alquiler de Trajes Típicos y Fantasía en General!!!</p>
            <p>Plan 3.000 Av. Paurito zona Los Pocitos</p>
            <p>Telf.: 67793706 - 71686142</p>
            <hr style="border: 0; border-top: 1px solid #5b2c6f; margin: 10px auto; width: 80%;">
            
            @php
                $numRecibo = $alquiler->numero_recibo ?? ($alquiler->idReserva ?? ($alquiler->id ?? ($datos['id'] ?? 'N/A')));
            @endphp
            <p style="font-size: 15px; font-weight: bold; color: #b9770e; margin-top: 5px;">
                RESERVA Nº {{ $numRecibo }}
            </p>
        </div>

        <!-- DATOS DEL CLIENTE -->
        <div class="info-cliente">
            @php
                $nombreCliente = is_object($alquiler ?? null) ? ($alquiler->cliente->nombre_completo ?? ($alquiler->nombre ?? 'Sin especificar')) : ($datos['nombre'] ?? 'Sin especificar');
                $telefonoCliente = is_object($alquiler ?? null) ? ($alquiler->cliente->telefono ?? ($alquiler->telefono ?? 'Sin teléfono')) : ($datos['telefono'] ?? 'Sin teléfono');
                $tipoCliente = is_object($alquiler ?? null) ? ($alquiler->cliente->tipo_cliente ?? ($alquiler->tipo_cliente ?? '')) : ($datos['tipo_cliente'] ?? '');
                $colegioVal = is_object($alquiler ?? null) ? ($alquiler->cliente->colegio ?? ($alquiler->colegio ?? '')) : ($datos['colegio'] ?? '');
                $cursoVal = is_object($alquiler ?? null) ? ($alquiler->cliente->curso ?? ($alquiler->curso ?? '')) : ($datos['curso'] ?? '');
                $fraternidadVal = is_object($alquiler ?? null) ? ($alquiler->fraternidad ?? '') : ($datos['fraternidad'] ?? '');
                $lugarBaileVal = is_object($alquiler ?? null) ? ($alquiler->lugar_baile ?? '') : ($datos['lugar_baile'] ?? '');
            @endphp

            <div><strong>Nombre del Cliente:</strong> {{ $nombreCliente }}</div>
            <div><strong>Telf.:</strong> {{ $telefonoCliente }}</div>

            @if($tipoCliente == 'estudiante')
                <div><strong>Colegio:</strong> {{ $colegioVal }}</div>
                <div><strong>Curso:</strong> {{ $cursoVal }}</div>
            @elseif($tipoCliente == 'fraternidad' && !empty($fraternidadVal))
                <div><strong>Fraternidad:</strong> {{ $fraternidadVal }}</div>
            @elseif($tipoCliente == 'coreografo' && !empty($lugarBaileVal))
                <div><strong>Lugar de Presentación:</strong> {{ $lugarBaileVal }}</div>
            @endif
        </div>

        <!-- FECHAS DE RESERVA Y ENTREGA PROGRAMADA CORREGIDAS -->
        <div class="info-fechas">
            @php
                // Fecha de Registro o Reserva
                $fReserva = 'N/D';
                if (!empty($datos['fecha_reserva'])) {
                    $fReserva = \Carbon\Carbon::parse($datos['fecha_reserva'])->format('d/m/Y');
                } elseif (is_object($alquiler ?? null) && !empty($alquiler->fecha_reserva)) {
                    $fReserva = \Carbon\Carbon::parse($alquiler->fecha_reserva)->format('d/m/Y');
                } else {
                    $fReserva = date('d/m/Y');
                }

                // Fecha Programada de Entrega (Priorizando estrictamente el valor recibido)
                $fEntrega = 'N/D';
                if (!empty($datos['fecha_programada'])) {
                    $fEntrega = \Carbon\Carbon::parse($datos['fecha_programada'])->format('d/m/Y');
                } elseif (is_object($alquiler ?? null) && !empty($alquiler->fecha_programada)) {
                    $fEntrega = \Carbon\Carbon::parse($alquiler->fecha_programada)->format('d/m/Y');
                } elseif (!empty($datos['fecha_devolucion'])) {
                    $fEntrega = \Carbon\Carbon::parse($datos['fecha_devolucion'])->format('d/m/Y');
                }
            @endphp
            <div><strong>Fecha de Reserva:</strong> {{ $fReserva }}</div>
            <div><strong>Fecha Programada de Entrega:</strong> <span style="color: #b9770e; font-weight: bold;">{{ $fEntrega }}</span></div>
        </div>

        <!-- DETALLE DE TRAJES -->
        <table>
            <thead>
                <tr>
                    <th style="width: 40px; text-align: center;">Nº</th>
                    <th>Trajes Reservados</th>
                    <th style="text-align: center; width: 60px;">Cant.</th>
                    <th style="width: 90px;">Precio U.</th>
                    <th style="width: 90px;">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $totalGeneral = 0;
                @endphp

                @if(isset($alquiler->detalles) && count($alquiler->detalles) > 0)
                    @foreach($alquiler->detalles as $det)
                        @php
                            $subtotal = ($det->cantidad ?? 1) * ($det->precio ?? 0);
                            $totalGeneral += $subtotal;
                            $nombreTraje = $det->traje->nombre ?? ($det->nombreTraje ?? ($det->nombre ?? 'Traje'));
                        @endphp
                        <tr>
                            <td style="text-align: center;">{{ $loop->iteration }}</td>
                            <td>
                                <strong>{{ $nombreTraje }}</strong>
                                @if(!empty($det->piezas))
                                    <br><span style="font-size: 11px; color: #555;">Piezas: {{ $det->piezas }}</span>
                                @endif
                            </td>
                            <td style="text-align: center;">{{ $det->cantidad ?? 1 }}</td>
                            <td>Bs {{ number_format($det->precio ?? 0, 2) }}</td>
                            <td><strong>Bs {{ number_format($subtotal, 2) }}</strong></td>
                        </tr>
                    @endforeach
                @elseif(isset($detalles) && count($detalles) > 0)
                    @foreach($detalles as $det)
                        @php
                            $subtotal = ($det->cantidad ?? 1) * ($det->precio ?? 0);
                            $totalGeneral += $subtotal;
                            $nombreTraje = $det->nombreTraje ?? ($det->nombre ?? 'Traje');
                        @endphp
                        <tr>
                            <td style="text-align: center;">{{ $loop->iteration }}</td>
                            <td>
                                <strong>{{ $nombreTraje }}</strong>
                                @if(!empty($det->talla))
                                    <br><span style="font-size: 11px; color: #27ae60; font-weight: bold;">Talla: {{ $det->talla }}</span>
                                @endif
                                @if(!empty($det->piezas))
                                    <br><span style="font-size: 11px; color: #555;">Piezas: {{ $det->piezas }}</span>
                                @endif
                            </td>
                            <td style="text-align: center;">{{ $det->cantidad ?? 1 }}</td>
                            <td>Bs {{ number_format($det->precio ?? 0, 2) }}</td>
                            <td><strong>Bs {{ number_format($subtotal, 2) }}</strong></td>
                        </tr>
                    @endforeach
                @elseif(isset($datos['idTraje']))
                    @php
                        $idTrajes = $datos['idTraje'] ?? [];
                        $cantidades = $datos['cant'] ?? [];
                        $precios = $datos['precio_u'] ?? [];
                    @endphp
                    @foreach($idTrajes as $index => $idTraje)
                        @php
                            $cant = intval($cantidades[$index] ?? 1);
                            $precioU = floatval($precios[$index] ?? 0);
                            $subtotal = $cant * $precioU;
                            $totalGeneral += $subtotal;
                            $trajeModel = \App\Models\Traje::find($idTraje);
                            $nombreTraje = $trajeModel->nombre ?? 'Traje';
                        @endphp
                        <tr>
                            <td style="text-align: center;">{{ $loop->iteration }}</td>
                            <td><strong>{{ $nombreTraje }}</strong></td>
                            <td style="text-align: center;">{{ $cant }}</td>
                            <td>Bs {{ number_format($precioU, 2) }}</td>
                            <td><strong>Bs {{ number_format($subtotal, 2) }}</strong></td>
                        </tr>
                    @endforeach
                @endif
            </tbody>
        </table>

        <!-- TOTALES HORIZONTALES ORDENADOS -->
        @php
            $montoAdelanto = floatval($alquiler->adelanto ?? ($alquiler->monto ?? ($datos['monto'] ?? 0)));
            if(isset($alquiler->total) && $alquiler->total > 0) {
                $totalGeneral = $alquiler->total;
            }
            $saldoPendiente = $totalGeneral - $montoAdelanto;
        @endphp

        <div class="totales-grid">
            <div>
                <div class="label">Total Reserva</div>
                <div class="valor" style="color: #2c3e50;">Bs {{ number_format($totalGeneral, 2) }}</div>
            </div>
            <div>
                <div class="label">Anticipo</div>
                <div class="valor" style="color: #c0392b;">Bs {{ number_format($montoAdelanto, 2) }}</div>
            </div>
            <div>
                <div class="label">Saldo Pendiente</div>
                <div class="valor" style="color: #27ae60;">Bs {{ number_format(max(0, $saldoPendiente), 2) }}</div>
            </div>
        </div>

        <!-- NOTA LEGAL / INFORMATIVA -->
        <div class="nota-legal">
            <strong>NOTA DE RESERVA:</strong> Este comprobante garantiza el apartado de los trajes detallados bajo el número de recibo asignado. El cliente deberá retornar en la fecha programada para efectuar la entrega formal y el registro en alquileres activos. ¡GRACIAS POR SU PREFERENCIA!
        </div>
    </div>

    <!-- BOTONES DE ACCIÓN -->
    <div class="no-print">
        <button class="btn-print" onclick="window.print()">Imprimir Comprobante 🖨️</button>
        <button class="btn-print cerrar" onclick="window.location.href='{{ route('alquileres', ['tab' => 'reservas']) }}'">Volver a Reservas</button>
    </div>

</body>
</html>