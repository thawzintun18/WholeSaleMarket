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
        // Super Admin
        User::create([
            'name'     => 'SuperAdministrator',
            'email'    => 'superadmin@gmail.com',
            'password' => Hash::make('superadmin@123'),
            'role'     => 'superadmin',
        ]);
        // Admin
        User::create([
            'name'     => 'Administrator',
            'email'    => 'admin@gmail.com',
            'password' => Hash::make('admin@123'),
            'role'     => 'admin',
        ]);
        // Staff
        User::create([
            'name'     => 'Staff User',
            'email'    => 'staff@gmail.com',
            'password' => Hash::make('staff@123'),
            'role'     => 'staff',
        ]);
    }
}
