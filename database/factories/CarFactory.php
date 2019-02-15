<?php

use App\User;
use Faker\Generator as Faker;

$factory->define(App\Car::class, function (Faker $faker) {
    $faker->addProvider(new \Faker\Provider\Fakecar($faker));
    $v = $faker->vehicleArray();

    return [
        'user_id' => User::all()->random()->id,
        'category' => 'Car',
        'model' => $v['model'],
        'motor_power' => '1.0',
        'ports' => '4',
        'board' => $faker->vehicleRegistration('[A-Z]{3}-[0-9]{4}'),
        'type_vehicle' => $faker->vehicleType,
        'year' => $faker->biasedNumberBetween(1998,2019, 'sqrt'),
        'fuel' => $faker->vehicleFuelType,
        'mileage' => $faker->biasedNumberBetween(0,500000, 'sqrt'),
        'Exchange' => 'Automatic',
        'direction' => '',
        'color' => 'White',
        'options' => '',
    ];
});
