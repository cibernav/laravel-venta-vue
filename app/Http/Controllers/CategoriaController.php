<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;//Query Builder
use App\Categoria;

class CategoriaController extends ApiController
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

        if($buscar == ''){
            $data = Categoria::orderby('id', 'desc')->paginate(10);
        }
        else{
            $data = Categoria::where($criterio, 'like', '%'.$buscar.'%')->orderby('id', 'desc')->paginate(10);
        }

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

    public function selectActivo(Request $request){
        if(!$request->ajax())
            return redirect('/');

        $data = Categoria::where('condicion', '=', '1')
        ->select('id', 'nombre')
        ->orderby('nombre', 'asc')
        ->get();

        return response()->json([
            'data' => $data
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if(!$request->ajax())
            return redirect('/');

        $validator = Validator::make($request->input(), Categoria::$rules);
        if ($validator->fails()){
            return response()->json([
                'error' => true,
                'message' => $validator->errors()
            ], 422);
        }

        //
        $categoria = new Categoria;
        $categoria->nombre = $request->nombre;
        $categoria->descripcion = $request->descripcion;
        $categoria->condicion = 1;
        $categoria->save();

        return response()->json([
            'error' => false,
            'message' => 'La categoria se guardo con exito.',
            'redirect' => ''
        ], 201);
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

        $validator = Validator::make($request->input(), Categoria::rulesUpdate($id));
        if ($validator->fails()){
            return response()->json([
                'error' => true,
                'message' => $validator->errors()
            ], 422);
        }

        $categoria = Categoria::findOrFail($id);
        $categoria->nombre = $request->nombre;
        $categoria->descripcion = $request->descripcion;
        $categoria->condicion = 1;
        $categoria->save();

        return response()->json([
            'error' => false,
            'message' => 'La categoria se actualizo con exito.',
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

        return response()->json([
            'error' => false,
            'message' => 'La categoria se elimino con exito.',
            'redirect' => ''
        ], 200);
    }

    public function active(Request $request, $id){

        if(!$request->ajax())
            return redirect('/');

        $categoria = Categoria::findOrFail($id);
        $categoria->condicion = !$categoria->condicion;
        $categoria->save();

        return response()->json([
            'error' => false,
            'message' => 'La categoria se activo con exito.',
            'redirect' => ''
        ], 200);
    }

}
