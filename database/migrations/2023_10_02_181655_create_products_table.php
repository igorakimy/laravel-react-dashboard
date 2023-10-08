<?php

use App\Models\Color;
use App\Models\Material;
use App\Models\Type;
use App\Models\Vendor;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name', 255);
            $table->string('sku')->unique();

            $table->unsignedInteger('quantity')->default(0);

            $table->unsignedDecimal('cost_price')->nullable();
            $table->unsignedDecimal('selling_price')->nullable();

            $table->unsignedDecimal('margin')->nullable();

            $table->unsignedDecimal('width')->nullable();
            $table->unsignedDecimal('height')->nullable();
            $table->unsignedDecimal('weight')->nullable();

            $table->string('barcode', 100)->index()->nullable();
            $table->string('location', 255)->nullable();

            $table->foreignIdFor(Color::class)
                  ->nullable()
                  ->constrained()
                  ->cascadeOnUpdate()
                  ->onDelete("set null");

            $table->foreignIdFor(Material::class)
                  ->nullable()
                  ->constrained()
                  ->cascadeOnUpdate()
                  ->onDelete("set null");

            $table->foreignIdFor(Vendor::class)
                  ->nullable()
                  ->constrained()
                  ->cascadeOnUpdate()
                  ->onDelete("set null");

            $table->foreignIdFor(Type::class)
                  ->nullable()
                  ->constrained()
                  ->cascadeOnUpdate()
                  ->onDelete('set null');

            $table->text('caption')->nullable();
            $table->text('description')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if ( ! app()->isProduction()) {
            Schema::dropIfExists('products');
        }
    }
};
