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
        Schema::create('integration_fields', function (Blueprint $table) {
            $table->id();

            $table->foreignId('integration_id')
                  ->constrained('integrations');

            $table->string('field_id', 20)->nullable();
            $table->string('name', 50);
            $table->string('api_name', 50);
            $table->string('data_type', 25);
            $table->integer('order')->default(0);

            $table->boolean('is_active')->default(true);
            $table->boolean('is_custom')->default(false);
            $table->boolean('is_required')->default(false);
            $table->boolean('is_primary')->default(false);
            $table->boolean('is_permanent')->default(false);

            $table->boolean('filterable')->default(false);
            $table->boolean('searchable')->default(false);
            $table->boolean('sortable')->default(false);

            $table->json('values')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if ( ! app()->isProduction()) {
            Schema::dropIfExists('integration_fields');
        }
    }
};
