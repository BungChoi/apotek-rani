<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product;
use Carbon\Carbon;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Obat Analgesik & Antipiretik
        Product::create([
            'name' => 'Paracetamol 500mg',
            'supplier_id' => 1, // Kimia Farma
            'category_id' => 1, // Obat Bebas
            'stock' => 150,
            'price' => 3500.00,
            'expired_date' => Carbon::now()->addMonths(18),
            'description' => 'Obat pereda nyeri dan penurun demam',
            'image' => 'products/obat1.png',
            'total_sold' => 45,
            'status' => 'tersedia',
        ]);

        Product::create([
            'name' => 'Aspirin 80mg',
            'supplier_id' => 2, // Kalbe Farma
            'category_id' => 3, // Obat Keras
            'stock' => 100,
            'price' => 5000.00,
            'expired_date' => Carbon::now()->addMonths(24),
            'description' => 'Obat pengencer darah untuk pencegahan stroke',
            'image' => 'products/obat2.png',
            'total_sold' => 23,
            'status' => 'tersedia',
        ]);

        Product::create([
            'name' => 'Ibuprofen 400mg',
            'supplier_id' => 3, // Dexa Medica
            'category_id' => 2, // Obat Bebas Terbatas
            'stock' => 80,
            'price' => 8500.00,
            'expired_date' => Carbon::now()->addMonths(20),
            'description' => 'Obat anti-inflamasi non-steroid untuk nyeri dan peradangan',
            'image' => 'products/obat3.png',
            'total_sold' => 31,
            'status' => 'tersedia',
        ]);

        // Obat Batuk & Flu
        Product::create([
            'name' => 'OBH Combi Plus',
            'supplier_id' => 4, // Sanbe Farma
            'category_id' => 1, // Obat Bebas
            'stock' => 60,
            'price' => 15000.00,
            'expired_date' => Carbon::now()->addMonths(15),
            'description' => 'Sirup obat batuk dengan ekspektoran',
            'image' => 'products/obat4.png',
            'total_sold' => 67,
            'status' => 'tersedia',
        ]);

        Product::create([
            'name' => 'Bodrex Flu & Batuk',
            'supplier_id' => 7, // Tempo Scan Pacific
            'category_id' => 1, // Obat Bebas
            'stock' => 0,
            'price' => 12000.00,
            'expired_date' => Carbon::now()->addMonths(12),
            'description' => 'Obat untuk meredakan gejala flu dan batuk',
            'image' => 'products/obat5.png',
            'total_sold' => 89,
            'status' => 'habis',
        ]);

        Product::create([
            'name' => 'Actifed Plus',
            'supplier_id' => 6, // Pharos Indonesia
            'category_id' => 2, // Obat Bebas Terbatas
            'stock' => 45,
            'price' => 18000.00,
            'expired_date' => Carbon::now()->addMonths(16),
            'description' => 'Obat flu dengan dekongestan dan antihistamin',
            'image' => 'products/obat6.png',
            'total_sold' => 34,
            'status' => 'tersedia',
        ]);

        // Antibiotik
        Product::create([
            'name' => 'Amoxicillin 500mg',
            'supplier_id' => 1, // Kimia Farma
            'category_id' => 3, // Obat Keras
            'stock' => 120,
            'price' => 25000.00,
            'expired_date' => Carbon::now()->addMonths(22),
            'description' => 'Antibiotik untuk infeksi bakteri',
            'image' => 'products/obat7.png',
            'total_sold' => 78,
            'status' => 'tersedia',
        ]);

        Product::create([
            'name' => 'Cefixime 200mg',
            'supplier_id' => 3, // Dexa Medica
            'category_id' => 3, // Obat Keras
            'stock' => 75,
            'price' => 45000.00,
            'expired_date' => Carbon::now()->addMonths(18),
            'description' => 'Antibiotik sefalosporin generasi ketiga',
            'image' => 'products/obat8.png',
            'total_sold' => 42,
            'status' => 'tersedia',
        ]);

        Product::create([
            'name' => 'Ciprofloxacin 500mg',
            'supplier_id' => 2, // Kalbe Farma
            'category_id' => 3, // Obat Keras
            'stock' => 90,
            'price' => 35000.00,
            'expired_date' => Carbon::now()->addMonths(20),
            'description' => 'Antibiotik quinolon untuk infeksi berat',
            'image' => 'products/obat1.png',
            'total_sold' => 29,
            'status' => 'tersedia',
        ]);

        // Obat Maag & Pencernaan
        Product::create([
            'name' => 'Antasida DOEN',
            'supplier_id' => 5, // Indofarma
            'category_id' => 1, // Obat Bebas
            'stock' => 200,
            'price' => 4500.00,
            'expired_date' => Carbon::now()->addMonths(24),
            'description' => 'Obat untuk mengatasi kelebihan asam lambung',
            'image' => 'products/obat2.png',
            'total_sold' => 156,
            'status' => 'tersedia',
        ]);

        Product::create([
            'name' => 'Omeprazole 20mg',
            'supplier_id' => 2, // Kalbe Farma
            'category_id' => 3, // Obat Keras
            'stock' => 110,
            'price' => 28000.00,
            'expired_date' => Carbon::now()->addMonths(21),
            'description' => 'Obat untuk mengurangi produksi asam lambung',
            'image' => 'products/obat3.png',
            'total_sold' => 73,
            'status' => 'tersedia',
        ]);

        Product::create([
            'name' => 'Loperamide 2mg',
            'supplier_id' => 4, // Sanbe Farma
            'category_id' => 2, // Obat Bebas Terbatas
            'stock' => 65,
            'price' => 12000.00,
            'expired_date' => Carbon::now()->addMonths(19),
            'description' => 'Obat untuk mengatasi diare',
            'image' => 'products/obat4.png',
            'total_sold' => 38,
            'status' => 'tersedia',
        ]);

        // Vitamin & Suplemen
        Product::create([
            'name' => 'Vitamin C 1000mg',
            'supplier_id' => 7, // Tempo Scan Pacific
            'category_id' => 4, // Vitamin & Suplemen
            'stock' => 180,
            'price' => 15000.00,
            'expired_date' => Carbon::now()->addMonths(30),
            'description' => 'Suplemen vitamin C untuk daya tahan tubuh',
            'image' => 'products/obat5.png',
            'total_sold' => 234,
            'status' => 'tersedia',
        ]);

        Product::create([
            'name' => 'Multivitamin Centrum',
            'supplier_id' => 8, // Hexpharm Jaya
            'category_id' => 4, // Vitamin & Suplemen
            'stock' => 95,
            'price' => 85000.00,
            'expired_date' => Carbon::now()->addMonths(36),
            'description' => 'Multivitamin lengkap untuk kesehatan harian',
            'image' => 'products/obat6.png',
            'total_sold' => 67,
            'status' => 'tersedia',
        ]);

        Product::create([
            'name' => 'Vitamin B Complex',
            'supplier_id' => 9, // Prafa
            'category_id' => 4, // Vitamin & Suplemen
            'stock' => 125,
            'price' => 22000.00,
            'expired_date' => Carbon::now()->addMonths(28),
            'description' => 'Suplemen vitamin B kompleks',
            'image' => 'products/obat7.png',
            'total_sold' => 89,
            'status' => 'tersedia',
        ]);

        // Obat Hipertensi
        Product::create([
            'name' => 'Amlodipine 10mg',
            'supplier_id' => 2, // Kalbe Farma
            'category_id' => 3, // Obat Keras
            'stock' => 85,
            'price' => 32000.00,
            'expired_date' => Carbon::now()->addMonths(24),
            'description' => 'Obat antihipertensi calcium channel blocker',
            'image' => 'products/obat8.png',
            'total_sold' => 156,
            'status' => 'tersedia',
        ]);

        Product::create([
            'name' => 'Captopril 25mg',
            'supplier_id' => 1, // Kimia Farma
            'category_id' => 3, // Obat Keras
            'stock' => 70,
            'price' => 18000.00,
            'expired_date' => Carbon::now()->addMonths(20),
            'description' => 'Obat ACE inhibitor untuk hipertensi',
            'image' => 'products/obat1.png',
            'total_sold' => 124,
            'status' => 'tersedia',
        ]);

        // Obat Diabetes
        Product::create([
            'name' => 'Metformin 500mg',
            'supplier_id' => 3, // Dexa Medica
            'category_id' => 3, // Obat Keras
            'stock' => 140,
            'price' => 24000.00,
            'expired_date' => Carbon::now()->addMonths(22),
            'description' => 'Obat diabetes tipe 2',
            'image' => 'products/obat2.png',
            'total_sold' => 189,
            'status' => 'tersedia',
        ]);

        Product::create([
            'name' => 'Glimepiride 2mg',
            'supplier_id' => 5, // Indofarma
            'category_id' => 3, // Obat Keras
            'stock' => 95,
            'price' => 38000.00,
            'expired_date' => Carbon::now()->addMonths(18),
            'description' => 'Obat diabetes golongan sulfonilurea',
            'image' => 'products/obat3.png',
            'total_sold' => 98,
            'status' => 'tersedia',
        ]);

        // Obat Kadaluarsa (untuk testing)
        Product::create([
            'name' => 'Expired Medicine Test',
            'supplier_id' => 10, // Soho
            'category_id' => 15, // Lainnya
            'stock' => 25,
            'price' => 10000.00,
            'expired_date' => Carbon::now()->subMonths(2), // Sudah kadaluarsa 2 bulan
            'description' => 'Obat untuk testing status kadaluarsa',
            'image' => 'products/obat4.png',
            'total_sold' => 5,
            'status' => 'kadaluarsa',
        ]);

        // Obat Hampir Kadaluarsa (untuk testing)
        Product::create([
            'name' => 'Soon Expired Medicine Test',
            'supplier_id' => 6, // Pharos
            'category_id' => 15, // Lainnya
            'stock' => 15,
            'price' => 8000.00,
            'expired_date' => Carbon::now()->addDays(15), // Akan kadaluarsa dalam 15 hari
            'description' => 'Obat untuk testing status hampir kadaluarsa',
            'image' => 'products/obat5.png',
            'total_sold' => 12,
            'status' => 'tersedia',
        ]);
    }
}
