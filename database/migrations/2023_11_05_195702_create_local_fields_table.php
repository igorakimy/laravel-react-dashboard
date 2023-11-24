<?php

use App\Enums\FieldType;
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
        Schema::create('local_fields', function (Blueprint $table) {
            $table->id();
            $table->string('name', 60);
            $table->string('slug', 60);

            $table->string('field_type')
                  ->default(FieldType::TEXT);

            $table->integer('order')->nullable();

            $table->string('default_value')->nullable();

            $table->json('validations')->nullable();

            $table->json('properties')->nullable();

            $table->boolean('permanent')->default(true);

            $table->unique(['name', 'slug']);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if ( ! app()->isProduction()) {
            Schema::dropIfExists('local_fields');
        }
    }
};
