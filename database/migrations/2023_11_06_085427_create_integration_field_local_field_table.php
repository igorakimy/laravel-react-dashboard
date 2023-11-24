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
        Schema::create('integration_field_local_field', function (Blueprint $table) {
            $table->id();

            $table->foreignId('integration_field_id')
                  ->constrained('integration_fields')
                  ->cascadeOnDelete();

            $table->foreignId('local_field_id')
                  ->constrained('local_fields')
                  ->cascadeOnDelete();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if ( ! app()->isProduction()) {
            Schema::dropIfExists('integration_field_local_field');
        }
    }
};
