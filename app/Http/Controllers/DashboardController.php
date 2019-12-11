<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;

class DashboardController extends Controller
{
    //
    // se ejecuta para todas las peticiones entrantes.
    public function __invoke(Request $request){

        $anio = date('Y');
        $ingresos = DB::table('ingresos as i')
        ->select(DB::RAW('MONTH(I.FECHA_HORA) AS MES'), DB::RAW('YEAR(I.FECHA_HORA) AS ANIO'), DB::RAW('SUM(I.TOTAL) AS TOTAL'))
        ->where("I.ESTADO", '=', 'Registrado')
        ->whereYear('I.FECHA_HORA', $anio)
        ->groupby(DB::RAW('MONTH(I.FECHA_HORA)'), DB::RAW('YEAR(I.FECHA_HORA)'))
        ->get();

        $ventas = DB::table('ventas as v')
        ->select(DB::RAW('MONTH(V.FECHA_HORA) AS MES'), DB::RAW('YEAR(V.FECHA_HORA) AS ANIO'), DB::RAW('SUM(V.TOTAL) AS TOTAL'))
        ->where("V.ESTADO", '=', 'Registrado')
        ->whereYear('V.FECHA_HORA', $anio)
        ->groupby(DB::RAW('MONTH(V.FECHA_HORA)'), DB::RAW('YEAR(V.FECHA_HORA)'))
        ->get();

        return response()->json([
            'data' => [
                'ingresos' => $ingresos,
                'ventas' => $ventas,
                'anio' => $anio
            ]
        ], 200);
    }
}
