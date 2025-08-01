<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('purchase_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('purchase_id')->constrained('purchases')->onDelete('cascade');
            $table->foreignId('product_id')->constrained('products')->onDelete('restrict');
            
            // Quantity and pricing
            $table->integer('quantity_ordered');
            $table->integer('quantity_received')->default(0);
            $table->decimal('unit_cost', 10, 2); // Cost price from supplier
            $table->decimal('total_cost', 12, 2); // quantity_ordered * unit_cost
            
            // Product details at time of purchase (for historical record)
            $table->string('product_name_snapshot', 255); // Product name at time of purchase
            $table->date('expiry_date')->nullable(); // Expiry date of received batch
            $table->string('batch_number', 50)->nullable(); // Batch number from supplier
            
            // Receiving information
            $table->date('received_date')->nullable();
            $table->text('notes')->nullable();
            
            $table->timestamps();

            // Indexes
            $table->index(['purchase_id']);
            $table->index(['product_id']);
            $table->index(['received_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchase_details');
    }
};
