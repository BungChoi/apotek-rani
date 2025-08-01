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
        Schema::create('sales', function (Blueprint $table) {
            $table->id();
            $table->string('sale_number', 50)->unique(); // Sales number
            $table->foreignId('customer_id')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('served_by_user_id')->constrained('users')->onDelete('restrict');
            $table->decimal('total_amount', 12, 2);
            $table->enum('payment_method', ['cash', 'transfer', 'credit_card', 'debit_card', 'e_wallet', 'qris'])->default('cash');
            $table->enum('status', ['completed', 'refunded'])->default('completed');
            $table->timestamp('sale_date');
            $table->text('notes')->nullable();
            $table->timestamps();

            // Indexes
            $table->index(['status']);
            $table->index(['sale_date']);
            $table->index(['payment_method']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales');
    }
};
