<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TrajeController extends Controller
{
    public function porCategoria($categoria)
    {
        $titulos = [
            'tipicos'          => 1,
            'disfraces'        => 2,
            'internacionales' => 3,
            'gala'             => 4
        ];

        $textosTitulos = [
            'tipicos'          => 'Trajes Típicos',
            'disfraces'        => 'Disfraces',
            'internacionales' => 'Trajes Internacionales',
            'gala'             => 'Trajes de Gala'
        ];

        $idCategoriaNum = $titulos[$categoria] ?? 1;
        $nombreCategoria = $textosTitulos[$categoria] ?? 'Inventario';

        // Obtenemos los trajes de la categoría
        $trajes = DB::table('traje')->where('idCategoria', $idCategoriaNum)->get();

        // Adjuntamos las tallas correspondientes a cada traje desde la tabla relacional y calculamos stock total
        foreach ($trajes as $traje) {
            $traje->tallas = DB::table('tallas_traje')
                ->where('idTraje', $traje->idTraje)
                ->get();
            $traje->stock = $traje->tallas->sum('stock');

            // Calcular cantidad de piezas reales a partir de los elementos separados por comas
            if (!empty($traje->pieza)) {
                $piezasArr = array_filter(array_map('trim', explode(',', $traje->pieza)));
                $traje->cant_piezas = count($piezasArr) > 0 ? count($piezasArr) : 1;
            } else {
                $traje->cant_piezas = $traje->cant_piezas ?: 1;
            }
        }

        return view('auth.stock_categoria', compact('trajes', 'nombreCategoria', 'categoria'));
    }

    // MÉTODO PARA VER LOS ATRIBUTOS COMPLETOS DEL TRAJE
    public function show($id)
    {
        $traje = DB::table('traje')->where('idTraje', $id)->first();

        if (!$traje) {
            abort(404, 'Traje no encontrado');
        }

        // Cargamos sus tallas asociadas y calculamos stock total
        $traje->tallas = DB::table('tallas_traje')
            ->where('idTraje', $traje->idTraje)
            ->get();
        $traje->stock = $traje->tallas->sum('stock');

        // Calcular cantidad de piezas reales a partir de los elementos separados por comas
        if (!empty($traje->pieza)) {
            $piezasArr = array_filter(array_map('trim', explode(',', $traje->pieza)));
            $traje->cant_piezas = count($piezasArr) > 0 ? count($piezasArr) : 1;
        } else {
            $traje->cant_piezas = $traje->cant_piezas ?: 1;
        }

        return view('auth.detalle_traje', compact('traje'));
    }

    public function store(Request $request)
    {
        // Sanitizar precio por si viene con coma decimal (ej. "35,00" -> "35.00")
        if ($request->has('precio')) {
            $request->merge(['precio' => str_replace(',', '.', strval($request->precio))]);
        }

        // Calcular la cantidad exacta de piezas a partir de las comas en 'pieza'
        $cantPiezas = 1;
        if ($request->filled('pieza')) {
            $piezasArray = array_filter(array_map('trim', explode(',', $request->pieza)));
            $cantPiezas = count($piezasArray) > 0 ? count($piezasArray) : 1;
        } elseif ($request->filled('cant_piezas')) {
            $cantPiezas = max(1, intval($request->cant_piezas));
        }
        $request->merge(['cant_piezas' => $cantPiezas]);

        $request->validate([
            'nombre'      => 'required|string|max:255',
            'precio'      => 'required|numeric',
            'cant_piezas' => 'required|integer|min:1',
            'tallas_arr'  => 'required|array|min:1',
            'tallas_arr.*.talla' => 'required|string|max:50',
            'tallas_arr.*.stock' => 'required|integer|min:0',
            'imagen'      => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $categoriasMap = [
            'tipicos'          => 1,
            'disfraces'        => 2,
            'internacionales' => 3,
            'gala'             => 4
        ];

        $categoriaTexto = $request->idCategoria;
        
        $idCategoriaNum = is_numeric($categoriaTexto) 
            ? $categoriaTexto 
            : ($categoriasMap[$categoriaTexto] ?? 1);

        $nombreImagen = null;
        if ($request->hasFile('imagen')) {
            $file = $request->file('imagen');
            $nombreImagen = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('img'), $nombreImagen);
        }

        // Calculamos el stock total a partir de las tallas para registrar la disponibilidad automática
        $totalStock = 0;
        if ($request->has('tallas_arr')) {
            foreach ($request->tallas_arr as $t) {
                $totalStock += intval($t['stock'] ?? 0);
            }
        }
        $estadoCalculado = $totalStock > 0 ? 'Disponible' : 'Agotado';

        // Insertamos el traje principal y obtenemos su ID generado
        $idTraje = DB::table('traje')->insertGetId([
            'idCategoria' => $idCategoriaNum,
            'nombre'       => $request->nombre,
            'pieza'        => $request->pieza,
            'cant_piezas'  => $cantPiezas,
            'precio'       => $request->precio,
            'estado'       => $estadoCalculado,
            'descripcion'  => $request->descripcion,
            'imagen'       => $nombreImagen,
            'created_at'   => now(),
            'updated_at'   => now(),
        ]);

        // Guardamos las tallas dinámicas en la tabla relacional
        if ($request->has('tallas_arr')) {
            foreach ($request->tallas_arr as $t) {
                if (!empty($t['talla'])) {
                    DB::table('tallas_traje')->insert([
                        'idTraje' => $idTraje,
                        'talla'    => $t['talla'],
                        'stock'    => $t['stock'] ?? 1,
                    ]);
                }
            }
        }

        return redirect()->route('stock.categoria', ['categoria' => is_numeric($categoriaTexto) ? 'tipicos' : $categoriaTexto])
                         ->with('success', '¡Traje añadido exitosamente!');
    }

    public function update(Request $request, $id)
    {
        // Sanitizar precio por si viene con coma decimal (ej. "35,00" -> "35.00")
        if ($request->has('precio')) {
            $request->merge(['precio' => str_replace(',', '.', strval($request->precio))]);
        }

        // Calcular la cantidad exacta de piezas a partir de las comas en 'pieza'
        $cantPiezas = 1;
        if ($request->filled('pieza')) {
            $piezasArray = array_filter(array_map('trim', explode(',', $request->pieza)));
            $cantPiezas = count($piezasArray) > 0 ? count($piezasArray) : 1;
        } elseif ($request->filled('cant_piezas')) {
            $cantPiezas = max(1, intval($request->cant_piezas));
        }
        $request->merge(['cant_piezas' => $cantPiezas]);

        $request->validate([
            'nombre'      => 'required|string|max:255',
            'precio'      => 'required|numeric',
            'cant_piezas' => 'required|integer|min:1',
            'tallas_arr'  => 'required|array|min:1',
            'tallas_arr.*.talla' => 'required|string|max:50',
            'tallas_arr.*.stock' => 'required|integer|min:0',
            'imagen'      => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $traje = DB::table('traje')->where('idTraje', $id)->first();
        
        if (!$traje) {
            abort(404, 'Traje no encontrado');
        }

        $nombreImagen = $traje->imagen ?? null;

        if ($request->hasFile('imagen')) {
            $file = $request->file('imagen');
            $nombreImagen = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('img'), $nombreImagen);
        }

        // Calculamos el stock total a partir de las tallas para la disponibilidad automática
        $totalStock = 0;
        if ($request->has('tallas_arr')) {
            foreach ($request->tallas_arr as $t) {
                $totalStock += intval($t['stock'] ?? 0);
            }
        }
        $estadoCalculado = $totalStock > 0 ? 'Disponible' : 'Agotado';

        // Actualizamos los datos principales del traje
        DB::table('traje')->where('idTraje', $id)->update([
            'nombre'      => $request->nombre,
            'pieza'       => $request->pieza,
            'cant_piezas' => $cantPiezas,
            'precio'      => $request->precio,
            'estado'      => $estadoCalculado,
            'descripcion' => $request->descripcion,
            'imagen'      => $nombreImagen,
            'updated_at'  => now(),
        ]);

        // Sincronizamos las tallas: eliminamos las anteriores y volvemos a insertar las del formulario
        DB::table('tallas_traje')->where('idTraje', $id)->delete();

        if ($request->has('tallas_arr')) {
            foreach ($request->tallas_arr as $t) {
                if (!empty($t['talla'])) {
                    DB::table('tallas_traje')->insert([
                        'idTraje' => $id,
                        'talla'    => $t['talla'],
                        'stock'    => $t['stock'] ?? 1,
                    ]);
                }
            }
        }

        return back()->with('success', '¡Traje actualizado exitosamente!');
    }

    public function destroy($id)
    {
        DB::table('tallas_traje')->where('idTraje', $id)->delete();
        DB::table('traje')->where('idTraje', $id)->delete();
        
        return back()->with('success', '¡Traje eliminado correctamente!');
    }
}
