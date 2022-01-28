<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name' => 'Marcos Oliveira',
            'email' => 'ezequieleso10@gmail.com',
            'password' => Hash::make('l3br0ngn4'),
            'admin' => true,
            'client' => false
        ]);
    }
}
