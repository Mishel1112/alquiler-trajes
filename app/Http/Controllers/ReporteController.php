<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ReporteController extends Controller
{
    // 1. Vista principal del menú de reportes
    public function index()
    {
        return view('auth.reporte');
    }

    // 2. Reporte Diario (Filtra por fecha y estados válidos)
    public function diario(Request $request)
    {
        $fechaSeleccionada = $request->input('fecha', Carbon::today()->format('Y-m-d'));

        $inicioDia = Carbon::parse($fechaSeleccionada)->startOfDay();
        $finDia = Carbon::parse($fechaSeleccionada)->endOfDay();

        $detallesHoy = DB::table('detalle_alquiler')
            ->join('alquiler', 'detalle_alquiler.idAlquiler', '=', 'alquiler.idAlquiler')
            ->leftJoin('traje', 'detalle_alquiler.idTraje', '=', 'traje.idTraje')
            ->where(function($q) use ($inicioDia, $finDia, $fechaSeleccionada) {
                $q->whereBetween('alquiler.created_at', [$inicioDia, $finDia])
                  ->orWhereDate('alquiler.f_recogida', $fechaSeleccionada)
                  ->orWhereDate('detalle_alquiler.created_at', $fechaSeleccionada);
            })
            ->whereRaw("LOWER(TRIM(alquiler.estado)) IN ('alquilado', 'activo', 'activos', 'entregado', 'lavado', 'ingresado_stock', 'ingresado al stock', 'disponible', 'finalizado')") 
            ->select('detalle_alquiler.subTotal', 'detalle_alquiler.cantidad', 'traje.nombre as nombreTraje')
            ->get();

        $totalRecaudado = (float) $detallesHoy->sum('subTotal');
        $trajesEntregados = (int) $detallesHoy->sum('cantidad');

        $modelosAgrupados = $detallesHoy->groupBy(function($item) {
            return strtoupper(trim($item->nombreTraje ?? 'OTRO'));
        });

        $palette = [
            '#e65100', '#f4511e', '#fb8c00', '#2e7d32', 
            '#1565c0', '#8e24aa', '#00838f', '#c2185b', 
            '#fbc02d', '#00897b', '#d84315', '#4527a0'
        ];

        $modelosData = [];
        if ($trajesEntregados > 0) {
            $idx = 0;
            foreach ($modelosAgrupados as $nombreModelo => $items) {
                $cantidad = (int) $items->sum('cantidad');
                $porcentaje = round(($cantidad / $trajesEntregados) * 100);
                $modelosData[] = [
                    'modelo' => $nombreModelo,
                    'cantidad' => $cantidad,
                    'porcentaje' => $porcentaje,
                    'color' => $palette[$idx % count($palette)]
                ];
                $idx++;
            }
        }

        return view('auth.reporte-diario', compact(
            'totalRecaudado', 
            'trajesEntregados', 
            'fechaSeleccionada', 
            'modelosData'
        ));
    }

    // 3. Reporte Mensual (Filtrado por mes, año y estados válidos incluyendo lavado)
    public function mensual(Request $request)
    {
        $mesSeleccionado = (int) $request->input('mes', Carbon::now()->month);
        $anioSeleccionado = (int) $request->input('anio', Carbon::now()->year);

        $detallesMes = DB::table('detalle_alquiler')
            ->join('alquiler', 'detalle_alquiler.idAlquiler', '=', 'alquiler.idAlquiler')
            ->where(function($q) use ($mesSeleccionado, $anioSeleccionado) {
                $q->where(function($sub) use ($mesSeleccionado, $anioSeleccionado) {
                    $sub->whereNotNull('alquiler.f_recogida')
                        ->whereMonth('alquiler.f_recogida', $mesSeleccionado)
                        ->whereYear('alquiler.f_recogida', $anioSeleccionado);
                })->orWhere(function($sub) use ($mesSeleccionado, $anioSeleccionado) {
                    $sub->whereNull('alquiler.f_recogida')
                        ->whereMonth('alquiler.created_at', $mesSeleccionado)
                        ->whereYear('alquiler.created_at', $anioSeleccionado);
                })->orWhere(function($sub) use ($mesSeleccionado, $anioSeleccionado) {
                    $sub->whereMonth('detalle_alquiler.created_at', $mesSeleccionado)
                        ->whereYear('detalle_alquiler.created_at', $anioSeleccionado);
                });
            })
            ->whereRaw("LOWER(TRIM(alquiler.estado)) IN ('alquilado', 'activo', 'activos', 'entregado', 'lavado', 'ingresado_stock', 'ingresado al stock', 'disponible', 'finalizado')")
            ->select(
                'detalle_alquiler.subTotal',
                'detalle_alquiler.cantidad',
                DB::raw('COALESCE(alquiler.f_recogida, detalle_alquiler.created_at, alquiler.created_at) as fecha_operacion')
            )
            ->get();

        $totalMes = (float) $detallesMes->sum('subTotal');
        $trajesMes = (int) $detallesMes->sum('cantidad');

        $semanasData = [
            'Semana 1' => 0,
            'Semana 2' => 0,
            'Semana 3' => 0,
            'Semana 4' => 0,
        ];

        foreach ($detallesMes as $detalle) {
            $dia = Carbon::parse($detalle->fecha_operacion)->day;
            if ($dia <= 7) {
                $semanasData['Semana 1'] += (float) $detalle->subTotal;
            } elseif ($dia <= 14) {
                $semanasData['Semana 2'] += (float) $detalle->subTotal;
            } elseif ($dia <= 21) {
                $semanasData['Semana 3'] += (float) $detalle->subTotal;
            } else {
                $semanasData['Semana 4'] += (float) $detalle->subTotal;
            }
        }

        return view('auth.reporte-mensual', compact(
            'totalMes', 
            'trajesMes', 
            'semanasData', 
            'mesSeleccionado', 
            'anioSeleccionado'
        ));
    }

    // 4. Reporte Anual (Comparativa global e histórico por gestión seleccionable)
    public function anual(Request $request)
    {
        // 1. Obtener datos agrupados de todas las gestiones
        $aniosData = DB::table('detalle_alquiler')
            ->join('alquiler', 'detalle_alquiler.idAlquiler', '=', 'alquiler.idAlquiler')
            ->whereRaw("LOWER(TRIM(alquiler.estado)) IN ('alquilado', 'activo', 'activos', 'entregado', 'lavado', 'ingresado_stock', 'ingresado al stock', 'disponible', 'finalizado')")
            ->select(
                DB::raw('YEAR(COALESCE(alquiler.f_recogida, detalle_alquiler.created_at, alquiler.created_at)) as anio'),
                DB::raw('SUM(detalle_alquiler.subTotal) as total'),
                DB::raw('SUM(detalle_alquiler.cantidad) as trajes'),
                DB::raw('COUNT(DISTINCT alquiler.idAlquiler) as operaciones')
            )
            ->groupBy(DB::raw('YEAR(COALESCE(alquiler.f_recogida, detalle_alquiler.created_at, alquiler.created_at))'))
            ->orderBy('anio', 'desc')
            ->get();

        $aniosDisponibles = $aniosData->pluck('anio')->map(fn($y) => (int) $y)->toArray();
        $anioActual = Carbon::now()->year;
        if (!in_array($anioActual, $aniosDisponibles)) {
            array_unshift($aniosDisponibles, $anioActual);
        }
        rsort($aniosDisponibles);

        // 2. Gestión seleccionada (por defecto la gestión actual)
        $anioSeleccionado = (int) $request->input('gestion', $request->input('anio', $anioActual));
        $anioAnterior = $anioSeleccionado - 1;

        // Datos específicos de la gestión seleccionada
        $datosGestionActual = $aniosData->firstWhere('anio', $anioSeleccionado);
        $datosGestionAnterior = $aniosData->firstWhere('anio', $anioAnterior);

        $totalGestion = $datosGestionActual ? (float) $datosGestionActual->total : 0.0;
        $trajesGestion = $datosGestionActual ? (int) $datosGestionActual->trajes : 0;
        $operacionesGestion = $datosGestionActual ? (int) $datosGestionActual->operaciones : 0;

        $totalGestionAnterior = $datosGestionAnterior ? (float) $datosGestionAnterior->total : 0.0;
        $trajesGestionAnterior = $datosGestionAnterior ? (int) $datosGestionAnterior->trajes : 0;

        $diferencia = $totalGestion - $totalGestionAnterior;
        $porcentajeCrecimiento = 0;
        if ($totalGestionAnterior > 0) {
            $porcentajeCrecimiento = round(($diferencia / $totalGestionAnterior) * 100, 1);
        } elseif ($totalGestion > 0) {
            $porcentajeCrecimiento = 100;
        }

        // 3. Desglose mensual detallado de la gestión seleccionada
        $desgloseMensualRaw = DB::table('detalle_alquiler')
            ->join('alquiler', 'detalle_alquiler.idAlquiler', '=', 'alquiler.idAlquiler')
            ->whereRaw("LOWER(TRIM(alquiler.estado)) IN ('alquilado', 'activo', 'activos', 'entregado', 'lavado', 'ingresado_stock', 'ingresado al stock', 'disponible', 'finalizado')")
            ->whereRaw("YEAR(COALESCE(alquiler.f_recogida, detalle_alquiler.created_at, alquiler.created_at)) = ?", [$anioSeleccionado])
            ->select(
                DB::raw('MONTH(COALESCE(alquiler.f_recogida, detalle_alquiler.created_at, alquiler.created_at)) as mes_num'),
                DB::raw('SUM(detalle_alquiler.subTotal) as total'),
                DB::raw('SUM(detalle_alquiler.cantidad) as trajes'),
                DB::raw('COUNT(DISTINCT alquiler.idAlquiler) as operaciones')
            )
            ->groupBy('mes_num')
            ->get()
            ->keyBy('mes_num');

        $nombresMeses = [
            1 => 'Enero', 2 => 'Febrero', 3 => 'Marzo', 4 => 'Abril',
            5 => 'Mayo', 6 => 'Junio', 7 => 'Julio', 8 => 'Agosto',
            9 => 'Septiembre', 10 => 'Octubre', 11 => 'Noviembre', 12 => 'Diciembre'
        ];

        $mesesDesglose = [];
        $maxTotalMes = 0;
        $mesPico = null;

        foreach ($nombresMeses as $num => $nombre) {
            $fila = $desgloseMensualRaw->get($num);
            $totalM = $fila ? (float) $fila->total : 0.0;
            $trajesM = $fila ? (int) $fila->trajes : 0;
            $operacionesM = $fila ? (int) $fila->operaciones : 0;
            $porcentaje = $totalGestion > 0 ? round(($totalM / $totalGestion) * 100, 1) : 0;

            if ($totalM > $maxTotalMes) {
                $maxTotalMes = $totalM;
                $mesPico = $nombre;
            }

            $mesesDesglose[] = [
                'numero' => $num,
                'nombre' => $nombre,
                'total' => $totalM,
                'trajes' => $trajesM,
                'operaciones' => $operacionesM,
                'porcentaje' => $porcentaje
            ];
        }

        // 4. Totales acumulados históricos globales
        $totalAcumulado = (float) $aniosData->sum('total');
        $historicoTrajes = (int) $aniosData->sum('trajes');
        $historicoOperaciones = (int) $aniosData->sum('operaciones');

        return view('auth.reporte-anual', compact(
            'aniosData',
            'aniosDisponibles',
            'anioSeleccionado',
            'anioActual',
            'anioAnterior',
            'totalGestion',
            'trajesGestion',
            'operacionesGestion',
            'totalGestionAnterior',
            'trajesGestionAnterior',
            'diferencia',
            'porcentajeCrecimiento',
            'mesesDesglose',
            'mesPico',
            'totalAcumulado',
            'historicoTrajes',
            'historicoOperaciones'
        ));
    }
}
