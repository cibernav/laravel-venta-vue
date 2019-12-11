<?php

use App\User;
use App\Articulo;
use App\Categoria;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Routing\Middleware\ThrottleRequests;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UsersTableSeeder::class);
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');

        Categoria::truncate();
        Articulo::truncate();
        //DB::table('tabla_pivot')->truncate();

        //Desactiva los eventos de eloquent - created - updated
        User::flushEventListeners();
        Categoria::flushEventListeners();
        Articulo::flushEventListeners();

        //Crear usuario admin
        $this->call(UsersTableSeeder::class);

        factory(Categoria::class, 30)->create();
        factory(Articulo::class, 200)->create();

        //Crear rol por defecto

        $this->call(PersonaTableSeeder::class);
    }
}
