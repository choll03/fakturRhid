<?php

use Illuminate\Database\Seeder;
use App\User;

class UserSeedeer extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name'  => "mail",
            'email' => "ismail15000000@gmail.com",
            'password'  => bcrypt('123456'),

        ]);
    }
}
