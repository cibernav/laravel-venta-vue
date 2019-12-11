<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Validator;
use Auth;
use App\User;
use App\Persona;

class UserController extends Controller
{
    //
    public function index(Request $request)
    {
        //
        // if(!$request->ajax())
        //     return redirect('/');

        $buscar = $request->buscar;
        $criterio = $request->criterio;

        $data = User::from('users as u')
        ->join('personas as pe', 'pe.id', '=', 'u.id')
        ->join('roles as r', 'r.id', '=', 'u.idrol')
        ->select(['pe.id', 'pe.nombre', 'pe.tipo_documento', 'pe.num_documento', 'pe.direccion', 'pe.telefono', 'pe.email',
        'u.username', 'u.condicion', 'u.idrol', 'r.nombre as rol'])
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

        $validator = Validator::make($request->input(), User::$rules, User::$message);
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

            $user = new User;
            $user->username = $request->username;
            $user->password = bcrypt($request->password);
            $user->condicion = 1;
            $user->idrol = $request->idrol;
            $user->id = $persona->id;
            $user->save();

            DB::commit();

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

    public function update(Request $request, $id)
    {
        //
        if(!$request->ajax())
            return redirect('/');

        $validator = Validator::make($request->input(), User::rulesUpdate($id), User::$message);
        if ($validator->fails()){
            return response()->json([
                'error' => true,
                'message' => $validator->errors()
            ], 422);
        }

        try {
            //code...
            $user = User::findorFail($request->id);
            $persona = Persona::findorFail($user->id);

            DB::beginTransaction();

            $persona->nombre = $request->nombre;
            $persona->tipo_documento = $request->tipo_documento;
            $persona->num_documento = $request->num_documento;
            $persona->direccion = $request->direccion;
            $persona->telefono = $request->telefono;
            $persona->email = $request->email;
            $persona->save();

            $user->username = $request->username;
            if ($request->password)
                $user->password = bcrypt($request->password);
            $user->idrol = $request->idrol;
            $user->save();

            DB::commit();

        } catch (Exception $e) {
            //throw $th;

            DB::rollBack();

            return response()->json([
                'error' => true,
                'message' => 'El usuario no se guardo con exito.',
                'redirect' => ''
            ], 201);
        }

        return response()->json([
            'error' => false,
            'message' => 'El usuario se actualizo con exito.',
            'redirect' => ''
        ], 200);
    }

    public function active(Request $request, $id){

        if(!$request->ajax())
            return redirect('/');

        $user = User::findOrFail($id);
        $user->condicion = !$user->condicion;
        $user->save();

        return response()->json([
            'error' => false,
            'message' => 'El usuario se ' . ($user->condicion ? 'activo' : 'desactivo') . ' con exito.',
            'redirect' => ''
        ], 200);
    }

    public function selectRol(){
        return response()->json([
            'data' => [
                    'rol' => auth::user()->rol->nombre,
                    'auth' => true,
                    'token' => '000000000'
                ]
        ], 200);
    }
}
