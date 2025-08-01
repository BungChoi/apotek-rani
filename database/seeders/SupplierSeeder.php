<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Supplier;

class SupplierSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Supplier::create([
            'name' => 'PT. Kimia Farma Tbk',
            'contact_person' => 'Andi Prasetyo',
            'phone' => '021-5551234',
            'address' => 'Jl. Veteran No. 9, Jakarta Pusat, DKI Jakarta 10110',
            'email' => 'supplier@kimiafarma.co.id',
        ]);

        Supplier::create([
            'name' => 'PT. Kalbe Farma Tbk',
            'contact_person' => 'Siti Nurhaliza',
            'phone' => '021-5552345',
            'address' => 'Jl. Letjen Suprapto Kav. 4, Jakarta Pusat, DKI Jakarta 10510',
            'email' => 'supplier@kalbe.co.id',
        ]);

        Supplier::create([
            'name' => 'PT. Dexa Medica',
            'contact_person' => 'Budi Santoso',
            'phone' => '021-5553456',
            'address' => 'Jl. Boulevard Raya KM 1.5, Cikarang, Bekasi, Jawa Barat 17530',
            'email' => 'supplier@dexa-medica.com',
        ]);

        Supplier::create([
            'name' => 'PT. Sanbe Farma',
            'contact_person' => 'Rini Wijayanti',
            'phone' => '022-5554567',
            'address' => 'Jl. Garuda No. 52, Bandung, Jawa Barat 40183',
            'email' => 'supplier@sanbe.co.id',
        ]);

        Supplier::create([
            'name' => 'PT. Indofarma Tbk',
            'contact_person' => 'Ahmad Fauzi',
            'phone' => '021-5555678',
            'address' => 'Jl. Tanah Abang II No. 1, Jakarta Pusat, DKI Jakarta 10160',
            'email' => 'supplier@indofarma.id',
        ]);

        Supplier::create([
            'name' => 'PT. Pharos Indonesia',
            'contact_person' => 'Dewi Sartika',
            'phone' => '021-5556789',
            'address' => 'Jl. Raya Bekasi KM 25, Bekasi, Jawa Barat 17530',
            'email' => 'supplier@pharos.co.id',
        ]);

        Supplier::create([
            'name' => 'PT. Tempo Scan Pacific',
            'contact_person' => 'Hendra Kusuma',
            'phone' => '021-5557890',
            'address' => 'Jl. Kapten Tendean No. 15, Jakarta Selatan, DKI Jakarta 12560',
            'email' => 'supplier@tempo.co.id',
        ]);

        Supplier::create([
            'name' => 'PT. Hexpharm Jaya',
            'contact_person' => 'Maya Sari',
            'phone' => '021-5558901',
            'address' => 'Jl. Raya Serpong KM 8, Tangerang, Banten 15310',
            'email' => 'supplier@hexpharm.co.id',
        ]);

        Supplier::create([
            'name' => 'PT. Prafa',
            'contact_person' => 'Tony Wijaya',
            'phone' => '021-5559012',
            'address' => 'Jl. Ahmad Yani No. 1, Bekasi, Jawa Barat 17141',
            'email' => 'supplier@prafa.co.id',
        ]);

        Supplier::create([
            'name' => 'PT. Soho Industri Pharmasi',
            'contact_person' => 'Linda Permata',
            'phone' => '021-5550123',
            'address' => 'Jl. Tomang Raya No. 21, Jakarta Barat, DKI Jakarta 11440',
            'email' => 'supplier@soho.co.id',
        ]);
    }
}
