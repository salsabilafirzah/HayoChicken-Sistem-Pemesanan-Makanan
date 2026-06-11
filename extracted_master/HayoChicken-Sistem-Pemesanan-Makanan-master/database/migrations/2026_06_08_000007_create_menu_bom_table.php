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
        Schema::create('menu_bom', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->index()->constrained('products')->onDelete('cascade');
            $table->unsignedInteger('raw_material_id');
            $table->decimal('quantity_needed', 10, 2);
            $table->timestamps();

            $table->foreign('raw_material_id')->references('id')->on('raw_materials')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('menu_bom');
    }
};
