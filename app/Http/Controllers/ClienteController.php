<?php

namespace App\Http\Controllers;

use App\Persona;
use Illuminate\Http\Request;
use Validator;
class ClienteController extends Controller
{
    //

    public function index(Request $request)
    {
        //
        if(!$request->ajax())
            return redirect('/');

        $buscar = $request->buscar;
        $criterio = $request->criterio;

        if($buscar == ''){
            $data = Persona::orderby('id', 'desc')->paginate(10);
        }
        else{
            $data = Persona::where($criterio, 'like', '%'.$buscar.'%')->orderby('id', 'desc')->paginate(10);
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


    public function store(Request $request)
    {
        if(!$request->ajax())
            return redirect('/');

        $validator = Validator::make($request->input(), Persona::$rules);
        if ($validator->fails()){
            return response()->json([
                'error' => true,
                'message' => $validator->errors()
            ], 422);
        }

        //
        $categoria = new Persona;
        $categoria->nombre = $request->nombre;
        $categoria->tipo_documento = $request->tipo_documento;
        $categoria->num_documento = $request->num_documento;
        $categoria->direccion = $request->direccion;
        $categoria->telefono = $request->telefono;
        $categoria->email = $request->email;
        $categoria->save();

        return response()->json([
            'error' => false,
            'message' => 'El cliente se guardo con exito.',
            'redirect' => ''
        ], 201);
    }


    public function update(Request $request, $id)
    {
        //
        if(!$request->ajax())
            return redirect('/');

        $validator = Validator::make($request->input(), Persona::rulesUpdate($id));
        if ($validator->fails()){
            return response()->json([
                'error' => true,
                'message' => $validator->errors()
            ], 422);
        }

        $categoria = Persona::findOrFail($id);
        $categoria->nombre = $request->nombre;
        $categoria->tipo_documento = $request->tipo_documento;
        $categoria->num_documento = $request->num_documento;
        $categoria->direccion = $request->direccion;
        $categoria->telefono = $request->telefono;
        $categoria->email = $request->email;
        $categoria->save();

        return response()->json([
            'error' => false,
            'message' => 'El cliente se actualizo con exito.',
            'redirect' => ''
        ], 200);
    }

    public function selectActivo(Request $request){

        if(!$request->ajax())
            return redirect('/');

            $filtro = $request->filtro;
            $data = Persona::from("personas as pe")
            ->where("pe.nombre", "like", $filtro . '%')
            ->orwhere("pe.num_documento", "like", $filtro . '%')
            ->select(['pe.id', 'pe.nombre', 'pe.num_documento'])
            ->orderby("pe.nombre", "asc")
            ->get();

            return response()->json([
                'data' => $data
            ], 200);

    }
}
