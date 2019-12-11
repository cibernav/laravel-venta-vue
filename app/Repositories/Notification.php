<?php

namespace App\Repositories;

use App\User;
use App\Notifications\NotifyAdmin;
use \DB;

class Notification{


    public function notify(){
        $fechaActual = date('Y-m-d');
        $totVentas = DB::table('ventas')->wheredate('created_at', $fechaActual)->count();
        $totIngresos = DB::table('ingresos')->wheredate('created_at', $fechaActual)->count();

        $arrayDato = [
            'ventas' => [
                'total' => $totVentas,
                'msj' => 'Ventas'
            ],
            'ingresos' => [
                'total' => $totIngresos,
                'msj' => 'Ingresos'
            ]
        ];

        $users = User::take(2)->get();

        $users->each(function($value) use($arrayDato) {
            User::findOrFail($value->id)->notify(new NotifyAdmin($arrayDato));
        });
    }
}

