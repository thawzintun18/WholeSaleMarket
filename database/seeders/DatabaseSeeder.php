<?php
namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        // Admin User
        User::create([
            'name'     => 'Administrator',
            'email'    => 'admin@example.com',
            'password' => Hash::make('password'),
            'role'     => 'admin',
        ]);

        // Staff User
        User::create([
            'name'     => 'Staff User',
            'email'    => 'staff@example.com',
            'password' => Hash::make('password'),
            'role'     => 'staff',
        ]);
    }
}
