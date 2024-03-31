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
        Schema::create('purchase_orders', function (Blueprint $table) {
            $table->id();
            $table->string('po_code', 20)->nullable()->unique();
            $table->datetime('purchase_date');
            $table->foreignId('supplier_id')
                ->constrained('suppliers', indexName: 'purchase_orders_supplier_id_foreign')
                ->cascadeOnUpdate()
                ->restrictOnDelete();
            $table->integer('total_item');
            $table->integer('total_price');
            $table->string('remarks', 150)->nullable();
            $table->string('status', 20);
            $table->foreignId('created_by')
                ->nullable()
                ->constrained('users', indexName: 'purchase_orders_created_by_foreign')
                ->cascadeOnUpdate() // when updated, all related rows also get updated
                ->restrictOnDelete(); // prevent delete if there are related rows
            $table->foreignId('updated_by')
                ->nullable()
                ->constrained('users', indexName: 'purchase_orders_updated_by_foreign')
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
        Schema::dropIfExists('purchase_orders');
    }
};
