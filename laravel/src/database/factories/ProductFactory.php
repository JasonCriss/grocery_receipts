<?php

use Faker\Generator as Faker;

$factory->define(\App\Product::class, function (Faker $faker) {
    return [
        'name'=>$faker->words($nb = $faker->numberBetween($min = 1, $max = 5), $asText = true),
        'product_type_id' => $faker->numberBetween($min = 1, $max = 50)
    ];
});
