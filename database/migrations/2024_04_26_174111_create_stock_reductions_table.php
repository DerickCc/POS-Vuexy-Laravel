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
        Schema::create('stock_reductions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_stock_detail_id')
                ->constrained('product_stock_details', indexName: 'stock_reductions_product_stock_detail_id_foreign')
                ->cascadeOnUpdate()
                ->restrictOnDelete();
            $table->foreignId('so_product_detail_id')
                ->constrained('sales_order_product_details', indexName: 'stock_reductions_so_product_detail_id_foreign')
                ->cascadeOnUpdate()
                ->restrictOnDelete();
            $table->decimal('quantity', $precision = 10, $scale = 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_reductions');
    }
};
