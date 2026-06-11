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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number', 30)->unique();
            $table->foreignId('user_id')->index()->constrained('users')->onDelete('restrict');
            $table->enum('status', ['NEW', 'PENDING_VERIFICATION', 'PROCESSING', 'DELIVERING', 'DONE', 'REJECTED'])->default('NEW');
            $table->text('delivery_address');
            $table->enum('payment_method', ['CASH', 'COD', 'QRIS_MANUAL']);
            $table->string('payment_receipt', 500)->nullable();
            $table->unsignedInteger('total_amount');
            $table->text('rejection_reason')->nullable();
            $table->timestamps();

            // Index untuk optimasi analitik
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
