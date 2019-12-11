<?php

use Illuminate\Database\Seeder;
use App\User;
use App\Persona;
use App\Rol;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        Persona::truncate();
        User::truncate();
        Rol::truncate();

        $rol = new Rol();
        $rol->id = 1;
        $rol->nombre = "Administrador";
        $rol->descripcion = "Administradores de area";
        $rol->save();

        $rol = new Rol();
        $rol->id = 2;
        $rol->nombre = "Vendedor";
        $rol->descripcion = "Vendedor area venta";
        $rol->save();

        $rol = new Rol();
        $rol->id = 3;
        $rol->nombre = "Almacenero";
        $rol->descripcion = "Almacenero area compras";
        $rol->save();

        $persona = new Persona();
        $persona->nombre = "Administrador";
        $persona->tipo_documento = "";
        $persona->num_documento = "";
        $persona->direccion = "";
        $persona->telefono = "";
        $persona->email= "";
        $persona->save();

        $user = new User();
        $user->id = $persona->id;
        $user->username = "admin";
        $user->password = bcrypt('123456');
        $user->condicion = 1;
        $user->idrol = 1;
        $user->save();

        //En PersonaTableSeeder
        //factory(User::class, 100)->create();
    }
}
