<?php

use App\Models\Field;
use App\Models\Product;
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
        Schema::create('values', function (Blueprint $table) {
            $table->id();

            $table->foreignIdFor(Product::class)
                  ->nullable()
                  ->constrained()
                  ->cascadeOnDelete();

            $table->foreignIdFor(Field::class)
                  ->nullable()
                  ->constrained()
                  ->cascadeOnDelete();

            $table->text('value_data');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if ( ! app()->isProduction()) {
            Schema::dropIfExists('values');
        }
    }
};
