<?php

use Illuminate\Database\Seeder;
use App\Persona;
use App\Proveedor;
use App\User;

class PersonaTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        Proveedor::truncate();
        //Persona::truncate();

        $personas = factory(Persona::class, 100)->create();
        $personas->each(function($p){
            if($p->tipo_documento == 'RUC'){
                $proveedor = factory(Proveedor::class, 1)->make();
                $proveedor->id = $p->id;
                $p->proveedor()->save($proveedor[0]);
            }
            else if($p->tipo_documento == 'DNI'){
                $user = factory(User::class, 1)->make();
                $user->id = $p->id;
                $p->user()->save($user[0]);
            }
        });

    }
}
