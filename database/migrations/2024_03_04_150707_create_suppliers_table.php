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
        Schema::create('suppliers', function (Blueprint $table) {
            $table->id();
            $table->string('code', 20)->nullable()->unique();
            $table->string('name', 100);
            $table->string('pic', 50);
            $table->string('address', 150)->nullable();
            $table->string('phone_no', 20);
            $table->string('remarks', 150)->nullable();
            $table->foreignId('created_by')
                ->nullable()
                ->constrained('users', indexName: 'suppliers_created_by')
                ->cascadeOnUpdate() // when updated, all related rows also get updated
                ->restrictOnDelete(); // prevent delete if there are related rows
            $table->foreignId('updated_by')
                ->nullable()
                ->constrained('users', indexName: 'suppliers_updated_by')
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
        Schema::dropIfExists('suppliers');
    }
};
