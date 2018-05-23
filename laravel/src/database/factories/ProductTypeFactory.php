<?php

use Faker\Generator as Faker;

$factory->define(\App\ProductType::class, function (Faker $faker) {
    return [
        'name'=>$faker->words($nb = $faker->numberBetween($min = 2, $max = 4), $asText = true)
    ];
});
