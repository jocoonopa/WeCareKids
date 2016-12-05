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

$cnfaker = Faker\Factory::create('zh_CN');

$factory->define(App\Model\User::class, function (Faker\Generator $faker) use ($cnfaker) {
    return [
        'name' => $cnfaker->name,
        'email' => $faker->safeEmail,
        'password' => bcrypt(str_random(10)),
        'remember_token' => str_random(10),
    ];
});

$factory->define(App\Model\Child::class, function (Faker\Generator $faker) use ($cnfaker) {
    return [
        'name' => $cnfaker->name,
        'sex' => rand(0, 1),
        'birthday' => \Carbon\Carbon::now()->modify('- ' . rand(210, 3600) . ' days'),
        'school_name' => str_replace('公司', (0 === rand(0, 1) ? '小學' : '幼稚園'), $cnfaker->company)
    ];
});

$factory->define(App\Model\Guardian::class, function (Faker\Generator $faker) use ($cnfaker) {
    return [
        'name' => $cnfaker->name,
        'sex' => rand(0, 1),
        'birthday' => \Carbon\Carbon::now()->modify('- ' . rand(25 * 365, 65 * 365) . ' days'),
        'mobile' => rand(130, 139) . str_pad(rand(1, 99999999), 8, '0', STR_PAD_LEFT), //11
        'email' => $faker->safeEmail
    ];
});
