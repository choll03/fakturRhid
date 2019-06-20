<?php

use Illuminate\Database\Seeder;
use Faker\Factory;
use App\Customer;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Factory::create();
        Customer::truncate();
        for ($i=0; $i < 10; $i++) { 
        	$item = Customer::create([
        		'nama'          => "PT. " . $faker->company,
        		'no_telp'       => "(021) ". $faker->phoneNumber,
                'alamat'        => "Jl. " . $faker->streetName,
                'village_id'    => 3201200001 + $i
        	]);
        }
    }
}
