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
        Schema::create('sales_orders', function (Blueprint $table) {
            $table->id();
            $table->string('so_code', 20)->nullable()->unique();
            $table->datetime('sales_date');
            $table->foreignId('customer_id')
                ->constrained('customers', indexName: 'sales_orders_customer_id_foreign')
                ->cascadeOnUpdate()
                ->restrictOnDelete();
            $table->string('payment_type', 10);
            $table->integer('sub_total');
            $table->integer('discount');
            $table->integer('grand_total');
            $table->integer('paid_amount');
            $table->integer('return_amount');
            $table->string('remarks', 150)->nullable();
            $table->string('status', 20);
            $table->foreignId('created_by')
                ->nullable()
                ->constrained('users', indexName: 'sales_orders_created_by_foreign')
                ->cascadeOnUpdate() // when updated, all related rows also get updated
                ->restrictOnDelete(); // prevent delete if there are related rows
            $table->foreignId('updated_by')
                ->nullable()
                ->constrained('users', indexName: 'sales_orders_updated_by_foreign')
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
        Schema::dropIfExists('sales_orders');
    }
};
