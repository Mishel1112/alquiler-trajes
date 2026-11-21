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
            ->select('detalle_alquiler.subTotal', 'traje.nombre as nombreTraje')
            ->get();

        $totalRecaudado = (float) $detallesHoy->sum('subTotal');
        $trajesEntregados = $detallesHoy->count();

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
                $cantidad = $items->count();
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
                DB::raw('COALESCE(alquiler.f_recogida, detalle_alquiler.created_at, alquiler.created_at) as fecha_operacion')
            )
            ->get();

        $totalMes = (float) $detallesMes->sum('subTotal');
        $trajesMes = $detallesMes->count();

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

    // 4. Reporte Anual (Comparativa global incluyendo estados válidos)
    public function anual()
    {
        $aniosData = DB::table('detalle_alquiler')
            ->join('alquiler', 'detalle_alquiler.idAlquiler', '=', 'alquiler.idAlquiler')
            ->whereRaw("LOWER(TRIM(alquiler.estado)) IN ('alquilado', 'activo', 'activos', 'entregado', 'lavado', 'ingresado_stock', 'ingresado al stock', 'disponible', 'finalizado')")
            ->select(
                DB::raw('YEAR(COALESCE(alquiler.f_recogida, detalle_alquiler.created_at, alquiler.created_at)) as anio'),
                DB::raw('SUM(detalle_alquiler.subTotal) as total'),
                DB::raw('COUNT(detalle_alquiler.idDetalleAlquiler) as trajes')
            )
            ->groupBy(DB::raw('YEAR(COALESCE(alquiler.f_recogida, detalle_alquiler.created_at, alquiler.created_at))'))
            ->orderBy('anio', 'desc')
            ->get();

        $aniosDisponibles = $aniosData->pluck('anio')->toArray();

        $anioActual = Carbon::now()->year;
        $anioPasado = $anioActual - 1;

        $actualData = $aniosData->firstWhere('anio', $anioActual);
        $pasadoData = $aniosData->firstWhere('anio', $anioPasado);

        $totalAnualActual = $actualData ? (float) $actualData->total : 0.0;
        $trajesAnualActual = $actualData ? (int) $actualData->trajes : 0;

        $totalAnualPasado = $pasadoData ? (float) $pasadoData->total : 0.0;
        $trajesAnualPasado = $pasadoData ? (int) $pasadoData->trajes : 0;

        $diferencia = $totalAnualActual - $totalAnualPasado;
        $porcentajeCrecimiento = 0;
        if ($totalAnualPasado > 0) {
            $porcentajeCrecimiento = round(($diferencia / $totalAnualPasado) * 100);
        } elseif ($totalAnualActual > 0) {
            $porcentajeCrecimiento = 100;
        }

        $totalAcumulado = (float) $aniosData->sum('total');
        $historicoTrajes = (int) $aniosData->sum('trajes');

        return view('auth.reporte-anual', compact(
            'aniosData',
            'totalAcumulado',
            'historicoTrajes',
            'totalAnualActual',
            'trajesAnualActual',
            'totalAnualPasado',
            'trajesAnualPasado',
            'diferencia',
            'porcentajeCrecimiento',
            'anioActual',
            'anioPasado',
            'aniosDisponibles'
        ));
    }
}
