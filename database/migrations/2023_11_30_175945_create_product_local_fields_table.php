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
        Schema::create('product_local_fields', function (Blueprint $table) {
            $table->id();

            $table->foreignId('product_id')
                  ->constrained('products')
                  ->cascadeOnDelete();

            $table->foreignId('local_field_id')
                  ->constrained('local_fields');

            $table->string('value')
                  ->nullable();

            $table->json('custom_props')
                  ->nullable()
                  ->default('{}');

            $table->timestamps();

            $table->unique(['product_id', 'local_field_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if ( ! app()->isProduction()) {
            Schema::dropIfExists('product_local_fields');
        }
    }
};
