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
        Schema::create('purchases', function (Blueprint $table) {
            $table->id();
            $table->string('purchase_number', 50)->unique(); // PO number
            $table->foreignId('supplier_id')->constrained('suppliers')->onDelete('restrict');
            $table->foreignId('created_by_user_id')->constrained('users')->onDelete('restrict'); // Admin/Apoteker who created
            
            // Purchase details
            $table->date('purchase_date');
            $table->decimal('total_amount', 12, 2);
            $table->enum('payment_method', ['cash', 'transfer', 'credit', 'check'])->default('transfer');
            $table->enum('status', ['draft', 'ordered', 'received', 'completed', 'cancelled'])->default('completed');
            $table->text('notes')->nullable();
            
            $table->timestamps();

            // Indexes
            $table->index(['purchase_date']);
            $table->index(['status']);
            $table->index(['payment_method']);
            $table->index(['supplier_id']);
            $table->index(['purchase_number']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchases');
    }
};
