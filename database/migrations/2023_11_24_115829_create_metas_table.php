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
        Schema::create('metas', function (Blueprint $table) {
            $table->id();

            $table->string('name');
            $table->string('slug');
            $table->string('field_type')->default(FieldType::TEXT);
            $table->string('value')->nullable();
            $table->boolean('is_required')->default(false);

            $table->foreignId('category_id')
                  ->constrained('categories')
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
            Schema::dropIfExists('metas');
        }
    }
};
