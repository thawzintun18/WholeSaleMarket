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
        Schema::create('backup_logs', function (Blueprint $table) {
            $table->id();

            // Backup Type
            $table->string('backup_type', 30);
            // Manual, Automatic, Cloud

            // Backup File Information
            $table->string('file_name')->nullable();
            $table->unsignedBigInteger('file_size')->default(0);
            // Bytes

            // Local Backup
            $table->string('local_path')->nullable();

            $table->string('local_status', 20)->default('Pending');
            // Pending, Success, Failed

            // USB Backup
            $table->string('usb_path')->nullable();

            $table->string('usb_status', 20)->default('Pending');
            // Pending, Success, Failed

            // Cloud Backup (Google Drive)
            $table->string('cloud_file_id')->nullable();

            $table->string('cloud_status', 20)->default('Pending');
            // Pending, Success, Failed

            // Overall Status
            $table->string('status', 20)->default('Pending');
            // Pending, Success, Failed, Partial

            $table->text('error_message')->nullable();

            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();

            $table->timestamps();

            $table->index(['backup_type', 'status']);
            $table->index('started_at');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('backup_logs');
    }
};
