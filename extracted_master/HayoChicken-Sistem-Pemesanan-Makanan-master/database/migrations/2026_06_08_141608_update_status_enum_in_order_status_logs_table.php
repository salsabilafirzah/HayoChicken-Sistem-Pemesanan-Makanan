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
        Schema::table('order_status_logs', function (Blueprint $table) {
            // Kita gunakan DB::statement karena Laravel Blueprint tidak mendukung perubahan enum dengan mudah di beberapa DB
            \Illuminate\Support\Facades\DB::statement("ALTER TABLE order_status_logs MODIFY COLUMN from_status ENUM('NEW', 'PENDING_VERIFICATION', 'PROCESSING', 'DELIVERING', 'DONE', 'REJECTED') NULL");
            \Illuminate\Support\Facades\DB::statement("ALTER TABLE order_status_logs MODIFY COLUMN to_status ENUM('NEW', 'PENDING_VERIFICATION', 'PROCESSING', 'DELIVERING', 'DONE', 'REJECTED') NOT NULL");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('order_status_logs', function (Blueprint $table) {
            \Illuminate\Support\Facades\DB::statement("ALTER TABLE order_status_logs MODIFY COLUMN from_status ENUM('NEW', 'PROCESSING', 'DELIVERING', 'DONE', 'REJECTED') NULL");
            \Illuminate\Support\Facades\DB::statement("ALTER TABLE order_status_logs MODIFY COLUMN to_status ENUM('NEW', 'PROCESSING', 'DELIVERING', 'DONE', 'REJECTED') NOT NULL");
        });
    }
};
