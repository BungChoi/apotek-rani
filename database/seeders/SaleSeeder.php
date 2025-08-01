<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Sale;
use App\Models\SaleDetail;
use App\Models\User;
use App\Models\Product;
use Carbon\Carbon;

class SaleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $apoteker = User::where('role', 'apoteker')->first();
        $customer = User::where('role', 'pelanggan')->first();

        // Sale 1: Cash payment
        $sale1 = Sale::create([
            'sale_number' => 'SL-20250726-0001',
            'customer_id' => $customer->id,
            'served_by_user_id' => $apoteker->id,
            'total_amount' => 50000.00,
            'payment_method' => Sale::PAYMENT_CASH,
            'status' => Sale::STATUS_COMPLETED,
            'sale_date' => Carbon::now()->subHours(2),
            'notes' => 'Walk-in customer purchase',
        ]);

        SaleDetail::create([
            'sale_id' => $sale1->id,
            'product_id' => 1, // Paracetamol 500mg
            'quantity' => 10,
            'unit_price' => 3500.00,
            'total_price' => 35000.00,
        ]);

        SaleDetail::create([
            'sale_id' => $sale1->id,
            'product_id' => 13, // Vitamin C 1000mg
            'quantity' => 1,
            'unit_price' => 15000.00,
            'total_price' => 15000.00,
        ]);

        // Sale 2: Transfer payment
        $sale2 = Sale::create([
            'sale_number' => 'SL-20250726-0002',
            'customer_id' => null, // Walk-in customer
            'served_by_user_id' => $apoteker->id,
            'total_amount' => 125000.00,
            'payment_method' => Sale::PAYMENT_TRANSFER,
            'status' => Sale::STATUS_COMPLETED,
            'sale_date' => Carbon::now()->subHours(5),
            'notes' => 'Online order processed',
        ]);

        SaleDetail::create([
            'sale_id' => $sale2->id,
            'product_id' => 7, // Amoxicillin 500mg
            'quantity' => 5,
            'unit_price' => 25000.00,
            'total_price' => 125000.00,
        ]);

        // Sale 3: E-Wallet payment
        $sale3 = Sale::create([
            'sale_number' => 'SL-20250726-0003',
            'customer_id' => $customer->id,
            'served_by_user_id' => $apoteker->id,
            'total_amount' => 73000.00,
            'payment_method' => Sale::PAYMENT_E_WALLET,
            'status' => Sale::STATUS_COMPLETED,
            'sale_date' => Carbon::now()->subDays(1),
            'notes' => 'Customer regular medication',
        ]);

        SaleDetail::create([
            'sale_id' => $sale3->id,
            'product_id' => 3, // Ibuprofen 400mg
            'quantity' => 5,
            'unit_price' => 8500.00,
            'total_price' => 42500.00,
        ]);

        SaleDetail::create([
            'sale_id' => $sale3->id,
            'product_id' => 10, // Antasida DOEN
            'quantity' => 7,
            'unit_price' => 4500.00,
            'total_price' => 31500.00,
        ]);

        echo "✅ Sale seeder completed successfully!" . PHP_EOL;
        echo "💰 Created 3 sales with different payment methods:" . PHP_EOL;
        echo "   - 1 Cash payment (Rp 50,000)" . PHP_EOL;
        echo "   - 1 Transfer payment (Rp 125,000)" . PHP_EOL;
        echo "   - 1 E-Wallet payment (Rp 73,000)" . PHP_EOL;
        echo "   Total Sales: Rp " . number_format(248000, 0, ',', '.') . PHP_EOL;
    }
}
