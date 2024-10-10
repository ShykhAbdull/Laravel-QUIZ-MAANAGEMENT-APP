<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $admin  = User::create([
            'name' => 'Admin',
            'email' => 'mynameisshaikh20@gmail.com',
            'password' => Hash::make('admin'),
        ]);

        
        // Assign the Admin role to the seeded user
        $admin->assignRole('Admin');
    }
}
