<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

$factory->define(App\User::class, function (Faker\Generator $faker) {
    static $password;

    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'password' => $password ?: $password = bcrypt('secret'),
        'remember_token' => str_random(10),
    ];
});


$factory->define(App\Models\Message::class, function (Faker\Generator $faker) {
    return [
        'site_id' => 1,
        'type' => 1,
        'title' => $faker->sentence,
        'content' => $faker->paragraph,
        'member_id' => $faker->randomElements(array(3,4, 5))[0],
        'state' => \App\Models\Message::STATE_NORMAL,
    ];
});
