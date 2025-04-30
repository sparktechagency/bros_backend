<?php
namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name'              => 'Admin Name',
            'email'             => 'admin@gmail.com',
            'role'              => 'ADMIN',
            'email_verified_at' => now(),
            'password'          => Hash::make('1234'),
        ]);
        User::create([
            'name'              => 'Demo User',
            'email'             => 'user@gmail.com',
            'role'              => 'USER',
            'car_brand'         => 'Honda',
            'car_model'         => 'Civic',
            'email_verified_at' => now(),
            'password'          => Hash::make('1234'),
        ]);
        User::create([
            'name'              => 'Demo User 1',
            'email'             => 'user1@gmail.com',
            'role'              => 'USER',
            'email_verified_at' => now(),
            'password'          => Hash::make('1234'),
        ]);
    }
}
