<?php

namespace App\Http\Controllers;

use App\Persona;
use App\Proveedor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Validator;

class ProveedorController extends Controller
{
    //
    public function index(Request $request)
    {
        //
        if(!$request->ajax())
            return redirect('/');

        $buscar = $request->buscar;
        $criterio = $request->criterio;

        $data = Proveedor::from('proveedores as p')
        ->join('personas as pe', 'pe.id', '=', 'p.id')
        ->select(['pe.id', 'pe.nombre', 'pe.tipo_documento', 'pe.num_documento', 'pe.direccion', 'pe.telefono', 'pe.email','p.contacto', 'p.telefono_contacto'])
        ->when($buscar, function($query) use($criterio, $buscar){
            return $query->where('pe.'. $criterio, 'like', '%'.$buscar.'%');
        })
        ->orderby('id', 'desc')->paginate(10);

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

        try {
            //code...
            DB::beginTransaction();
            $persona = new Persona;
            $persona->nombre = $request->nombre;
            $persona->tipo_documento = $request->tipo_documento;
            $persona->num_documento = $request->num_documento;
            $persona->direccion = $request->direccion;
            $persona->telefono = $request->telefono;
            $persona->email = $request->email;
            $persona->save();

            $proveedor = new Proveedor;
            $proveedor->contacto = $request->contacto;
            $proveedor->telefono_contacto = $request->telefono_contacto;
            $proveedor->id = $persona->id;
            $proveedor->save();

            DB::commit();

        } catch (Exception $e) {
            //throw $th;

            DB::rollBack();

            return response()->json([
                'error' => true,
                'message' => 'El proveedor no se guardo con exito.',
                'redirect' => ''
            ], 201);
        }
        //


        return response()->json([
            'error' => false,
            'message' => 'El proveedor se guardo con exito.',
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

        try {
            //code...
            $proveedor = Proveedor::findorFail($request->id);
            $persona = Persona::findorFail($proveedor->id);

            DB::beginTransaction();

            $persona->nombre = $request->nombre;
            $persona->tipo_documento = $request->tipo_documento;
            $persona->num_documento = $request->num_documento;
            $persona->direccion = $request->direccion;
            $persona->telefono = $request->telefono;
            $persona->email = $request->email;
            $persona->save();

            $proveedor->contacto = $request->contacto;
            $proveedor->telefono_contacto = $request->telefono_contacto;
            $proveedor->save();

            DB::commit();

        } catch (Exception $e) {
            //throw $th;

            DB::rollBack();

            return response()->json([
                'error' => true,
                'message' => 'El proveedor no se guardo con exito.',
                'redirect' => ''
            ], 201);
        }

        return response()->json([
            'error' => false,
            'message' => 'El proveedor se actualizo con exito.',
            'redirect' => ''
        ], 200);
    }

    public function selectActivo(Request $request){


        $filtro = $request->filtro;
        $data = Proveedor::from("proveedores as p")
        ->join("personas as pe", "pe.id", "=", "p.id")
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
