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
        Schema::create('product_stock_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')
                ->constrained('products', indexName: 'product_stock_details_product_id_foreign')
                ->cascadeOnUpdate()
                ->restrictOnDelete();
            $table->integer('purchase_price');
            $table->decimal('quantity', $precision = 10, $scale = 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_stock_details');
    }
};
