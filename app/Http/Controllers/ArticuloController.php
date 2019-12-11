<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Articulo;

class ArticuloController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //

        if(!$request->ajax())
            return redirect('/');

        $buscar = $request->buscar;
        $criterio = $request->criterio;

        if($buscar == '')
            $buscar = '';

        $data = Articulo::from('articulos as a')
            ->join('categorias as c', 'c.id', '=', 'a.idcategoria')
            ->select('a.id', 'a.idcategoria', 'a.codigo', 'a.nombre', 'c.nombre as nombre_categoria', 'a.precio_venta', 'a.stock', 'a.descripcion', 'a.condicion')
            ->when($buscar, function($query) use($criterio, $buscar){
                return $query->where('a.'.$criterio, 'like', '%'. $buscar . '%');
            })
            ->orderby('a.id', 'desc')->paginate(10);

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

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        if(!$request->ajax())
            return redirect('/');


        $validator = Validator::make($request->input(), Articulo::$rules, Articulo::$message);
        if ($validator->fails()){
            return response()->json([
                'error' => true,
                'message' => $validator->errors()
            ], 422);
        }

        //
        $articulo = new Articulo;
        $articulo->idcategoria = $request->idcategoria;
        $articulo->codigo = $request->codigo;
        $articulo->nombre = $request->nombre;
        $articulo->precio_venta = $request->precio_venta;
        $articulo->stock = $request->stock;
        $articulo->descripcion = $request->descripcion;
        $articulo->condicion = 1;
        $articulo->save();

        return response()->json([
            'error' => false,
            'message' => 'El dato se guardo con exito.',
            'redirect' => ''
        ], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
        if(!$request->ajax())
            return redirect('/');

        $validator = Validator::make($request->input(), Articulo::rulesUpdate($id));
        if ($validator->fails()){
            return response()->json([
                'error' => true,
                'message' => $validator->errors()
            ], 422);
        }

        $articulo = Articulo::findOrFail($id);
        $articulo->idcategoria = $request->idcategoria;
        $articulo->codigo = $request->codigo;
        $articulo->nombre = $request->nombre;
        $articulo->precio_venta = $request->precio_venta;
        $articulo->stock = $request->stock;
        $articulo->descripcion = $request->descripcion;
        $articulo->condicion = 1;
        $articulo->save();

        return response()->json([
            'error' => false,
            'message' => 'El dato se actualizo con exito.',
            'redirect' => ''
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function active(Request $request, $id){

        if(!$request->ajax())
            return redirect('/');

        $articulo = Articulo::findOrFail($id);
        $articulo->condicion = !$articulo->condicion;
        $articulo->save();

        return response()->json([
            'error' => false,
            'message' => 'El dato se activo con exito.',
            'redirect' => ''
        ], 200);
    }

    public function buscarArticulo(Request $request){

        if(!$request->ajax())
            return redirect('/');

        $filtro = $request->filtro;

        //Se define el alias 'a', por el uso del campo delete_at en el order
        $data = Articulo::from('articulos as a')
        ->where('a.codigo', '=', $filtro)
        ->select('a.id', 'a.nombre')
        ->orderby('a.nombre', 'asc')
        ->take(1)->get();

        return response()->json([
            'data' => $data,
        ], 200);
    }

    public function buscarArticuloVenta(Request $request){

        if(!$request->ajax())
            return redirect('/');

        $filtro = $request->filtro;

        //Se define el alias 'a', por el uso del campo delete_at en el order
        $data = Articulo::from('articulos as a')
        ->where('a.codigo', '=', $filtro)
        ->where('a.stock', '>', '0')
        ->select('a.id', 'a.nombre', 'a.stock', 'a.precio_venta')
        ->orderby('a.nombre', 'asc')
        ->take(1)->get();

        return response()->json([
            'data' => $data,
        ], 200);
    }

    public function listar(Request $request)
    {
        //

        if(!$request->ajax())
            return redirect('/');

        $buscar = $request->buscar;
        $criterio = $request->criterio;

        if($buscar == '')
            $buscar = '';

        $data = Articulo::from('articulos as a')
            ->join('categorias as c', 'c.id', '=', 'a.idcategoria')
            ->select('a.id', 'a.idcategoria', 'a.codigo', 'a.nombre', 'c.nombre as nombre_categoria', 'a.precio_venta', 'a.stock', 'a.descripcion', 'a.condicion')
            ->when($buscar, function($query) use($criterio, $buscar){
                return $query->where('a.'.$criterio, 'like', '%'. $buscar . '%');
            })
            ->orderby('a.id', 'desc')->paginate(10);

        return response()->json([
            'data' => $data
        ], 200);
    }

    public function listarVenta(Request $request)
    {
        //

        if(!$request->ajax())
            return redirect('/');

        $buscar = $request->buscar;
        $criterio = $request->criterio;

        if($buscar == '')
            $buscar = '';

        $data = Articulo::from('articulos as a')
            ->join('categorias as c', 'c.id', '=', 'a.idcategoria')
            ->where('a.stock', '>', '0')
            ->select('a.id', 'a.idcategoria', 'a.codigo', 'a.nombre', 'c.nombre as nombre_categoria', 'a.precio_venta', 'a.stock', 'a.descripcion', 'a.condicion')
            ->when($buscar, function($query) use($criterio, $buscar){
                return $query->where('a.'.$criterio, 'like', '%'. $buscar . '%');
            })
            ->orderby('a.id', 'desc')->paginate(10);

        return response()->json([
            'data' => $data
        ], 200);
    }

}
