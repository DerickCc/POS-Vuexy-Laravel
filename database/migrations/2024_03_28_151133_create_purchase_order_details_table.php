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
        Schema::create('purchase_order_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('po_id')
                ->constrained('purchase_orders', indexName: 'purchase_order_details_po_id_foreign')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->foreignId('product_id')
                ->constrained('products', indexName: 'purchase_order_details_product_id_foreign')
                ->cascadeOnUpdate()
                ->restrictOnDelete();
            $table->integer('purchase_price');
            $table->decimal('quantity', $precision = 10, $scale = 2);
            $table->integer('total_price');
            $table->foreignId('created_by')
                ->nullable()
                ->constrained('users', indexName: 'purchase_order_details_created_by_foreign')
                ->cascadeOnUpdate() // when updated, all related rows also get updated
                ->restrictOnDelete(); // prevent delete if there are related rows
            $table->foreignId('updated_by')
                ->nullable()
                ->constrained('users', indexName: 'purchase_order_details_updated_by_foreign')
                ->cascadeOnUpdate()
                ->restrictOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchase_order_details');
    }
};
