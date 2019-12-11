<?php

use App\User;
use Illuminate\Support\Str;
use Faker\Generator as Faker;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(App\User::class, function (Faker $faker) {

    return [
        'username' => $faker->unique()->firstName,
        'password' => '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm', // secret
        'condicion' => $faker->randomElement([0 , 1]),
        'idrol' => $faker->randomElement([1 , 2, 3]),
        'remember_token' => Str::random(10)
    ];


    // return [
    //     'name' => $faker->name,
    //     'email' => $faker->unique()->safeEmail,
    //     'email_verified_at' => now(),
    //     'password' => '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm', // secret
    //     'remember_token' => Str::random(10),
    //     'verified' => $verificado = $faker->randomElement([User::USUARIO_VERIFICADO, User::USUARIO_NO_VERIFICADO]),
    //     'verification_token' => $verificado == User::USUARIO_VERIFICADO ? null : User::generarVerificationToken(),
    //     'admin' => $faker->randomElement([User::USUARIO_ADMINISTRADOR, User::USUARIO_REGULAR])
    // ];

});
