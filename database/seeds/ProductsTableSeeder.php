<?php

use Illuminate\Database\Seeder;
use App\Product;

class ProductsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker\Factory::create();

	    for($i = 0; $i < 100; $i++) {
	        Product::create([
	            'name' => $faker->userName,
	            'price' => $faker->randomDigit(4)."000",
	            'stock' => $faker->randomDigit(2),
	            'image' => $faker->imageUrl($width = 200, $height = 200),
	            'description' => $faker->paragraph,
	            'owner_id'=> $faker->randomDigit(1),
	        ]);
	    }
    }
}
