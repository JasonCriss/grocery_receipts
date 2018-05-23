<?php

use Faker\Generator as Faker;

$factory->define(App\Receipt::class, function (Faker $faker) {
    return [
        'raw'=>'Fake Data',
        'transactiondate' => $faker->dateTimeBetween($startDate = '-1 years', $endDate = 'now', $timezone = null),
        'totalsales' => $faker->randomFloat($nbMaxDecimals = 2, $min = 5, $max = 150),
        'numitems' => $faker->numberBetween($min = 1, $max = 20),
        'processed' => 1
        ];
});
