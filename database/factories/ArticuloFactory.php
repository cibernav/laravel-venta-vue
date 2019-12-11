<?php

use App\Categoria;
use Faker\Generator as Faker;

$factory->define(App\Articulo::class, function (Faker $faker) {
    return [
        'codigo' => $faker->ean13, //barcode
        'nombre' => $faker->unique()->name,
        'precio_venta' => $faker->randomFloat(2, 1, 50),
        'stock'  => $faker->numberBetween(1,30),
        'descripcion' => $faker->paragraph(1),
        'condicion' => $faker->randomElement([0, 1]),
        'idcategoria' => Categoria::where('condicion', '=', '1')->get()->random()->id
    ];

    // $faker->unique()->paragraph;
    // $faker->unique()->name;
    // $faker->unique()->randomNumber(5);
});
