<?php

use Faker\Generator as Faker;

$factory->define(App\Categoria::class, function (Faker $faker) {
    return [
        'nombre' => $faker->unique()->sentence(2),
        'descripcion' => $faker->paragraph(1), // solo un parrafo
        'condicion'  => $faker->randomElement([0 , 1])
    ];
});
