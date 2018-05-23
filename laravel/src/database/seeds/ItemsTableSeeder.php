<?php

use Illuminate\Database\Seeder;
use App\Receipt;
use App\Product;
use App\Item;

class ItemsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker\Factory::create();

        $receipts = Receipt::all();
        foreach ($receipts as $receipt){
            for($i=0;$i<$receipt->numitems;$i++){
                $product = Product::find($faker->numberBetween(1,200));
                Item::create([
                    'name' => $product->name,
                    'product_id' => $product->id,
                    'receipt_id' => $receipt->id,
                    'quantity' => $faker->numberBetween(1,5),
                    'cost' => $faker->randomFloat($nbMaxDecimals = 2, $min = .5, $max = 25)
                ]);
            }
        }
    }
}
