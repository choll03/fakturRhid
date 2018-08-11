<?php

use Illuminate\Database\Seeder;
use App\Item;
use Faker\Factory;

class ItemsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Factory::create();

        Item::truncate();

        for ($i=0; $i < 100; $i++) { 
        	$item = Item::create([
        		'nama_item' => $faker->lastName,
        		'ukuran' => $faker->numberBetween(10, 100).' x '. $faker->numberBetween(10, 100) . ' mm',
        		'harga_item' => $faker->numberBetween(8000, 100000),
        		'stok' => $faker->randomDigit,
        		'edit_mode' => 0
        	]);
        }
    }
}
