<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Skip if categories already exist to avoid duplicate entries
        if (Category::count() > 0) {
            $this->command->info('Categories already exist, skipping CategorySeeder');
            return;
        }
        
        $categories = [
            [
                'name' => 'Obat Bebas',
                'description' => 'Obat yang dapat dibeli tanpa resep dokter',
            ],
            [
                'name' => 'Obat Bebas Terbatas',
                'description' => 'Obat yang dapat dibeli tanpa resep dengan batasan tertentu',
            ],
            [
                'name' => 'Obat Keras',
                'description' => 'Obat yang hanya dapat dibeli dengan resep dokter',
            ],
            [
                'name' => 'Vitamin & Suplemen',
                'description' => 'Vitamin, mineral, dan suplemen kesehatan',
            ],
            [
                'name' => 'Obat Herbal',
                'description' => 'Obat tradisional dan herbal',
            ],
            [
                'name' => 'Perawatan Luka',
                'description' => 'Produk untuk perawatan dan pengobatan luka',
            ],
            [
                'name' => 'Kesehatan Mata',
                'description' => 'Obat dan produk untuk kesehatan mata',
            ],
            [
                'name' => 'Kesehatan Mulut',
                'description' => 'Produk untuk kesehatan gigi dan mulut',
            ],
            [
                'name' => 'Antiseptik & Disinfektan',
                'description' => 'Produk pembersih dan disinfektan',
            ],
            [
                'name' => 'Alat Kesehatan',
                'description' => 'Peralatan medis dan kesehatan',
            ],
            [
                'name' => 'Obat Anak',
                'description' => 'Obat khusus untuk anak-anak',
            ],
            [
                'name' => 'Obat Dewasa',
                'description' => 'Obat untuk dewasa dan lansia',
            ],
            [
                'name' => 'Produk Ibu & Bayi',
                'description' => 'Produk kesehatan untuk ibu hamil dan bayi',
            ],
            [
                'name' => 'Kontrasepsi',
                'description' => 'Alat dan obat kontrasepsi',
            ],
            [
                'name' => 'Lainnya',
                'description' => 'Produk farmasi lainnya',
            ],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
