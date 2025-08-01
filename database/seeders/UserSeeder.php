<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Admin Users
        User::create([
            'name' => 'Administrator',
            'email' => 'admin@apotek.com',
            'password' => Hash::make('password'),
            'role' => User::ROLE_ADMIN,
            'phone' => '081234567890',
            'address' => 'Jl. Admin No. 1, Jakarta',
        ]);

        // Apoteker Users
        User::create([
            'name' => 'Dr. Budi Santoso',
            'email' => 'budi@apotek.com',
            'password' => Hash::make('password'),
            'role' => User::ROLE_APOTEKER,
            'phone' => '081234567891',
            'address' => 'Jl. Apoteker No. 1, Jakarta',
        ]);

        User::create([
            'name' => 'Dr. Sari Dewi',
            'email' => 'sari@apotek.com',
            'password' => Hash::make('password'),
            'role' => User::ROLE_APOTEKER,
            'phone' => '081234567892',
            'address' => 'Jl. Apoteker No. 2, Jakarta',
        ]);

        User::create([
            'name' => 'Dr. Ahmad Rizki',
            'email' => 'ahmad@apotek.com',
            'password' => Hash::make('password'),
            'role' => User::ROLE_APOTEKER,
            'phone' => '081234567893',
            'address' => 'Jl. Apoteker No. 3, Jakarta',
        ]);

        // Pelanggan Users
        User::create([
            'name' => 'John Doe',
            'email' => 'john@customer.com',
            'password' => Hash::make('password'),
            'role' => User::ROLE_PELANGGAN,
            'phone' => '081234567894',
            'address' => 'Jl. Pelanggan No. 1, Jakarta',
        ]);

        User::create([
            'name' => 'Jane Smith',
            'email' => 'jane@customer.com',
            'password' => Hash::make('password'),
            'role' => User::ROLE_PELANGGAN,
            'phone' => '081234567895',
            'address' => 'Jl. Pelanggan No. 2, Jakarta',
        ]);

        User::create([
            'name' => 'Michael Johnson',
            'email' => 'michael@customer.com',
            'password' => Hash::make('password'),
            'role' => User::ROLE_PELANGGAN,
            'phone' => '081234567896',
            'address' => 'Jl. Pelanggan No. 3, Jakarta',
        ]);

        User::create([
            'name' => 'Sarah Wilson',
            'email' => 'sarah@customer.com',
            'password' => Hash::make('password'),
            'role' => User::ROLE_PELANGGAN,
            'phone' => '081234567897',
            'address' => 'Jl. Pelanggan No. 4, Jakarta',
        ]);

        User::create([
            'name' => 'David Brown',
            'email' => 'david@customer.com',
            'password' => Hash::make('password'),
            'role' => User::ROLE_PELANGGAN,
            'phone' => '081234567898',
            'address' => 'Jl. Pelanggan No. 5, Jakarta',
        ]);

        User::create([
            'name' => 'Lisa Anderson',
            'email' => 'lisa@customer.com',
            'password' => Hash::make('password'),
            'role' => User::ROLE_PELANGGAN,
            'phone' => '081234567899',
            'address' => 'Jl. Pelanggan No. 6, Jakarta',
        ]);
    }
}
