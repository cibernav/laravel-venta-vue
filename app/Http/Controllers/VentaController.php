<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Carbon\Carbon;
use App\Venta;
use App\VentaDetalle;
use Validator;
use Auth;
use App\Repositories\Notification;

class VentaController extends Controller
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

        $data = Venta::from('ventas as v')
        ->join('personas as pe', 'pe.id', '=', 'v.idcliente')
        ->join('users as u', 'u.id', '=', 'v.idusuario')
        ->select(['v.id', 'v.tipo_comprobante', 'v.serie_comprobante', 'v.num_comprobante', 'v.fecha_hora', 'v.igv as impuesto', 'v.total', 'v.estado',
        'pe.nombre as cliente', 'u.username as usuario'])
        ->when($buscar, function($query) use($criterio, $buscar){
            return $query->where('v.'. $criterio, 'like', '%'.$buscar.'%');
        })
        ->orderby('v.id', 'desc')->paginate(10);

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

        $validator = Validator::make($request->input(), Venta::$rules, Venta::$message);
        if ($validator->fails()){
            return response()->json([
                'error' => true,
                'message' => $validator->errors()
            ], 422);
        }

        $itemsVal = collect([]);
        $items = collect($request->items);
        $items->each(function($item, $key) use($itemsVal){
            $key++;
            if((float)$item['precio'] <= 0){
                $itemsVal->push("El item " . $key . ", debe tener un precio mayor a 0.");
            }
            if((int)$item['cantidad'] <= 0){
                $itemsVal->push("El item " . $key . ", debe tener una cantidad mayor a 0.");
            }

            if((int)$item['cantidad'] > (int)$item['stock']){
                $itemsVal->push("El item " . $key . ", supera el stock disponible(" + $item['stock'] + ").");
            }

            if((float)$item['descuento'] < 0){
                $itemsVal->push("El item " . $key . ", debe tener un descuento mayor o igual a 0.");
            }

            if((double)$item['descuento'] > (double)($item['cantidad'] * $item['precio']) ){
                $itemsVal->push("El item " . $key . ", debe tener un descuento no mayor al subtotal.");
            }
        });

        if ($itemsVal->count() > 0){
            return response()->json([
                'error' => true,
                'message' => [ 'items' => $itemsVal->toarray() ]
            ], 422);
        }


        try {
            //code...
            DB::beginTransaction();

            $documento = new Venta();
            $documento->idcliente = $request->idcliente;
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

            //$items = $request->items;

            foreach ($items as $key => $value) {
                $item = new VentaDetalle();
                $item->idventa = $documento->id;
                $item->idarticulo = $value['idarticulo'];
                $item->cantidad = (integer)$value['cantidad'];
                $item->precio = (float)$value['precio'];
                $item->descuento = (float)$value['descuento'];
                $item->save();

            }

            DB::commit();

            $this->notification->Notify();

        } catch (Exception $e) {
            //throw $th;

            DB::rollBack();

            return response()->json([
                'error' => true,
                'message' => 'El documento no se guardo con exito.',
                'redirect' => ''
            ], 201);
        }
        //


        return response()->json([
            'error' => false,
            'message' => 'El documento se guardo con exito.',
            'redirect' => '',
            'id' => $documento->id
        ], 201);
    }


    public function desactive(Request $request, $id){

        if(!$request->ajax())
            return redirect('/');

        $documento = Venta::findOrFail($id);
        $documento->estado = "Anulado";
        $documento->save();

        return response()->json([
            'error' => false,
            'message' => 'El documento se anulo con exito.',
            'redirect' => ''
        ], 200);

    }

    public function show($id){

        $data = Venta::from('ventas as v')
        ->join('personas as pe', 'pe.id', '=', 'v.idcliente')
        ->join('users as u', 'u.id', '=', 'v.idusuario')
        ->select(['v.id', 'v.tipo_comprobante', 'v.serie_comprobante', 'v.num_comprobante', 'v.fecha_hora', 'v.impuesto', 'v.subtotal', 'v.igv', 'v.total', 'v.estado',
        'pe.nombre as cliente', 'u.username as usuario'])
        ->where('v.id', '=', $id)
        ->get();

        $items = VentaDetalle::from('ventas_detalle as d')
        ->join('articulos as a', 'a.id', '=', 'd.idarticulo')
        ->where('d.idventa', '=', $id)
        ->select(['d.id', 'd.idarticulo', 'a.nombre as articulo', 'd.cantidad', 'd.precio', 'd.descuento'])
        ->get();

        return response()->json([
            'data' => [
                'master' => $data[0],//parsea un objeto
                'items' => $items
            ],
        ], 200);
    }
}
