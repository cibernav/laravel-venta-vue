<?php

namespace App\Http\Controllers;

use App\Rol;
use Illuminate\Http\Request;

class RolController extends Controller
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
            $data = Rol::orderby('id', 'desc')->paginate(10);
        }
        else{
            $data = Rol::where($criterio, 'like', '%'.$buscar.'%')->orderby('id', 'desc')->paginate(10);
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

    public function selectactivo(){
        $data = Rol::where('condicion', '=', '1')
        ->select(['id', 'nombre'])
        ->orderby('nombre', 'asc')
        ->get();

        return response()->json([
            'data' => $data
        ], 200);

    }


}
