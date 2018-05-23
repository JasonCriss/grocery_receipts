<?php

use Illuminate\Database\Seeder;

class ReceiptsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $receipts = factory(App\Receipt::class, 1000)->create();
    }
}
