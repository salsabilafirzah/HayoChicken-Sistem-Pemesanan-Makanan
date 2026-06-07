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
        Schema::create('order_status_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->index()->constrained('orders')->onDelete('cascade');
            $table->foreignId('changed_by_user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->enum('from_status', ['NEW', 'PROCESSING', 'DELIVERING', 'DONE', 'REJECTED'])->nullable();
            $table->enum('to_status', ['NEW', 'PROCESSING', 'DELIVERING', 'DONE', 'REJECTED']);
            $table->text('notes')->nullable();
            $table->timestamp('created_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_status_logs');
    }
};
