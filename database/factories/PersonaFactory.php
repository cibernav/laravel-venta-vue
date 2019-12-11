<?php

use Faker\Generator as Faker;
use App\Persona;

$factory->define(Persona::class, function (Faker $faker) {
    return [
        //
        'nombre' => $faker->unique()->name,
        'tipo_documento' =>  $faker->randomElement([ 'DNI', 'RUC', 'PASS']),
        'num_documento' => $faker->unique()->numerify('#########'),
        'direccion' => $faker->address,
        'telefono' => $faker->phoneNumber,
        'email' => $faker->email
    ];
});
