<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('category_id')->index();
            $table->string('name', 150)->index();
            $table->string('slug', 150)->unique();
            $table->text('description')->nullable();
            $table->unsignedInteger('base_price');
            $table->string('image_url', 500)->nullable();
            $table->tinyInteger('is_available')->default(1);
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('category_id')->references('id')->on('categories')->onDelete('restrict');
        });

        // Add check constraint for base_price > 0
        DB::statement('ALTER TABLE products ADD CONSTRAINT base_price_positive CHECK (base_price > 0)');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
