<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AlquilerController extends Controller
{
    private function procesarTallasTraje($idTraje)
    {
        if (!$idTraje) {
            return [];
        }

        $tallasBD = DB::table('tallas_traje')
            ->where('idTraje', $idTraje)
            ->get();

        $tallasProcesadas = [];

        foreach ($tallasBD as $t) {
            $tallasProcesadas[] = [
                'talla' => $t->talla,
                'stock' => intval($t->stock)
            ];
        }

        return $tallasProcesadas;
    }

    public function mostrarConfirmacion($id)
    {
        $trajeSeleccionado = DB::table('traje')
            ->where('idTraje', $id)
            ->first();

        if ($trajeSeleccionado) {
            $trajeSeleccionado->lista_tallas = $this->procesarTallasTraje($trajeSeleccionado->idTraje);
            $trajeSeleccionado->stock = collect($trajeSeleccionado->lista_tallas)->sum('stock');

            $catId = $trajeSeleccionado->idCategoria ?? $trajeSeleccionado->id_Categoria ?? 1;
            $categoria = DB::table('categoria')->where('idCategoria', $catId)->first();
            $trajeSeleccionado->categoria = $categoria->nombre ?? 'Trajes';
        }

        return view('auth.confirmar', compact('trajeSeleccionado'));
    }

    public function create(Request $request, $id = null)
    {
        if ($request->has('id_traje')) {
            session()->forget(['reserva_a_entregar', 'datos_reserva_cliente', 'detalles_reserva_trajes']);
        }

        $idParam = $id ?? $request->query('entrega_reserva');
        if ($idParam && !session()->has('reserva_a_entregar')) {
            $reserva = DB::table('alquiler')
                ->join('cliente', 'alquiler.idCliente', '=', 'cliente.idCliente')
                ->leftJoin('garantia', 'alquiler.idGarantia', '=', 'garantia.idGarantia')
                ->where('alquiler.idAlquiler', $idParam)
                ->whereIn(DB::raw('LOWER(alquiler.estado)'), ['reservado'])
                ->select(
                    'alquiler.idAlquiler as id',
                    'alquiler.idCliente',
                    'alquiler.idGarantia',
                    'cliente.tipo_cliente',
                    'cliente.colegio',
                    'cliente.curso',
                    'cliente.fraternidad',
                    'cliente.lugar_baile',
                    'alquiler.f_recogida as fecha_recogida',
                    'alquiler.f_recogida as fecha_reserva',
                    'alquiler.f_devolucion as fecha_devolucion',
                    'alquiler.f_devolucion as fecha_programada',
                    'cliente.nombre',
                    'cliente.telefono',
                    'garantia.monto as seña_monto',
                    'garantia.descripcion as seña_descripcion'
                )
                ->first();

            if ($reserva) {
                $detallesReserva = DB::table('detalle_alquiler')
                    ->join('traje', 'detalle_alquiler.idTraje', '=', 'traje.idTraje')
                    ->where('detalle_alquiler.idAlquiler', $idParam)
                    ->select(
                        'traje.idTraje',
                        'traje.nombre',
                        'detalle_alquiler.cantidad',
                        'detalle_alquiler.precio',
                        'detalle_alquiler.cantidadPiezas'
                    )
                    ->get();

                session([
                    'reserva_a_entregar' => $idParam,
                    'datos_reserva_cliente' => $reserva,
                    'detalles_reserva_trajes' => $detallesReserva
                ]);
            }
        }

        $trajeIdInput = $request->input('id_traje');
        $trajeId = is_array($trajeIdInput) ? ($trajeIdInput[0] ?? null) : $trajeIdInput;

        $trajeSeleccionado = null;

        if ($trajeId) {
            $trajeSeleccionado = DB::table('traje')
                ->where('idTraje', $trajeId)
                ->first();

            if ($trajeSeleccionado) {
                $trajeSeleccionado->lista_tallas = $this->procesarTallasTraje($trajeSeleccionado->idTraje);
                $trajeSeleccionado->stock = collect($trajeSeleccionado->lista_tallas)->sum('stock');
            }
        }

        if (session()->has('reserva_a_entregar')) {
            $nextId = session('reserva_a_entregar');
        } else {
            $ultimoAlquiler = DB::table('alquiler')->orderBy('idAlquiler', 'desc')->first();
            $nextId = $ultimoAlquiler ? ($ultimoAlquiler->idAlquiler + 1) : 1;
        }

        return view('auth.registrar', compact('trajeSeleccionado', 'nextId'));
    }

    public function createReserva(Request $request)
    {
        $trajeIdInput = $request->input('id_traje');
        $trajeId = is_array($trajeIdInput) ? ($trajeIdInput[0] ?? null) : $trajeIdInput;

        $trajeSeleccionado = null;
        if ($trajeId) {
            $trajeSeleccionado = DB::table('traje')->where('idTraje', $trajeId)->first();
            if ($trajeSeleccionado) {
                $trajeSeleccionado->lista_tallas = $this->procesarTallasTraje($trajeSeleccionado->idTraje);
                $trajeSeleccionado->stock = collect($trajeSeleccionado->lista_tallas)->sum('stock');
            }
        }

        $ultimoAlquiler = DB::table('alquiler')->orderBy('idAlquiler', 'desc')->first();
        $nextId = $ultimoAlquiler ? ($ultimoAlquiler->idAlquiler + 1) : 1;

        return view('auth.registrar_reserva', compact('trajeSeleccionado', 'nextId'));
    }

    public function vistaPreviaReserva(Request $request)
    {
        $numRecibo = $request->input('numero_recibo') ?? 'VISTA PREVIA';
        $montoGarantia = floatval($request->input('monto', 0));
        $descripcionGarantia = $request->input('descripcion', '');

        $tipoCliente = $request->input('tipo_cliente', 'publico general');
        $fechaProg = $request->input('fecha_programada') ?? $request->input('fecha_devolucion') ?? date('Y-m-d H:i:s');

        $textoGarantia = "Anticipo: Bs " . number_format($montoGarantia, 0) . (!empty($descripcionGarantia) ? " (" . $descripcionGarantia . ")" : "");

        $idTrajes = $request->input('id_traje', []);
        $cantidades = $request->input('cant', []);
        $precios = $request->input('precio_u', []);
        $cantidadPiezasArray = $request->input('cant_piezas', []);
        $tallasArray = $request->input('talla', []);

        $detalles = collect();
        $totalGeneral = 0;

        foreach ($idTrajes as $index => $idTraje) {
            $trajeInfo = DB::table('traje')->where('idTraje', $idTraje)->first();
            $cant = intval($cantidades[$index] ?? 1);
            $precio = floatval($precios[$index] ?? ($trajeInfo->precio ?? 0));
            
            $subTotal = $cant * $precio;
            $totalGeneral += $subTotal;

            $detalles->push((object) [
                'nombreTraje' => $trajeInfo->nombre ?? 'Traje Seleccionado',
                'piezas' => $trajeInfo->descripcion ?? '',
                'cantidadPiezas' => $cantidadPiezasArray[$index] ?? ($trajeInfo->cant_piezas ?? 1),
                'talla' => $tallasArray[$index] ?? '',
                'cantidad' => $cant,
                'precio' => $precio,
                'subTotal' => $subTotal
            ]);
        }

        $alquiler = (object) [
            'id' => $numRecibo,
            'numero_recibo' => $numRecibo,
            'idReserva' => $numRecibo,
            'fecha_recogida' => now(),
            'f_recogida' => now(),
            'fecha_reserva' => now(),
            'fecha_devolucion' => $fechaProg,
            'f_devolucion' => $fechaProg,
            'fecha_programada' => $fechaProg,
            'fecha_entrega' => $fechaProg,
            'nombre' => $request->input('nombre', 'Sin Nombre'),
            'telefono' => $request->input('telefono', 'Sin teléfono'),
            'tipo_cliente' => $tipoCliente,
            'tipo_operacion' => 'Reserva',
            'tipoOperacion' => 'Reserva',
            'monto' => $montoGarantia,
            'adelanto' => $montoGarantia,
            'total' => $totalGeneral,
            'colegio' => $tipoCliente == 'estudiante' ? $request->input('colegio', 'No especificado') : null,
            'curso' => $tipoCliente == 'estudiante' ? $request->input('curso', 'No especificado') : null,
            'fraternidad' => $tipoCliente == 'fraternidad' ? $request->input('fraternidad', 'No especificado') : null,
            'lugar_baile' => $request->input('lugar_baile', 'No especificado'),
            'garantia' => $textoGarantia,
            'detalles' => $detalles
        ];

        return view('auth.recibo_reserva', [
            'alquiler' => $alquiler,
            'detalles' => $detalles,
            'totalGeneral' => $totalGeneral,
            'datos' => $request->all()
        ]);
    }

    public function storeReserva(Request $request)
    {
        $request->validate([
            'nombre'   => 'required|string|max:255',
            'id_traje' => 'required|array',
            'monto'    => 'required|numeric|min:0',
        ]);

        try {
            DB::beginTransaction();

            $tipoCliente = $request->input('tipo_cliente', 'publico general');
            if ($tipoCliente === 'publico') {
                $tipoCliente = 'publico general';
            }
            $tipoValido = in_array($tipoCliente, ['estudiante', 'fraternidad', 'publico general', 'coreografo']) ? $tipoCliente : 'publico general';

            $idCliente = DB::table('cliente')->insertGetId([
                'nombre'       => $request->input('nombre'),
                'telefono'     => $request->input('telefono', ''),
                'tipo_cliente' => $tipoValido,
                'fraternidad'  => $tipoValido == 'fraternidad' ? $request->input('fraternidad') : null,
                'colegio'      => $tipoValido == 'estudiante' ? $request->input('colegio') : null,
                'curso'        => $tipoValido == 'estudiante' ? $request->input('curso') : null,
                'lugar_baile'  => $request->input('lugar_baile'),
                'created_at'   => now(),
                'updated_at'   => now(),
            ]);

            $idGarantia = DB::table('garantia')->insertGetId([
                'tipo'        => 'Anticipo',
                'monto'       => $request->input('monto', 0),
                'descripcion' => $request->input('descripcion', 'Anticipo para apartar reserva'),
                'created_at'  => now(),
                'updated_at'  => now(),
            ]);

            $fechaActual = now();
            $fechaProgramadaReserva = $request->input('fecha_programada') ?? $request->input('fecha_devolucion') ?? $request->input('fecha_recogida') ?? now();

            $idAlquiler = DB::table('alquiler')->insertGetId([
                'idCliente'      => $idCliente,
                'idGarantia'     => $idGarantia,
                'idUsuario'      => auth()->id() ?? 1,
                'f_recogida'     => $fechaActual,
                'f_devolucion'   => $fechaProgramadaReserva,
                'estado'         => 'Reservado',
                'tipo_operacion' => 'Reserva',
                'created_at'     => now(),
            ]);

            $idTrajes            = $request->input('id_traje', []);
            $cantidades          = $request->input('cant', []);
            $precios             = $request->input('precio_u', []);
            $tallasInput         = $request->input('talla', []);
            $cantidadPiezasArray = $request->input('cant_piezas', []);

            foreach ($idTrajes as $index => $idTraje) {
                $cantidad = intval($cantidades[$index] ?? 1);
                $precioU  = floatval($precios[$index] ?? 0);
                
                $tallaActual = trim($tallasInput[$index] ?? '');
                if (str_contains($tallaActual, ':')) {
                    $partes = explode(':', $tallaActual);
                    $tallaActual = trim($partes[0]);
                }
                
                $trajeInfo = DB::table('traje')->where('idTraje', $idTraje)->first();
                $cantidadPiezasReal = isset($cantidadPiezasArray[$index]) ? intval($cantidadPiezasArray[$index]) : ($trajeInfo->cant_piezas ?? 1);
                
                $subTotal = $cantidad * $precioU;

                // Descontamos stock de la talla en tallas_traje
                $actualizado = false;
                if (!empty($tallaActual) && $tallaActual !== 'Única') {
                    $affected = DB::table('tallas_traje')
                        ->where('idTraje', $idTraje)
                        ->where('talla', $tallaActual)
                        ->update(['stock' => DB::raw("CASE WHEN stock >= {$cantidad} THEN stock - {$cantidad} ELSE 0 END")]);
                    if ($affected > 0) {
                        $actualizado = true;
                    }
                }

                if (!$actualizado) {
                    $tallaRow = DB::table('tallas_traje')->where('idTraje', $idTraje)->first();
                    if ($tallaRow) {
                        DB::table('tallas_traje')
                            ->where('idTalla', $tallaRow->idTalla)
                            ->update(['stock' => DB::raw("CASE WHEN stock >= {$cantidad} THEN stock - {$cantidad} ELSE 0 END")]);
                    }
                }

                DB::table('detalle_alquiler')->insert([
                    'idAlquiler'     => $idAlquiler,
                    'idTraje'        => $idTraje,
                    'cantidad'       => $cantidad,
                    'cantidadPiezas' => $cantidadPiezasReal,
                    'precio'         => $precioU,
                    'subTotal'       => $subTotal,
                    'created_at'     => now(),
                    'updated_at'     => now(),
                ]);
            }

            DB::commit();

            return redirect()->route('alquiler.recibo', ['id' => $idAlquiler]);

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Error al guardar la reserva: ' . $e->getMessage()]);
        }
    }

    public function vistaPrevia(Request $request)
    {
        $tipoOperacionInput = $request->input('tipoOperacion') ?? ($request->input('tipo_operacion') === 'reservado' ? 'Reserva' : 'Alquiler');
        $esReserva = (strcasecmp($tipoOperacionInput, 'Reserva') === 0 || $request->input('tipo_operacion') === 'reservado');
        $tipoOpValor = $esReserva ? 'Reserva' : 'Alquiler';

        $montoGarantia = $request->input('monto', 0);
        $descripcionGarantia = $request->input('descripcion', '');
        $observacionesInput = trim($request->input('observaciones', ''));
        if (!empty($observacionesInput)) {
            $descripcionGarantia = !empty($descripcionGarantia) ? ($descripcionGarantia . " | Obs: " . $observacionesInput) : $observacionesInput;
        }

        if ($esReserva) {
            $textoGarantia = "Anticipo: Bs " . number_format($montoGarantia, 0) . (!empty($descripcionGarantia) ? " (" . $descripcionGarantia . ")" : "");
        } else {
            if (!empty($montoGarantia) && $montoGarantia > 0) {
                $textoGarantia = "Bs " . number_format($montoGarantia, 0) . " + " . $descripcionGarantia;
            } else {
                $textoGarantia = !empty($descripcionGarantia) ? $descripcionGarantia : 'No especificada';
            }
        }

        $tipoCliente = $request->input('tipo_cliente', 'publico general');
        $fechaProg = $request->input('fecha_programada') ?? $request->input('fecha_devolucion') ?? date('Y-m-d H:i:s');
        $numRecibo = session('reserva_a_entregar') ?? $request->input('numero_recibo') ?? 'VISTA PREVIA';

        $alquiler = (object) [
            'id' => $numRecibo,
            'numero_recibo' => $numRecibo,
            'idReserva' => $numRecibo,
            'fecha_recogida' => now(),
            'f_recogida' => now(),
            'fecha_reserva' => now(),
            'fecha_devolucion' => $fechaProg,
            'f_devolucion' => $fechaProg,
            'fecha_programada' => $fechaProg,
            'fecha_entrega' => $fechaProg,
            'nombre' => $request->input('nombre', 'Sin Nombre'),
            'telefono' => $request->input('telefono', 'Sin teléfono'),
            'tipo_cliente' => $tipoCliente,
            'tipo_operacion' => $tipoOpValor,
            'tipoOperacion' => $tipoOpValor,
            'monto' => $montoGarantia,
            'adelanto' => $montoGarantia,
            'colegio' => $tipoCliente == 'estudiante' ? $request->input('colegio', 'No especificado') : null,
            'curso' => $tipoCliente == 'estudiante' ? $request->input('curso', 'No especificado') : null,
            'fraternidad' => $tipoCliente == 'fraternidad' ? $request->input('fraternidad', 'No especificado') : null,
            'lugar_baile' => $request->input('lugar_baile', 'No especificado'),
            'garantia' => $textoGarantia
        ];

        $idTrajes = $request->input('id_traje', []);
        $cantidades = $request->input('cant', []);
        $precios = $request->input('precio_u', []);
        $cantidadPiezasArray = $request->input('cant_piezas', []);
        $tallasArray = $request->input('talla', []);

        $detalles = collect();
        $totalGeneral = 0;

        foreach ($idTrajes as $index => $idTraje) {
            $trajeInfo = DB::table('traje')->where('idTraje', $idTraje)->first();
            $cant = intval($cantidades[$index] ?? 1);
            $precio = floatval($precios[$index] ?? ($trajeInfo->precio ?? 0));
            
            $subTotal = $cant * $precio;
            $totalGeneral += $subTotal;

            $detalles->push((object) [
                'nombreTraje' => $trajeInfo->nombre ?? 'Traje Seleccionado',
                'piezas' => $trajeInfo->descripcion ?? '',
                'cantidadPiezas' => $cantidadPiezasArray[$index] ?? ($trajeInfo->cant_piezas ?? 1),
                'talla' => $tallasArray[$index] ?? '',
                'cantidad' => $cant,
                'precio' => $precio,
                'subTotal' => $subTotal
            ]);
        }

        $alquiler->total = $totalGeneral;
        $alquiler->detalles = $detalles;

        if ($esReserva) {
            return view('auth.recibo_reserva', compact('alquiler', 'detalles', 'totalGeneral'));
        }

        return view('auth.recibo', compact('alquiler', 'detalles', 'totalGeneral'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre'   => 'required|string|max:255',
            'id_traje' => 'required|array',
        ]);

        try {
            DB::beginTransaction();

            $idReservaOriginal = $request->input('id_reserva') ?: session('reserva_a_entregar');

            $tipoCliente = $request->input('tipo_cliente', 'publico general');
            if ($tipoCliente === 'publico') {
                $tipoCliente = 'publico general';
            }
            $tipoValido = in_array($tipoCliente, ['estudiante', 'fraternidad', 'publico general', 'coreografo']) ? $tipoCliente : 'publico general';

            $idCliente = DB::table('cliente')->insertGetId([
                'nombre'       => $request->input('nombre'),
                'telefono'     => $request->input('telefono', ''),
                'tipo_cliente' => $tipoValido,
                'fraternidad'  => $tipoValido == 'fraternidad' ? $request->input('fraternidad') : null,
                'colegio'      => $tipoValido == 'estudiante' ? $request->input('colegio') : null,
                'curso'        => $tipoValido == 'estudiante' ? $request->input('curso') : null,
                'lugar_baile'  => $request->input('lugar_baile'),
                'created_at'   => now(),
                'updated_at'   => now(),
            ]);

            $tipoOperacionInput = $request->input('tipoOperacion') ?? ($request->input('tipo_operacion') === 'reservado' ? 'Reserva' : 'Alquiler');
            if ($idReservaOriginal) {
                $tipoOperacionInput = 'Alquiler';
            }
            $esReserva = (strcasecmp($tipoOperacionInput, 'Reserva') === 0);
            $tipoOpValor = $esReserva ? 'Reserva' : 'Alquiler';
            $estadoValor = $esReserva ? 'Reservado' : 'Alquilado';

            if ($esReserva) {
                $tipoGarantia = 'Anticipo';
                $montoGarantia = floatval($request->input('monto', 0));
                $descripcionGarantia = $request->input('descripcion') ?: 'Anticipo para apartar reserva';
            } else {
                $tipoGarantia = $request->input('tipo') ?: ($request->input('tipo_garantia_opcion') ?: 'Dinero');
                $montoGarantia = floatval($request->input('monto', 0));
                $descripcionGarantia = $request->input('descripcion');
            }

            $observacionesInput = trim($request->input('observaciones', ''));
            if (!empty($observacionesInput)) {
                $descripcionGarantia = !empty($descripcionGarantia) ? ($descripcionGarantia . " | Obs: " . $observacionesInput) : $observacionesInput;
            }

            $idGarantia = DB::table('garantia')->insertGetId([
                'tipo'        => $tipoGarantia,
                'monto'       => $montoGarantia,
                'descripcion' => $descripcionGarantia,
                'created_at'  => now(),
                'updated_at'  => now(),
            ]);

            $fechaDevolucion = $request->input('fecha_devolucion') ?? $request->input('fecha_programada') ?? now()->addDays(2);

            if ($idReservaOriginal) {
                DB::table('alquiler')
                    ->where('idAlquiler', $idReservaOriginal)
                    ->update([
                        'idCliente'      => $idCliente,
                        'idGarantia'     => $idGarantia,
                        'idUsuario'      => auth()->id() ?? 1,
                        'f_recogida'     => now(),
                        'f_devolucion'   => $fechaDevolucion,
                        'estado'         => 'Alquilado',
                        'tipo_operacion' => 'Alquiler',
                    ]);

                DB::table('detalle_alquiler')->where('idAlquiler', $idReservaOriginal)->delete();
                $idAlquiler = $idReservaOriginal;

                session()->forget(['reserva_a_entregar', 'datos_reserva_cliente', 'detalles_reserva_trajes']);
            } else {
                $idAlquiler = DB::table('alquiler')->insertGetId([
                    'idCliente'      => $idCliente,
                    'idGarantia'     => $idGarantia,
                    'idUsuario'      => auth()->id() ?? 1,
                    'f_recogida'     => now(),
                    'f_devolucion'   => $fechaDevolucion,
                    'estado'         => $estadoValor,
                    'tipo_operacion' => $tipoOpValor,
                    'created_at'     => now(),
                ]);
            }

            $idTrajes            = $request->input('id_traje', []);
            $cantidades          = $request->input('cant', []);
            $precios             = $request->input('precio_u', []);
            $tallasInput         = $request->input('talla', []);
            $cantidadPiezasArray = $request->input('cant_piezas', []);

            foreach ($idTrajes as $index => $idTraje) {
                $cantidad = intval($cantidades[$index] ?? 1);
                $precioU  = floatval($precios[$index] ?? 0);
                
                $tallaActual = trim($tallasInput[$index] ?? '');
                if (str_contains($tallaActual, ':')) {
                    $partes = explode(':', $tallaActual);
                    $tallaActual = trim($partes[0]);
                }
                
                $trajeInfo = DB::table('traje')->where('idTraje', $idTraje)->first();
                $cantidadPiezasReal = isset($cantidadPiezasArray[$index]) ? intval($cantidadPiezasArray[$index]) : ($trajeInfo->cant_piezas ?? 1);
                
                $subTotal = $cantidad * $precioU;

                // Descontamos stock si no viene de una entrega previa ya descontada
                if (!$idReservaOriginal) {
                    $actualizado = false;
                    if (!empty($tallaActual) && $tallaActual !== 'Única') {
                        $affected = DB::table('tallas_traje')
                            ->where('idTraje', $idTraje)
                            ->where('talla', $tallaActual)
                            ->update(['stock' => DB::raw("CASE WHEN stock >= {$cantidad} THEN stock - {$cantidad} ELSE 0 END")]);
                        if ($affected > 0) {
                            $actualizado = true;
                        }
                    }

                    if (!$actualizado) {
                        $tallaRow = DB::table('tallas_traje')->where('idTraje', $idTraje)->first();
                        if ($tallaRow) {
                            DB::table('tallas_traje')
                                ->where('idTalla', $tallaRow->idTalla)
                                ->update(['stock' => DB::raw("CASE WHEN stock >= {$cantidad} THEN stock - {$cantidad} ELSE 0 END")]);
                        }
                    }
                }

                DB::table('detalle_alquiler')->insert([
                    'idAlquiler'     => $idAlquiler,
                    'idTraje'        => $idTraje,
                    'cantidad'       => $cantidad,
                    'cantidadPiezas' => $cantidadPiezasReal,
                    'precio'         => $precioU,
                    'subTotal'       => $subTotal,
                    'created_at'     => now(),
                    'updated_at'     => now(),
                ]);
            }

            DB::commit();

            return redirect()->route('alquiler.recibo', ['id' => $idAlquiler]);

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Error al guardar el alquiler: ' . $e->getMessage()]);
        }
    }

    public function verRecibo($id)
    {
        $alquiler = DB::table('alquiler')
            ->join('cliente', 'alquiler.idCliente', '=', 'cliente.idCliente')
            ->leftJoin('garantia', 'alquiler.idGarantia', '=', 'garantia.idGarantia')
            ->leftJoin('devolucion', 'alquiler.idAlquiler', '=', 'devolucion.idAlquiler')
            ->where('alquiler.idAlquiler', $id)
            ->select(
                'alquiler.idAlquiler as id',
                'alquiler.idAlquiler as numero_recibo',
                'alquiler.f_recogida as fecha_recogida',
                'alquiler.f_recogida as fecha_reserva',
                'alquiler.f_devolucion as fecha_devolucion',
                'alquiler.f_devolucion as fecha_programada',
                'alquiler.f_devolucion as fecha_entrega',
                'alquiler.tipo_operacion as tipoOperacion',
                'alquiler.tipo_operacion',
                'alquiler.estado',
                'cliente.tipo_cliente',
                'cliente.colegio',
                'cliente.curso',
                'cliente.fraternidad',
                'cliente.lugar_baile',
                'alquiler.created_at',
                'cliente.nombre',
                'cliente.telefono',
                'garantia.monto as monto', 
                'garantia.monto as adelanto', 
                'garantia.monto as monto_garantia', 
                'garantia.descripcion as garantia_descripcion',
                'devolucion.cobroDano as cobro_danio',
                'devolucion.observacion as observacion_danio',
                'devolucion.monto_devuelto as monto_devuelto'
            )
            ->first();

        if (!$alquiler) {
            abort(404, 'Recibo no encontrado.');
        }

        if (!empty($alquiler->monto_garantia) && $alquiler->monto_garantia > 0) {
            $alquiler->garantia = "Bs " . number_format($alquiler->monto_garantia, 0) . " + " . ($alquiler->garantia_descripcion ?? '');
        } else {
            $alquiler->garantia = $alquiler->garantia_descripcion ?? 'No especificada';
        }

        $detalles = DB::table('detalle_alquiler')
            ->join('traje', 'detalle_alquiler.idTraje', '=', 'traje.idTraje')
            ->where('detalle_alquiler.idAlquiler', $id)
            ->select(
                'traje.nombre as nombreTraje',
                'traje.descripcion as piezas',
                'detalle_alquiler.cantidadPiezas',
                'detalle_alquiler.cantidad',
                'detalle_alquiler.precio',
                'detalle_alquiler.subTotal'
            )
            ->get();

        $totalGeneral = $detalles->sum('subTotal');

        if (strtolower($alquiler->tipo_operacion) === 'reserva' || strtolower($alquiler->estado) === 'reservado') {
            return view('auth.recibo_reserva', compact('alquiler', 'detalles', 'totalGeneral'));
        }

        return view('auth.recibo', compact('alquiler', 'detalles', 'totalGeneral'));
    }

    public function index(Request $request)
    {
        $pestaña_actual = $request->get('tab', 'activos');

        // 1. Alquileres Activos (Alquilado o activo)
        $alquileres_activos_raw = DB::table('alquiler')
            ->join('cliente', 'alquiler.idCliente', '=', 'cliente.idCliente')
            ->leftJoin('garantia', 'alquiler.idGarantia', '=', 'garantia.idGarantia')
            ->whereIn(DB::raw('LOWER(alquiler.estado)'), ['alquilado', 'activo'])
            ->select(
                'alquiler.idAlquiler',
                'cliente.nombre as cliente',
                'alquiler.f_recogida as fecha_recogida',
                'alquiler.f_devolucion as fecha_limite',
                'alquiler.created_at',
                'garantia.monto as monto_garantia',
                'garantia.descripcion as garantia_descripcion'
            )
            ->orderBy('alquiler.idAlquiler', 'desc')
            ->get();

        $alquileres_activos = $alquileres_activos_raw->map(function ($item) {
            $detalles = DB::table('detalle_alquiler')
                ->join('traje', 'detalle_alquiler.idTraje', '=', 'traje.idTraje')
                ->where('detalle_alquiler.idAlquiler', $item->idAlquiler)
                ->select('traje.nombre', 'detalle_alquiler.cantidad')
                ->get();
            $item->detalles = $detalles;
            $item->traje = $detalles->pluck('nombre')->join(', ');
            $item->cantidad_total = $detalles->sum('cantidad');
            $item->numero_recibo = $item->idAlquiler;

            $fechaCreacion = $item->created_at ? Carbon::parse($item->created_at) : null;
            $fechaLimite = $item->fecha_limite ? Carbon::parse($item->fecha_limite)->endOfDay() : ($fechaCreacion ? $fechaCreacion->copy()->addHours(24) : null);

            $esAtrasado = false;
            if ($fechaLimite && now()->gt($fechaLimite)) {
                $esAtrasado = true;
            } elseif ($fechaCreacion && (!$item->fecha_limite || Carbon::parse($item->fecha_limite)->isToday()) && now()->gt($fechaCreacion->copy()->addHours(24))) {
                $esAtrasado = true;
            }

            $item->estado_plazo = $esAtrasado ? 'Atrasado' : 'Vigente';

            return $item;
        });

        // 2. Devoluciones / Lavandería / Historial de Ingreso al Stock desde tabla devolucion
        $devoluciones_raw = DB::table('devolucion')
            ->join('alquiler', 'devolucion.idAlquiler', '=', 'alquiler.idAlquiler')
            ->join('cliente', 'alquiler.idCliente', '=', 'cliente.idCliente')
            ->select(
                'devolucion.idDevolucion',
                'alquiler.idAlquiler',
                'cliente.nombre as cliente',
                'devolucion.f_real_devolucion as fecha_devolucion_real',
                'devolucion.f_ingreso_stock as fecha_ingreso_stock',
                'devolucion.f_devolucion as fecha_devolucion',
                'alquiler.created_at',
                'devolucion.estado'
            )
            ->orderBy('devolucion.idDevolucion', 'desc')
            ->get();

        $devoluciones = $devoluciones_raw->map(function ($dev) {
            $detalles = DB::table('detalle_alquiler')
                ->join('traje', 'detalle_alquiler.idTraje', '=', 'traje.idTraje')
                ->where('detalle_alquiler.idAlquiler', $dev->idAlquiler)
                ->select('traje.idTraje', 'traje.nombre', 'detalle_alquiler.cantidad')
                ->get();
            $dev->detalles = $detalles;
            $dev->traje = $detalles->pluck('nombre')->join(', ');
            $dev->cantidad_total = $detalles->sum('cantidad');
            $dev->numero_recibo = $dev->idAlquiler;

            if ($dev->estado === 'lavado') {
                $dev->estado_nombre = 'En lavado';
                $dev->estado_slug = 'lavado';
            } else {
                $dev->estado_nombre = 'Ingresado al stock';
                $dev->estado_slug = 'ingresado_stock';
            }

            $fDevolucionRaw = $dev->fecha_devolucion_real ?? $dev->fecha_devolucion ?? now();
            $dev->fecha_devolucion_formateada = Carbon::parse($fDevolucionRaw)->format('d/m/Y');

            $dev->fecha_ingreso_stock_formateada = $dev->fecha_ingreso_stock 
                ? Carbon::parse($dev->fecha_ingreso_stock)->format('d/m/Y')
                : ($dev->estado !== 'lavado' ? Carbon::parse($fDevolucionRaw)->format('d/m/Y') : 'Pendiente');

            return $dev;
        });

        // 3. Reservas
        $reservas_raw = DB::table('alquiler')
            ->join('cliente', 'alquiler.idCliente', '=', 'cliente.idCliente')
            ->whereIn(DB::raw('LOWER(alquiler.estado)'), ['reservado'])
            ->select(
                'alquiler.idAlquiler',
                'cliente.nombre as cliente',
                'alquiler.f_recogida as fecha_reserva',
                'alquiler.f_devolucion as fecha_entrega',
                'alquiler.f_devolucion as fecha_programada',
                'alquiler.estado',
                'cliente.tipo_cliente',
                'cliente.colegio',
                'cliente.curso',
                'cliente.fraternidad',
                'cliente.lugar_baile',
                'alquiler.created_at'
            )
            ->orderBy('alquiler.idAlquiler', 'desc')
            ->get();

        $reservas = $reservas_raw->map(function ($res) {
            $detalles = DB::table('detalle_alquiler')
                ->join('traje', 'detalle_alquiler.idTraje', '=', 'traje.idTraje')
                ->where('detalle_alquiler.idAlquiler', $res->idAlquiler)
                ->select('traje.nombre', 'detalle_alquiler.cantidad', 'detalle_alquiler.precio')
                ->get();
            $res->detalles = $detalles;
            $res->traje = $detalles->pluck('nombre')->join(', ');
            $res->numero_recibo = $res->idAlquiler;
            return $res;
        });

        // 4. Historial de Devoluciones y Alquileres Finalizados (Paginado)
        $historial_raw = DB::table('alquiler')
            ->join('cliente', 'alquiler.idCliente', '=', 'cliente.idCliente')
            ->leftJoin('usuario', 'alquiler.idUsuario', '=', 'usuario.idUsuario')
            ->leftJoin('devolucion', 'alquiler.idAlquiler', '=', 'devolucion.idAlquiler')
            ->whereIn(DB::raw('LOWER(alquiler.estado)'), ['finalizado'])
            ->select(
                'alquiler.idAlquiler',
                'cliente.nombre as cliente',
                'alquiler.f_recogida as fecha_recogida',
                'alquiler.f_devolucion as fecha_devolucion',
                'devolucion.f_real_devolucion as fecha_devolucion_real',
                'devolucion.f_ingreso_stock as fecha_ingreso_stock',
                'alquiler.created_at',
                'alquiler.estado',
                'usuario.nombre as usuario_nombre',
                'usuario.apellido as usuario_apellido'
            )
            ->orderBy('alquiler.idAlquiler', 'desc')
            ->paginate(8, ['*'], 'page')
            ->withQueryString();

        $historialIds = collect($historial_raw->items())->pluck('idAlquiler')->toArray();
        $detallesHistorial = DB::table('detalle_alquiler')
            ->join('traje', 'detalle_alquiler.idTraje', '=', 'traje.idTraje')
            ->whereIn('detalle_alquiler.idAlquiler', $historialIds)
            ->select('detalle_alquiler.idAlquiler', 'traje.nombre', 'detalle_alquiler.cantidad')
            ->get()
            ->groupBy('idAlquiler');

        foreach ($historial_raw->items() as $item) {
            $detalles = $detallesHistorial->get($item->idAlquiler, collect());
            $item->detalles = $detalles;
            $item->traje = $detalles->pluck('nombre')->join(', ');
            $item->cantidad_total = $detalles->sum('cantidad');
            $item->numero_recibo = $item->idAlquiler;

            $fIngresoRaw = $item->fecha_ingreso_stock ?? $item->fecha_devolucion_real ?? $item->fecha_devolucion ?? $item->created_at ?? now();
            $item->fecha_ingreso_historial_formateada = Carbon::parse($fIngresoRaw)->format('d/m/Y');

            $nomUsuario = !empty($item->usuario_nombre)
                ? trim($item->usuario_nombre . ' ' . ($item->usuario_apellido ?? ''))
                : (auth()->check() ? trim(auth()->user()->nombre . ' ' . (auth()->user()->apellido ?? '')) : 'Erlinda');
            $item->usuario = $nomUsuario;
        }

        $historial = $historial_raw;

        return view('auth.alquiler', compact('pestaña_actual', 'alquileres_activos', 'devoluciones', 'reservas', 'historial'));
    }

    public function formEntregarReserva($id)
    {
        $reserva = DB::table('alquiler')
            ->join('cliente', 'alquiler.idCliente', '=', 'cliente.idCliente')
            ->leftJoin('garantia', 'alquiler.idGarantia', '=', 'garantia.idGarantia')
            ->where('alquiler.idAlquiler', $id)
            ->whereIn(DB::raw('LOWER(alquiler.estado)'), ['reservado'])
            ->select(
                'alquiler.idAlquiler as id',
                'alquiler.idCliente',
                'alquiler.idGarantia',
                'cliente.tipo_cliente',
                'cliente.colegio',
                'cliente.curso',
                'cliente.fraternidad',
                'cliente.lugar_baile',
                'alquiler.f_recogida as fecha_recogida',
                'alquiler.f_recogida as fecha_reserva',
                'alquiler.f_devolucion as fecha_devolucion',
                'alquiler.f_devolucion as fecha_programada',
                'cliente.nombre',
                'cliente.telefono',
                'garantia.monto as seña_monto',
                'garantia.descripcion as seña_descripcion'
            )
            ->first();

        if (!$reserva) {
            return redirect()->route('alquileres', ['tab' => 'reservas'])
                           ->withErrors(['error' => 'La reserva no fue encontrada o ya fue entregada.']);
        }

        $detallesReserva = DB::table('detalle_alquiler')
            ->join('traje', 'detalle_alquiler.idTraje', '=', 'traje.idTraje')
            ->where('detalle_alquiler.idAlquiler', $id)
            ->select(
                'traje.idTraje',
                'traje.nombre',
                'detalle_alquiler.cantidad',
                'detalle_alquiler.precio',
                'detalle_alquiler.cantidadPiezas'
            )
            ->get();

        session([
            'reserva_a_entregar' => $id,
            'datos_reserva_cliente' => $reserva,
            'detalles_reserva_trajes' => $detallesReserva
        ]);

        return redirect()->route('registrar');
    }

    public function entregarForm($id)
    {
        return $this->formEntregarReserva($id);
    }

    public function procesarDevolucion(Request $request)
    {
        $idAlquiler = $request->input('id');

        $alquiler = DB::table('alquiler')->where('idAlquiler', $idAlquiler)->first();
        if (!$alquiler) {
            return back()->withErrors(['error' => 'Alquiler no encontrado.']);
        }

        $huboDanio = $request->input('hubo_danio') === 'si';
        $cobroDanio = $huboDanio ? floatval($request->input('cobro_danio', 0)) : 0;
        $observacionDanio = $huboDanio ? trim($request->input('observacion_danio', '')) : null;

        $garantia = DB::table('garantia')->where('idGarantia', $alquiler->idGarantia)->first();
        $montoGarantia = $garantia ? floatval($garantia->monto) : 0;

        $montoDevuelto = $request->filled('monto_devuelto')
            ? floatval($request->input('monto_devuelto'))
            : max(0, $montoGarantia - $cobroDanio);

        $idUsuario = auth()->check() ? auth()->id() : ($alquiler->idUsuario ?? 1);

        DB::table('alquiler')
            ->where('idAlquiler', $idAlquiler)
            ->update([
                'estado' => 'Finalizado',
            ]);

        $fechaEntrega = $alquiler->f_devolucion ? Carbon::parse($alquiler->f_devolucion)->toDateTimeString() : now()->toDateTimeString();

        $existeDev = DB::table('devolucion')->where('idAlquiler', $idAlquiler)->first();
        if ($existeDev) {
            DB::table('devolucion')->where('idAlquiler', $idAlquiler)->update([
                'f_devolucion'      => $fechaEntrega,
                'f_real_devolucion' => now()->toDateTimeString(),
                'estado'            => 'lavado',
                'cobroDano'         => $cobroDanio,
                'monto_devuelto'    => $montoDevuelto,
                'observacion'       => $observacionDanio,
                'idUsuario'         => $idUsuario,
            ]);
        } else {
            DB::table('devolucion')->insert([
                'idAlquiler'        => $idAlquiler,
                'f_devolucion'      => $fechaEntrega,
                'f_real_devolucion' => now()->toDateTimeString(),
                'estado'            => 'lavado',
                'cobroDano'         => $cobroDanio,
                'monto_devuelto'    => $montoDevuelto,
                'observacion'       => $observacionDanio,
                'idUsuario'         => $idUsuario,
                'f_ingreso_stock'   => null,
            ]);
        }

        return redirect()->route('alquileres', ['tab' => 'devoluciones'])
                       ->with('success', '¡Devolución registrada exitosamente!');
    }

    public function procesarLiberacion(Request $request)
    {
        $idAlquiler = $request->input('id');

        try {
            DB::beginTransaction();

            $devolucion = DB::table('devolucion')
                ->where('idAlquiler', $idAlquiler)
                ->first();

            if (!$devolucion) {
                DB::rollBack();
                return back()->withErrors(['error' => 'Registro de devolución no encontrado.']);
            }

            if ($devolucion->estado === 'ingresado_stock') {
                DB::rollBack();
                return redirect()->route('alquileres', ['tab' => 'devoluciones'])
                               ->with('error', 'Este registro ya fue ingresado previamente al stock.');
            }

            $detalles = DB::table('detalle_alquiler')
                ->where('idAlquiler', $idAlquiler)
                ->get();

            foreach ($detalles as $detalle) {
                $cant = intval($detalle->cantidad ?? 1);

                $tallasExistentes = DB::table('tallas_traje')
                    ->where('idTraje', $detalle->idTraje)
                    ->get();

                if ($tallasExistentes->count() > 0) {
                    DB::table('tallas_traje')
                        ->where('idTalla', $tallasExistentes->first()->idTalla)
                        ->increment('stock', $cant);
                }
            }

            DB::table('devolucion')
                ->where('idAlquiler', $idAlquiler)
                ->update([
                    'estado'          => 'ingresado_stock',
                    'f_ingreso_stock' => now()->toDateTimeString(),
                ]);

            DB::commit();

            return redirect()->route('alquileres', ['tab' => 'devoluciones'])
                           ->with('success', '¡Ingreso al stock confirmado! Los trajes se han sumado al inventario disponible.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Error al ingresar trajes al stock: ' . $e->getMessage()]);
        }
    }

    public function detalleDevolucion($id)
    {
        $dev = DB::table('devolucion')
            ->join('alquiler', 'devolucion.idAlquiler', '=', 'alquiler.idAlquiler')
            ->join('cliente', 'alquiler.idCliente', '=', 'cliente.idCliente')
            ->leftJoin('garantia', 'alquiler.idGarantia', '=', 'garantia.idGarantia')
            ->where('devolucion.idAlquiler', $id)
            ->select(
                'alquiler.idAlquiler',
                'cliente.nombre as cliente',
                'devolucion.f_real_devolucion as fecha_devolucion_real',
                'devolucion.f_ingreso_stock as fecha_ingreso_stock',
                'devolucion.f_devolucion as fecha_devolucion',
                'devolucion.estado',
                'garantia.monto as monto_garantia',
                'devolucion.cobroDano as cobro_danio',
                'devolucion.observacion as observacion_danio',
                'devolucion.monto_devuelto as monto_devuelto'
            )
            ->first();

        if (!$dev) {
            return response()->json(['error' => 'Devolución no encontrada'], 404);
        }

        $detalles = DB::table('detalle_alquiler')
            ->join('traje', 'detalle_alquiler.idTraje', '=', 'traje.idTraje')
            ->where('detalle_alquiler.idAlquiler', $id)
            ->select('traje.nombre', 'detalle_alquiler.cantidad')
            ->get();

        $fDevolucionRaw = $dev->fecha_devolucion_real ?? $dev->fecha_devolucion ?? now();
        $fIngresoRaw = $dev->fecha_ingreso_stock ?? ($dev->estado !== 'lavado' ? $dev->fecha_devolucion_real : null);

        return response()->json([
            'id' => $dev->idAlquiler,
            'cliente' => $dev->cliente,
            'estado' => ($dev->estado === 'lavado') ? 'En lavado' : 'Ingresado al stock',
            'estado_slug' => ($dev->estado === 'lavado') ? 'lavado' : 'ingresado_stock',
            'fecha_devolucion' => Carbon::parse($fDevolucionRaw)->format('d/m/Y'),
            'fecha_ingreso' => $fIngresoRaw ? Carbon::parse($fIngresoRaw)->format('d/m/Y') : 'Pendiente',
            'detalles' => $detalles,
            'cantidad_total' => $detalles->sum('cantidad'),
            'monto_garantia' => $dev->monto_garantia ? floatval($dev->monto_garantia) : 0,
            'cobro_danio' => $dev->cobro_danio ? floatval($dev->cobro_danio) : 0,
            'observacion_danio' => $dev->observacion_danio ?? null,
            'monto_devuelto' => $dev->monto_devuelto !== null ? floatval($dev->monto_devuelto) : null,
        ]);
    }

    public function cancelarReserva($id)
    {
        try {
            DB::beginTransaction();

            $detalles = DB::table('detalle_alquiler')
                ->where('idAlquiler', $id)
                ->get();

            foreach ($detalles as $detalle) {
                DB::table('tallas_traje')
                    ->where('idTraje', $detalle->idTraje)
                    ->increment('stock', $detalle->cantidad);
            }

            DB::table('alquiler')
                ->where('idAlquiler', $id)
                ->update(['estado' => 'Cancelado']);

            DB::commit();

            return redirect()->route('alquileres', ['tab' => 'reservas'])
                           ->with('success', '¡Reserva cancelada y trajes liberados correctamente!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Error al cancelar la reserva: ' . $e->getMessage()]);
        }
    }
}
