<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Auth;
class NotificacionController extends Controller
{
    //

    public function get(){

        $fechanow = date('Y-m-d') . ' 00:00:00';
        $data = auth::user()->notifications()
        ->where('created_at', '>', $fechanow)
        ->orderby('created_at', 'desc')
        ->first();

        return response()->json([
            'data' => $data ? $data->data : []
        ], 200);

    }
}
