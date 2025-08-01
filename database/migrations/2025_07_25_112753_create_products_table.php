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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name', 255);
            $table->foreignId('supplier_id')->constrained('suppliers')->onDelete('restrict');
            $table->foreignId('category_id')->default(15)->constrained('categories')->onDelete('restrict');
            $table->integer('stock')->default(0);
            $table->decimal('price', 10, 2);
            $table->date('expired_date');
            $table->text('description')->nullable();
            $table->string('image', 255)->nullable();
            $table->integer('total_sold')->default(0);
            $table->enum('status', ['tersedia', 'habis', 'kadaluarsa'])->default('tersedia');
            $table->timestamps();

            // Indexes
            $table->index(['status']);
            $table->index(['expired_date']);
            $table->index(['name']);
            $table->index(['category_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
