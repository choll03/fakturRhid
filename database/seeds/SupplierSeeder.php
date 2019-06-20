<?php

use Illuminate\Database\Seeder;
use Faker\Factory;
use App\Supplier;

class SupplierSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Factory::create();
        Supplier::truncate();
        for ($i=0; $i < 5; $i++) { 
        	$item = Supplier::create([
        		'nama'          => "PT. " . $faker->company,
        		'no_telp'       => $faker->phoneNumber,
                'alamat'        => "Jl. " . $faker->streetName,
                'village_id'    => 3201200001 + $i
        	]);
        }
    }
}
