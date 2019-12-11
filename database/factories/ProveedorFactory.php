<?php

use Faker\Generator as Faker;
use App\Proveedor;

$factory->define(Proveedor::class, function (Faker $faker) {
    return [
        //

        'contacto' => $faker->name,
        'telefono_contacto' => $faker->phoneNumber
    ];
});
