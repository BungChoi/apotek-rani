<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Purchase;
use App\Models\PurchaseDetail;
use App\Models\Product;
use App\Models\Supplier;
use App\Models\User;
use Carbon\Carbon;

class PurchaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get admin user for creating purchases
        $admin = User::where('role', 'admin')->first();
        $apoteker = User::where('role', 'apoteker')->first();

        // Purchase 1: From Kimia Farma (completed)
        $purchase1 = Purchase::create([
            'purchase_number' => 'PO-20250726-0001',
            'supplier_id' => 1, // Kimia Farma
            'created_by_user_id' => $admin->id,
            'purchase_date' => Carbon::now()->subDays(15),
            'total_amount' => 500000.00,
            'payment_method' => Purchase::PAYMENT_TRANSFER,
            'status' => Purchase::STATUS_COMPLETED,
            'notes' => 'Purchase order for pain medications and fever reducers',
        ]);

        // Purchase 1 Details
        PurchaseDetail::create([
            'purchase_id' => $purchase1->id,
            'product_id' => 1, // Paracetamol 500mg
            'quantity_ordered' => 100,
            'quantity_received' => 100,
            'unit_cost' => 2000.00,
            'total_cost' => 200000.00,
            'product_name_snapshot' => 'Paracetamol 500mg',
            'expiry_date' => Carbon::now()->addMonths(18),
            'batch_number' => 'PCM001',
            'received_date' => Carbon::now()->subDays(8),
        ]);

        PurchaseDetail::create([
            'purchase_id' => $purchase1->id,
            'product_id' => 7, // Amoxicillin 500mg
            'quantity_ordered' => 60,
            'quantity_received' => 60,
            'unit_cost' => 5000.00,
            'total_cost' => 300000.00,
            'product_name_snapshot' => 'Amoxicillin 500mg',
            'expiry_date' => Carbon::now()->addMonths(22),
            'batch_number' => 'AMX001',
            'received_date' => Carbon::now()->subDays(8),
        ]);

        // Purchase 2: From Kalbe Farma (received)
        $purchase2 = Purchase::create([
            'purchase_number' => 'PO-20250726-0002',
            'supplier_id' => 2, // Kalbe Farma
            'created_by_user_id' => $apoteker->id,
            'purchase_date' => Carbon::now()->subDays(5),
            'total_amount' => 800000.00,
            'payment_method' => Purchase::PAYMENT_CREDIT,
            'status' => Purchase::STATUS_RECEIVED,
            'notes' => 'Large order for vitamins and cardiovascular medications',
        ]);

        // Purchase 2 Details
        PurchaseDetail::create([
            'purchase_id' => $purchase2->id,
            'product_id' => 13, // Vitamin C 1000mg
            'quantity_ordered' => 200,
            'quantity_received' => 200,
            'unit_cost' => 1000.00,
            'total_cost' => 200000.00,
            'product_name_snapshot' => 'Vitamin C 1000mg',
            'expiry_date' => Carbon::now()->addMonths(30),
            'batch_number' => 'VTC001',
            'received_date' => Carbon::now()->subDays(2),
        ]);

        PurchaseDetail::create([
            'purchase_id' => $purchase2->id,
            'product_id' => 16, // Amlodipine 10mg
            'quantity_ordered' => 120,
            'quantity_received' => 120,
            'unit_cost' => 5000.00,
            'total_cost' => 600000.00,
            'product_name_snapshot' => 'Amlodipine 10mg',
            'expiry_date' => Carbon::now()->addMonths(24),
            'batch_number' => 'AML001',
            'received_date' => Carbon::now()->subDays(2),
        ]);

        // Purchase 3: From Dexa Medica (ordered)
        $purchase3 = Purchase::create([
            'purchase_number' => 'PO-20250726-0003',
            'supplier_id' => 3, // Dexa Medica
            'created_by_user_id' => $admin->id,
            'purchase_date' => Carbon::now()->subDays(3),
            'total_amount' => 450000.00,
            'payment_method' => Purchase::PAYMENT_TRANSFER,
            'status' => Purchase::STATUS_ORDERED,
            'notes' => 'Order for anti-inflammatory and diabetes medications',
        ]);

        // Purchase 3 Details (partially received)
        PurchaseDetail::create([
            'purchase_id' => $purchase3->id,
            'product_id' => 3, // Ibuprofen 400mg
            'quantity_ordered' => 80,
            'quantity_received' => 50,
            'unit_cost' => 3000.00,
            'total_cost' => 240000.00,
            'product_name_snapshot' => 'Ibuprofen 400mg',
            'expiry_date' => Carbon::now()->addMonths(20),
            'batch_number' => 'IBU001',
            'received_date' => Carbon::now()->subDays(1),
        ]);

        PurchaseDetail::create([
            'purchase_id' => $purchase3->id,
            'product_id' => 18, // Metformin 500mg
            'quantity_ordered' => 70,
            'quantity_received' => 0, // Not yet received
            'unit_cost' => 3000.00,
            'total_cost' => 210000.00,
            'product_name_snapshot' => 'Metformin 500mg',
        ]);

        // Purchase 4: From Sanbe Farma (draft)
        $purchase4 = Purchase::create([
            'purchase_number' => 'PO-20250726-0004',
            'supplier_id' => 4, // Sanbe Farma
            'created_by_user_id' => $apoteker->id,
            'purchase_date' => Carbon::now(),
            'total_amount' => 350000.00,
            'payment_method' => Purchase::PAYMENT_TRANSFER,
            'status' => Purchase::STATUS_DRAFT,
            'notes' => 'Draft order for cough medicines and digestive medications',
        ]);

        // Purchase 4 Details
        PurchaseDetail::create([
            'purchase_id' => $purchase4->id,
            'product_id' => 4, // OBH Combi Plus
            'quantity_ordered' => 50,
            'quantity_received' => 0,
            'unit_cost' => 4000.00,
            'total_cost' => 200000.00,
            'product_name_snapshot' => 'OBH Combi Plus',
        ]);

        PurchaseDetail::create([
            'purchase_id' => $purchase4->id,
            'product_id' => 12, // Loperamide 2mg
            'quantity_ordered' => 50,
            'quantity_received' => 0,
            'unit_cost' => 3000.00,
            'total_cost' => 150000.00,
            'product_name_snapshot' => 'Loperamide 2mg',
        ]);

        // Purchase 5: From Indofarma (cancelled)
        $purchase5 = Purchase::create([
            'purchase_number' => 'PO-20250726-0005',
            'supplier_id' => 5, // Indofarma
            'created_by_user_id' => $admin->id,
            'purchase_date' => Carbon::now()->subDays(45),
            'total_amount' => 300000.00,
            'payment_method' => Purchase::PAYMENT_CREDIT,
            'status' => Purchase::STATUS_CANCELLED,
            'notes' => 'Order for antacid medications - CANCELLED',
        ]);

        // Purchase 5 Details
        PurchaseDetail::create([
            'purchase_id' => $purchase5->id,
            'product_id' => 10, // Antasida DOEN
            'quantity_ordered' => 150,
            'quantity_received' => 150,
            'unit_cost' => 2000.00,
            'total_cost' => 300000.00,
            'product_name_snapshot' => 'Antasida DOEN',
            'expiry_date' => Carbon::now()->addMonths(24),
            'batch_number' => 'ANT001',
            'received_date' => Carbon::now()->subDays(38),
        ]);

        echo "✅ Purchase seeder completed successfully!" . PHP_EOL;
        echo "📦 Created 5 purchases with different statuses:" . PHP_EOL;
        echo "   - 1 Completed (Transfer)" . PHP_EOL;
        echo "   - 1 Received (Credit)" . PHP_EOL;
        echo "   - 1 Ordered (Transfer)" . PHP_EOL;
        echo "   - 1 Draft (Transfer)" . PHP_EOL;
        echo "   - 1 Cancelled (Credit)" . PHP_EOL;
    }
}
