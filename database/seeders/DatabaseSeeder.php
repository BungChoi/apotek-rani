<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Run seeders in correct order based on foreign key dependencies
        $this->call([
            UserSeeder::class,        // First - no dependencies
            SupplierSeeder::class,    // Second - no dependencies
            CategorySeeder::class,    // Third - no dependencies  
            ProductSeeder::class,     // Fourth - depends on Suppliers & Categories
            PurchaseSeeder::class,    // Fifth - depends on Users, Suppliers, Products
            SaleSeeder::class,        // Sixth - depends on Users, Products
        ]);

        $this->command->info('🎉 All seeders completed successfully!');
        $this->command->info('📊 Database seeded with:');
        $this->command->info('   👥 Users: 10 (1 Admin, 3 Apoteker, 6 Pelanggan)');
        $this->command->info('   🏢 Suppliers: 10');
        $this->command->info('   📂 Categories: 15 (Various medicine categories)');
        $this->command->info('   💊 Products: 21 (Various medicines)');
        $this->command->info('   📦 Purchases: 5 (Various purchase statuses)');
        $this->command->info('   💰 Sales: 3 (Various payment methods)');
        $this->command->info('');
        $this->command->info('🔑 Login Credentials:');
        $this->command->info('   Admin: admin@apotek.com / password');
        $this->command->info('   Apoteker: budi@apotek.com / password');
        $this->command->info('   Customer: john@customer.com / password');
    }
}
