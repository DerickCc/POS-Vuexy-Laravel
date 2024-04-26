<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->string('code', 20)->nullable()->unique();
            $table->string('photo', 100)->nullable();
            $table->decimal('stock', $precision = 10, $scale = 2)->default(0.0);
            $table->decimal('restock_threshold', $precision = 10, $scale = 2)->default(0.0);
            $table->string('uom', 50);
            $table->integer('purchase_price');
            $table->integer('selling_price');
            $table->string('remarks', 150)->nullable();
            $table->foreignId('created_by')
                ->nullable()
                ->constrained('users', indexName: 'products_created_by_foreign')
                ->cascadeOnUpdate() // when updated, all related rows also get updated
                ->restrictOnDelete(); // prevent delete if there are related rows
            $table->foreignId('updated_by')
                ->nullable()
                ->constrained('users', indexName: 'products_updated_by_foreign')
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
        Schema::dropIfExists('products');

        // delete product-photo folder
        if (Storage::disk('public')->exists('product-photo')) {
            Storage::disk('public')->deleteDirectory('product-photo');
        }

    }
};
