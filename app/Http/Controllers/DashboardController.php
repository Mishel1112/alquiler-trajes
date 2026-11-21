<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $hoy = Carbon::today()->toDateString();
        $estadosValidos = ['alquilado', 'activo', 'activos', 'entregado', 'lavado', 'ingresado_stock', 'ingresado al stock', 'disponible', 'finalizado'];

        // 1. Alquileres hoy: Cantidad total de recibos/alquileres generados hoy
        $alquileresHoy = DB::table('alquiler')
            ->where(function($q) use ($hoy) {
                $q->whereDate('created_at', $hoy)
                  ->orWhereDate('f_recogida', $hoy);
            })
            ->whereRaw("LOWER(TRIM(estado)) IN ('" . implode("','", $estadosValidos) . "')")
            ->count();

        // 2. Trajes salidos hoy: Suma total de prendas físicas alquiladas hoy
        $trajesSalidosHoy = (int) (DB::table('detalle_alquiler')
            ->join('alquiler', 'detalle_alquiler.idAlquiler', '=', 'alquiler.idAlquiler')
            ->where(function($q) use ($hoy) {
                $q->whereDate('alquiler.created_at', $hoy)
                  ->orWhereDate('alquiler.f_recogida', $hoy);
            })
            ->whereRaw("LOWER(TRIM(alquiler.estado)) IN ('" . implode("','", $estadosValidos) . "')")
            ->sum('detalle_alquiler.cantidad') ?? 0);

        // 3. Ingresos: Suma total del dinero generado por los alquileres de hoy (sum de subTotal)
        $ingresosHoy = (float) (DB::table('detalle_alquiler')
            ->join('alquiler', 'detalle_alquiler.idAlquiler', '=', 'alquiler.idAlquiler')
            ->where(function($q) use ($hoy) {
                $q->whereDate('alquiler.created_at', $hoy)
                  ->orWhereDate('alquiler.f_recogida', $hoy);
            })
            ->whereRaw("LOWER(TRIM(alquiler.estado)) IN ('" . implode("','", $estadosValidos) . "')")
            ->sum('detalle_alquiler.subTotal') ?? 0);

        $totalTrajes = DB::table('traje')->count();

        if ($totalTrajes > 0) {
            $alquiladosCount = DB::table('detalle_alquiler')
                ->join('alquiler', 'detalle_alquiler.idAlquiler', '=', 'alquiler.idAlquiler')
                ->whereIn(DB::raw('LOWER(alquiler.estado)'), ['alquilado', 'activo'])
                ->distinct('detalle_alquiler.idTraje')
                ->count('detalle_alquiler.idTraje');

            $reservadosCount = DB::table('detalle_alquiler')
                ->join('alquiler', 'detalle_alquiler.idAlquiler', '=', 'alquiler.idAlquiler')
                ->whereIn(DB::raw('LOWER(alquiler.estado)'), ['reservado'])
                ->distinct('detalle_alquiler.idTraje')
                ->count('detalle_alquiler.idTraje');

            $lavadosCount = DB::table('detalle_alquiler')
                ->join('alquiler', 'detalle_alquiler.idAlquiler', '=', 'alquiler.idAlquiler')
                ->whereIn(DB::raw('LOWER(alquiler.estado)'), ['lavado'])
                ->distinct('detalle_alquiler.idTraje')
                ->count('detalle_alquiler.idTraje');

            $alquiladosCount = min($alquiladosCount, $totalTrajes);
            $reservadosCount = min($reservadosCount, $totalTrajes - $alquiladosCount);
            $lavadosCount    = min($lavadosCount, $totalTrajes - ($alquiladosCount + $reservadosCount));
            $disponiblesCount = max(0, $totalTrajes - ($alquiladosCount + $reservadosCount + $lavadosCount));

            $porcDisponibles = round(($disponiblesCount / $totalTrajes) * 100);
            $porcAlquilados  = round(($alquiladosCount / $totalTrajes) * 100);
            $porcReservados  = round(($reservadosCount / $totalTrajes) * 100);
            $porcLavados     = round(($lavadosCount / $totalTrajes) * 100);

            // Ajuste para garantizar que la suma sea exactamente 100%
            $suma = $porcDisponibles + $porcAlquilados + $porcReservados + $porcLavados;
            if ($suma !== 100) {
                $porcDisponibles += (100 - $suma);
            }
        } else {
            $porcDisponibles = 0;
            $porcAlquilados  = 0;
            $porcReservados  = 0;
            $porcLavados     = 0;
        }

        $proximasDevoluciones = DB::table('alquiler')
            ->join('detalle_alquiler', 'alquiler.idAlquiler', '=', 'detalle_alquiler.idAlquiler')
            ->join('traje', 'detalle_alquiler.idTraje', '=', 'traje.idTraje')
            ->whereIn(DB::raw('LOWER(alquiler.estado)'), ['alquilado', 'activo'])
            ->select('traje.nombre as traje', 'alquiler.f_devolucion as fecha_devolucion', 'alquiler.idAlquiler')
            ->orderBy('alquiler.f_devolucion', 'asc')
            ->limit(5)
            ->get();

        $trajesLavadoCount = DB::table('devolucion')
            ->where('estado', 'lavado')
            ->count();

        $lavadoAtrasadoCount = DB::table('devolucion')
            ->where('estado', 'lavado')
            ->where('f_devolucion', '<', Carbon::now()->subDays(2))
            ->count();

        $nuevoUsuario = DB::table('usuario')
            ->orderBy('created_at', 'desc')
            ->first();

        return view('dashboard', compact(
            'alquileresHoy',
            'trajesSalidosHoy',
            'ingresosHoy',
            'porcDisponibles',
            'porcAlquilados',
            'porcReservados',
            'porcLavados',
            'proximasDevoluciones',
            'trajesLavadoCount',
            'lavadoAtrasadoCount',
            'nuevoUsuario'
        ));
    }
}
