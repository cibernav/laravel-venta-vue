<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Ingreso;
use App\IngresoDetalle;
use DB;
use Carbon\Carbon;
use Validator;
use Auth;
use App\Repositories\Notification;

class IngresoController extends Controller
{
    //
    protected $notification;

    //injecto la clase de repository
    public function __construct(Notification $notification){
        $this->notification = $notification;
    }

    public function index(Request $request)
    {
        //
        // if(!$request->ajax())
        //     return redirect('/');

        $buscar = $request->buscar;
        $criterio = $request->criterio;

        $data = Ingreso::from('ingresos as i')
        ->join('personas as pe', 'pe.id', '=', 'i.idproveedor')
        ->join('users as u', 'u.id', '=', 'i.idusuario')
        ->select(['i.id', 'i.tipo_comprobante', 'i.serie_comprobante', 'i.num_comprobante', 'i.fecha_hora', 'i.impuesto', 'i.total', 'i.estado',
        'pe.nombre as proveedor', 'u.username as usuario'])
        ->when($buscar, function($query) use($criterio, $buscar){
            return $query->where('i.'. $criterio, 'like', '%'.$buscar.'%');
        })
        ->orderby('i.id', 'desc')->paginate(10);

        return response()->json([
            'pagination' => [
                'total'        => $data->total(),
                'current_page' => $data->currentPage(),
                'per_page'     => $data->perPage(),
                'last_page'    => $data->lastPage(),
                'from'         => $data->firstItem(),
                'to'           => $data->lastItem(),
            ],
            'data' => $data
        ], 200);
    }

    public function store(Request $request)
    {
        if(!$request->ajax())
            return redirect('/');

        $validator = Validator::make($request->input(), Ingreso::$rules, Ingreso::$message);
        if ($validator->fails()){
            return response()->json([
                'error' => true,
                'message' => $validator->errors()
            ], 422);
        }

        try {
            //code...
            DB::beginTransaction();

            $documento = new Ingreso();
            $documento->idproveedor = $request->idproveedor;
            $documento->idusuario = Auth::user()->id;
            $documento->tipo_comprobante = $request->tipo_comprobante;
            $documento->serie_comprobante = $request->serie_comprobante;
            $documento->num_comprobante = $request->num_comprobante;
            $documento->fecha_hora = Carbon::now()->toDateString();
            $documento->impuesto = $request->impuesto;
            $documento->subtotal = (float)$request->subtotal;
            $documento->igv = (float)$request->igv;
            $documento->total = (float)$request->total;
            $documento->estado = "Registrado";
            $documento->save();

            $items = $request->items;

            foreach ($items as $key => $value) {
                $item = new IngresoDetalle();
                $item->idingreso = $documento->id;
                $item->idarticulo = $value['idarticulo'];
                $item->cantidad = (integer)$value['cantidad'];
                $item->precio = (float)$value['precio'];
                $item->save();

            }
            DB::commit();

            $this->notification->Notify();

        } catch (Exception $e) {
            //throw $th;

            DB::rollBack();

            return response()->json([
                'error' => true,
                'message' => 'El usuario no se guardo con exito.',
                'redirect' => ''
            ], 201);
        }
        //


        return response()->json([
            'error' => false,
            'message' => 'El usuario se guardo con exito.',
            'redirect' => ''
        ], 201);
    }


    public function desactive(Request $request, $id){

        if(!$request->ajax())
            return redirect('/');

        $documento = Ingreso::findOrFail($id);
        $documento->estado = "Anulado";
        $documento->save();

        return response()->json([
            'error' => false,
            'message' => 'El documento se anulo con exito.',
            'redirect' => ''
        ], 200);

    }

    public function show($id){

        $data = Ingreso::from('ingresos as i')
        ->join('personas as pe', 'pe.id', '=', 'i.idproveedor')
        ->join('users as u', 'u.id', '=', 'i.idusuario')
        ->select(['i.id', 'i.tipo_comprobante', 'i.serie_comprobante', 'i.num_comprobante', 'i.fecha_hora', 'i.impuesto', 'i.subtotal', 'i.igv', 'i.total', 'i.estado',
        'pe.nombre as proveedor', 'u.username as usuario'])
        ->where('i.id', '=', $id)
        ->get();

        $items = IngresoDetalle::from('ingresos_detalle as d')
        ->join('articulos as a', 'a.id', '=', 'd.idarticulo')
        ->where('d.idingreso', '=', $id)
        ->select(['d.id', 'd.idarticulo', 'a.nombre as articulo', 'd.cantidad', 'd.precio'])
        ->get();

        return response()->json([
            'data' => [
                'master' => $data[0],//parsea un objeto
                'items' => $items
            ],
        ], 200);
    }

}
